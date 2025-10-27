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

namespace Mimmi20Test\Form\Links\TestAsset;

use Laminas\Form\Exception\ExceptionInterface;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\Form;
use Mimmi20\Form\Links\Element\Links;

/**
 * @template TFilteredValues of object
 * @extends Form<TFilteredValues>
 */
final class TestFormStringUrl extends Form
{
    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public function __construct()
    {
        parent::__construct('collection');

        /** @var InputFilter<TFilteredValues> $inputfilter */
        $inputfilter = new InputFilter();

        $this->setInputFilter($inputfilter);

        $this->add(
            [
                'name' => 'links',
                'options' => [
                    'links' => 'http://www.test.com',
                    'separator' => ' || ',
                ],
                'type' => Links::class,
            ],
        );
    }
}
