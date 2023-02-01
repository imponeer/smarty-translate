<?php

use Imponeer\Smarty\Extensions\Translate\TransBlock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;
class TransBlockTest extends TestCase
{

    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var TransBlock
     */
    private $plugin;
    /**
     * @var Smarty
     */
    private $smarty;

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


        $this->plugin = new TransBlock($this->translator);

        $this->smarty = new Smarty();
        $this->smarty->caching = Smarty::CACHING_OFF;
        $this->smarty->registerPlugin(
            'block',
            $this->plugin->getName(),
            [$this->plugin, 'execute']
        );

        parent::setUp();
    }

    public function testGetName() {
        $this->assertSame('trans', $this->plugin->getName());
    }

    public function testInvokeWithCorrectDomain() {
        $src = urlencode('{trans domain="default"}test{/trans}');
        $ret = $this->smarty->fetch('eval:urlencode:'.$src);
        $this->assertSame(
            $this->translator->trans('test', [], 'default'),
            $ret
        );
    }

    public function testInvokeWithoutDomain() {
        $src = urlencode('{trans}test2{/trans}');
        $ret = $this->smarty->fetch('eval:urlencode:'.$src);
        $this->assertSame(
            $this->translator->trans('test2'),
            $ret
        );
    }

    public function testInvokeWithCorrectParameters() {
        $src = urlencode('{trans parameters=["value" => "xx"]}test3{/trans}');
        $ret = $this->smarty->fetch('eval:urlencode:'.$src);
        $this->assertSame(
            $this->translator->trans('test3', ['value' => 'xx']),
            $ret
        );
    }

    public function testInvokeWithDomainAndCorrectParameters() {
        $src = urlencode('{trans domain="default" parameters=["value" => "xx"]}test4{/trans}');
        $ret = $this->smarty->fetch('eval:urlencode:'.$src);
        $this->assertSame(
            $this->translator->trans('test4', ['value' => 'xx'], 'default'),
            $ret
        );
    }

    public function testInvokeWithDomainLocaleAndCorrectParameters() {
        $src = urlencode('{trans locale="lt" domain="default" parameters=["value" => "xx"]}test4{/trans}');
        $ret = $this->smarty->fetch('eval:urlencode:'.$src);
        $this->assertSame(
            $this->translator->trans('test4', ['value' => 'xx'], 'default', 'lt'),
            $ret
        );
    }

    public function testInvokeWithNotCorrectDomain() {
        $src = urlencode('{trans domain="default2" parameters=["value" => "xx"]}test4{/trans}');
        $ret = $this->smarty->fetch('eval:urlencode:'.$src);
        $this->assertSame("test4", $ret);
        $this->assertSame(
            $this->translator->trans('test4', ['value' => 'xx'], 'default2'),
            $ret
        );
    }

    public function testInvokeWithNotCorrectParameters() {
        $src = urlencode('{trans domain="default" parameters=["valueX" => "xx"]}test4{/trans}');
        $ret = $this->smarty->fetch('eval:urlencode:'.$src);
        $this->assertSame(
            $this->translator->trans('test4', ['valueX' => 'xx'], 'default'),
            $ret
        );
    }

    public function testInvokeWithNotCorrectLocale() {
        $src = urlencode('{trans domain="default" locale="be" parameters=["valueX" => "xx"]}test4{/trans}');
        $ret = $this->smarty->fetch('eval:urlencode:'.$src);
        $this->assertSame("test4", $ret);
    }

}