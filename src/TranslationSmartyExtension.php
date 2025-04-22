<?php

namespace Imponeer\Smarty\Extensions\Translate;

use Smarty\BlockHandler\BlockHandlerInterface;
use Smarty\Extension\Base;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationSmartyExtension extends Base
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    public function getModifierCallback(string $modifierName)
    {
        if ($modifierName === 'trans') {
            return new TransVarModifier($this->translator);
        }

        return parent::getModifierCallback($modifierName);
    }

    public function getBlockHandler(string $blockTagName): ?BlockHandlerInterface
    {
        if ($blockTagName === 'trans') {
            return new TransBlock($this->translator);
        }

        return parent::getBlockHandler($blockTagName);
    }
}
