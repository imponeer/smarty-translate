<?php

namespace Imponeer\Smarty\Extensions\Translate;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Trans var modifier (aka filter) similar as twig trans function
 *
 * @package Imponeer\Smarty\Extensions\Translate
 */
class TransVarModifier
{
    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
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
    public function __invoke(string $message, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $this->translator->trans($message, $parameters, $domain, $locale);
    }
}