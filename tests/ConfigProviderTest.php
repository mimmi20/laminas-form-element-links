<?php

/**
 * This file is part of the mimmi20/laminas-form-element-links package.
 *
 * Copyright (c) 2021-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20Test\Form\Links;

use Mimmi20\Form\Links\ConfigProvider;
use Mimmi20\Form\Links\Element\Links;
use Mimmi20\Form\Links\Element\LinksInterface;
use Mimmi20\Form\Links\View\Helper\FormLinks;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    /** @throws Exception */
    public function testProviderDefinesExpectedFactoryServices(): void
    {
        $formElementConfig = (new ConfigProvider())->getFormElementConfig();
        self::assertIsArray($formElementConfig);

        self::assertArrayHasKey('factories', $formElementConfig);
        $factories = $formElementConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(Links::class, $factories);

        self::assertArrayHasKey('aliases', $formElementConfig);
        $aliases = $formElementConfig['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('links', $aliases);
        self::assertArrayHasKey(LinksInterface::class, $aliases);
    }

    /** @throws Exception */
    public function testProviderDefinesExpectedFactoryServices2(): void
    {
        $viewHelperConfig = (new ConfigProvider())->getViewHelperConfig();
        self::assertIsArray($viewHelperConfig);

        self::assertArrayHasKey('factories', $viewHelperConfig);
        $factories = $viewHelperConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(FormLinks::class, $factories);

        self::assertArrayHasKey('aliases', $viewHelperConfig);
        $aliases = $viewHelperConfig['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('formlinks', $aliases);
        self::assertArrayHasKey('form_links', $aliases);
        self::assertArrayHasKey('formLinks', $aliases);
        self::assertArrayHasKey('FormLinks', $aliases);
    }

    /** @throws Exception */
    public function testInvocationReturnsArrayWithDependencies(): void
    {
        $config = (new ConfigProvider())();

        self::assertIsArray($config);
        self::assertArrayHasKey('form_elements', $config);
        self::assertArrayHasKey('view_helpers', $config);

        $formElementConfig = $config['form_elements'];
        self::assertArrayHasKey('factories', $formElementConfig);
        $factories = $formElementConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(Links::class, $factories);

        self::assertArrayHasKey('aliases', $formElementConfig);
        $aliases = $formElementConfig['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('links', $aliases);
        self::assertArrayHasKey(LinksInterface::class, $aliases);

        $viewHelperConfig = $config['view_helpers'];
        self::assertIsArray($viewHelperConfig);

        self::assertArrayHasKey('factories', $viewHelperConfig);
        $factories = $viewHelperConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(FormLinks::class, $factories);

        self::assertArrayHasKey('aliases', $viewHelperConfig);
        $aliases = $viewHelperConfig['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('formlinks', $aliases);
        self::assertArrayHasKey('form_links', $aliases);
        self::assertArrayHasKey('formLinks', $aliases);
        self::assertArrayHasKey('FormLinks', $aliases);
    }
}
