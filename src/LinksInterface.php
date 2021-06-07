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

namespace Mimmi20\Form\Element\Links;

use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Navigation\Page\AbstractPage;
use Mezzio\Navigation\Page\PageInterface;
use Traversable;

interface LinksInterface
{
    /**
     * Accepted options for MultiCheckbox:
     * - use_hidden_element: do we render hidden element?
     * - unchecked_value: value for checkbox when unchecked
     * - checked_value: value for checkbox when checked
     *
     * @param array<int, AbstractPage|array|PageInterface|string>|Traversable $options
     *
     * @throws InvalidArgumentException
     */
    public function setOptions($options): self;

    /**
     * @return array<int, array<string, string|null>>
     */
    public function getLinks(): array;

    /**
     * @param array<int, AbstractPage|array|PageInterface|string>|iterable $links
     *
     * @throws InvalidArgumentException
     */
    public function setLinks(iterable $links): self;

    public function getSeparator(): string;

    public function setSeparator(string $separator): self;
}
