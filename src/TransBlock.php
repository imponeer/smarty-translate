<?php

namespace Imponeer\Smarty\Extensions\Translate;

use Imponeer\Contracts\Smarty\Extension\SmartyBlockInterface;
use Smarty_Internal_Template;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Implements smarty {trans}...{/trans} block
 *
 * @package Imponeer\Smarty\Extensions\Translate
 */
class TransBlock implements SmartyBlockInterface
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
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'trans';
    }

    /**
     * @inheritDoc
     */
    public function execute(array $params, ?string $content, Smarty_Internal_Template $template, &$repeat)
    {
        if (!$repeat && isset($content)) {
            return $this->translator->trans(
                trim($content),
                $params['parameters'] ?? [],
                $params['domain'] ?? null,
                $params['locale'] ?? null
            );
        }
    }
}