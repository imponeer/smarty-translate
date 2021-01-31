<?php

namespace Imponeer\Smarty\Extensions\Translate;

use Imponeer\Contracts\Smarty\Extension\SmartyModifierInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Trans var modifier (aka filter) similar as twig trans function
 *
 * @package Imponeer\Smarty\Extensions\Translate
 */
class TransVarModifier implements SmartyModifierInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * TransVarModifier constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Executes modifier
     *
     * @param string $message Message or id string to translate
     * @param array $parameters Translation parameters
     * @param string|null $domain Translation domain
     * @param string|null $locale Locale
     *
     * @return string
     */
    public function execute($message, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $this->translator->trans($message, $parameters, $domain, $locale);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'trans';
    }
}