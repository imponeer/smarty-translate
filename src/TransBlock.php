<?php

namespace Imponeer\Smarty\Extensions\Translate;

use Smarty\BlockHandler\BlockHandlerInterface;
use Smarty\Template;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Implements smarty {trans}...{/trans} block
 *
 * @package Imponeer\Smarty\Extensions\Translate
 */
class TransBlock implements BlockHandlerInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    public function handle($params, $content, Template $template, &$repeat)
    {
        if (!$repeat && isset($content)) {
            return $this->translator->trans(
                trim($content),
                $params['parameters'] ?? [],
                $params['domain'] ?? null,
                $params['locale'] ?? null
            );
        }

        return $content ?? '';
    }

    public function isCacheable(): bool
    {
        return false;
    }
}
