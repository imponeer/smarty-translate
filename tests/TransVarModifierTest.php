<?php

namespace Imponeer\Smarty\Extensions\Translate\Tests;

use Imponeer\Smarty\Extensions\Translate\TranslationSmartyExtension;
use PHPUnit\Framework\TestCase;
use Smarty\Smarty;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

class TransVarModifierTest extends TestCase
{
    private Translator $translator;
    private Smarty $smarty;

    protected function setUp(): void
    {
        $this->translator = new Translator('en');
        $this->translator->addLoader(
            "array",
            new ArrayLoader()
        );
        $this->translator->addResource('array', [
            'test' => 'TEST',
        ], 'en', 'default');
        $this->translator->addResource('array', [
            'test2' => 'TEST2',
        ], 'en');
        $this->translator->addResource('array', [
            'test3' => 'TEST3 {value}',
        ], 'en');
        $this->translator->addResource('array', [
            'test4' => 'TEST4 {value}',
        ], 'en', 'default');
        $this->translator->addResource('array', [
            'test4' => 'TEST4[X] {value}',
        ], 'lt', 'default');

        $this->smarty = new Smarty();
        $this->smarty->caching = Smarty::CACHING_OFF;
        $this->smarty->addExtension(new TranslationSmartyExtension($this->translator));

        parent::setUp();
    }

    public function testInvokeWithCorrectDomain(): void
    {
        $src = urlencode('{"test"|trans:[]:"default"}');
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);
        $this->assertSame(
            $this->translator->trans('test', [], 'default'),
            $ret
        );
    }

    public function testInvokeWithoutDomain(): void
    {
        $src = urlencode('{"test2"|trans}');
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);
        $this->assertSame(
            $this->translator->trans('test2'),
            $ret
        );
    }

    public function testInvokeWithCorrectParameters(): void
    {
        $src = urlencode('{"test3"|trans:["value" => "xx"]}');
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);
        $this->assertSame(
            $this->translator->trans('test3', ['value' => 'xx']),
            $ret
        );
    }

    public function testInvokeWithDomainAndCorrectParameters(): void
    {
        $src = urlencode('{"test4"|trans:["value" => "xx"]:"default"}');
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);
        $this->assertSame(
            $this->translator->trans('test4', ['value' => 'xx'], 'default'),
            $ret
        );
    }

    public function testInvokeWithDomainLocaleAndCorrectParameters(): void
    {
        $src = urlencode('{"test4"|trans:["value" => "xx"]:"default":"lt"}');
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);
        $this->assertSame(
            $this->translator->trans('test4', ['value' => 'xx'], 'default', 'lt'),
            $ret
        );
    }

    public function testInvokeWithNotCorrectDomain(): void
    {
        $src = urlencode('{"test4"|trans:["value" => "xx"]:"default2"}');
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);
        $this->assertSame("test4", $ret);
        $this->assertSame(
            $this->translator->trans('test4', ['value' => 'xx'], 'default2'),
            $ret
        );
    }

    public function testInvokeWithNotCorrectParameters(): void
    {
        $src = urlencode('{"test4"|trans:["valueX" => "xx"]:"default"}');
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);
        $this->assertSame(
            $this->translator->trans('test4', ['valueX' => 'xx'], 'default'),
            $ret
        );
    }

    public function testInvokeWithNotCorrectLocale(): void
    {
        $src = urlencode('{"test4"|trans:["valueX" => "xx"]:"default":"be"}');
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);
        $this->assertSame(
            $this->translator->trans('test4', ['value' => 'xx'], 'default', 'be'),
            $ret
        );
    }
}
