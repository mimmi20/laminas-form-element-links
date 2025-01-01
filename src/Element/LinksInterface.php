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

namespace Mimmi20\Form\Links\Element;

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Navigation\Page\AbstractPage;
use Override;

interface LinksInterface extends ElementInterface
{
    /**
     * Accepted options for MultiCheckbox:
     * - use_hidden_element: do we render hidden element?
     * - unchecked_value: value for checkbox when unchecked
     * - checked_value: value for checkbox when checked
     *
     * @param iterable<int, AbstractPage|array<array<string, string|null>>|string> $options
     * @phpstan-param array{links?: iterable<int, int|string|array{href?: string, id?: string|null, title?: string|null, class?: string|null, target?: string|null}|AbstractPage>|string, separator?: string, label?: string|null} $options
     *
     * @return self
     *
     * @throws InvalidArgumentException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    #[Override]
    public function setOptions(iterable $options);

    /**
     * @return array<int, array<string, string|null>>
     *
     * @throws void
     */
    public function getLinks(): array;

    /**
     * @phpstan-param iterable<int, int|string|array{href?: string, id?: string|null, title?: string|null, class?: string|null, target?: string|null}|AbstractPage> $links
     *
     * @return self
     *
     * @throws InvalidArgumentException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setLinks(iterable $links);

    /** @throws void */
    public function getSeparator(): string;

    /**
     * @return self
     *
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setSeparator(string $separator);
}
