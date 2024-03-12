<?php
/**
 * This file is part of the mimmi20/laminas-form-element-links package.
 *
 * Copyright (c) 2021-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20Test\Form\Links;

use Mimmi20\Form\Links\Module;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

final class ModuleTest extends TestCase
{
    /** @throws Exception */
    public function testGetConfig(): void
    {
        $module = new Module();

        $config = $module->getConfig();

        self::assertIsArray($config);
        self::assertCount(2, $config);
        self::assertArrayHasKey('form_elements', $config);
        self::assertArrayHasKey('view_helpers', $config);
    }

    /** @throws Exception */
    public function testGetModuleDependencies(): void
    {
        $module = new Module();

        $config = $module->getModuleDependencies();

        self::assertIsArray($config);
        self::assertCount(4, $config);
        self::assertArrayHasKey(0, $config);
        self::assertContains('Laminas\I18n', $config);
        self::assertContains('Laminas\InputFilter', $config);
        self::assertContains('Laminas\Form', $config);
        self::assertContains('Laminas\Navigation', $config);
    }
}
