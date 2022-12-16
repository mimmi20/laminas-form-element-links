<?php
/**
 * This file is part of the mimmi20/laminas-form-element-links package.
 *
 * Copyright (c) 2021-2022, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Form\Links;

use Laminas\Form\ElementFactory;
use Mimmi20\Form\Links\Element\Links;
use Mimmi20\Form\Links\Element\LinksInterface;
use Mimmi20\Form\Links\View\Helper\FormLinks;
use Mimmi20\Form\Links\View\Helper\FormLinksFactory;

final class ConfigProvider
{
    /**
     * Return general-purpose laminas-navigation configuration.
     *
     * @return array<string, array<string, array<string, string>>>
     * @phpstan-return array{form_elements: array{aliases: array<string, class-string>, factories: array<class-string, class-string>}, view_helpers: array{aliases: array<string, class-string>, factories: array<class-string, class-string>}}
     *
     * @throws void
     */
    public function __invoke(): array
    {
        return [
            'form_elements' => $this->getFormElementConfig(),
            'view_helpers' => $this->getViewHelperConfig(),
        ];
    }

    /**
     * Return application-level dependency configuration.
     *
     * @return array<string, array<string, string>>
     * @phpstan-return array{aliases: array<string, class-string>, factories: array<class-string, class-string>}
     *
     * @throws void
     */
    public function getFormElementConfig(): array
    {
        return [
            'aliases' => [
                'links' => Links::class,
                LinksInterface::class => Links::class,
            ],
            'factories' => [
                Links::class => ElementFactory::class,
            ],
        ];
    }

    /**
     * Return application-level dependency configuration.
     *
     * @return array<string, array<string, string>>
     * @phpstan-return array{aliases: array<string, class-string>, factories: array<class-string, class-string>}
     *
     * @throws void
     */
    public function getViewHelperConfig(): array
    {
        return [
            'aliases' => [
                'formlinks' => FormLinks::class,
                'form_links' => FormLinks::class,
                'formLinks' => FormLinks::class,
                'FormLinks' => FormLinks::class,
            ],
            'factories' => [
                FormLinks::class => FormLinksFactory::class,
            ],
        ];
    }
}
