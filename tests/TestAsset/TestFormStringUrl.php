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

namespace Mimmi20Test\Form\Links\TestAsset;

use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\Form;
use Mimmi20\Form\Links\Element\Links;

final class TestFormStringUrl extends Form
{
    /** @throws InvalidArgumentException */
    public function __construct()
    {
        parent::__construct('collection');

        $this->setInputFilter(new InputFilter());

        $this->add(
            [
                'type' => Links::class,
                'name' => 'links',
                'options' => [
                    'separator' => ' || ',
                    'links' => 'http://www.test.com',
                ],
            ],
        );
    }
}
