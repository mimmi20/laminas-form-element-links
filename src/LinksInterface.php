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

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Navigation\Page\AbstractPage;
use Mezzio\Navigation\Page\PageInterface;
use Traversable;

interface LinksInterface extends ElementInterface
{
    /**
     * Accepted options for MultiCheckbox:
     * - use_hidden_element: do we render hidden element?
     * - unchecked_value: value for checkbox when unchecked
     * - checked_value: value for checkbox when checked
     *
     * @param array<int, AbstractPage|array|PageInterface|string>|Traversable $options
     *
     * @return self
     *
     * @throws InvalidArgumentException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setOptions($options);

    /**
     * @return array<int, array<string, string|null>>
     */
    public function getLinks(): array;

    /**
     * @param array<int, AbstractPage|array|PageInterface|string>|iterable $links
     *
     * @return self
     *
     * @throws InvalidArgumentException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setLinks(iterable $links);

    public function getSeparator(): string;

    /**
     * @return self
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setSeparator(string $separator);
}
