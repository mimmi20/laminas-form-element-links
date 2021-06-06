<?php
/**
 * This file is part of the mimmi20/laminas-form-element-links package.
 *
 * Copyright (c) 2021, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20Test\Form\Element\Links;

use Mimmi20\Form\Element\Links\ConfigProvider;
use Mimmi20\Form\Element\Links\Links;
use Mimmi20\Form\Element\Links\LinksInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

final class ConfigProviderTest extends TestCase
{
    private ConfigProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new ConfigProvider();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testProviderDefinesExpectedFactoryServices(): void
    {
        $formElementConfig = $this->provider->getFormElementConfig();
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

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testInvocationReturnsArrayWithDependencies(): void
    {
        $config = ($this->provider)();

        self::assertIsArray($config);
        self::assertArrayHasKey('form_elements', $config);

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
    }
}
