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

use Laminas\Form\Element;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\InputFilter\InputProviderInterface;
use Laminas\Navigation\Page\AbstractPage;
use Override;

use function array_key_exists;
use function is_array;
use function is_iterable;
use function is_string;

final class Links extends Element implements InputProviderInterface, LinksInterface
{
    /**
     * @var array<int, array<string, string|null>>
     * @phpstan-var array<int, array{href: string, id?: string|null, title?: string|null, class?: string|null, target?: string|null}>
     */
    private array $links = [];

    /**
     * Breadcrumbs separator string.
     */
    private string $separator = ' | ';

    /**
     * Accepted options for MultiCheckbox:
     * - use_hidden_element: do we render hidden element?
     * - unchecked_value: value for checkbox when unchecked
     * - checked_value: value for checkbox when checked
     *
     * @param iterable<int, AbstractPage|array<array<string, string|null>>|string> $options
     * @phpstan-param array{links?: iterable<int, int|string|array{href?: string, id?: string|null, title?: string|null, class?: string|null, target?: string|null}|AbstractPage>|string, separator?: string, label?: string|null} $options
     *
     * @throws InvalidArgumentException
     */
    #[Override]
    public function setOptions(iterable $options): self
    {
        parent::setOptions($options);

        if (isset($this->options['links'])) {
            $links = $this->options['links'];

            if (!is_iterable($links)) {
                $links = [$links];
            }

            $this->setLinks($links);
        }

        if (isset($this->options['separator'])) {
            $this->setSeparator($this->options['separator']);
        }

        return $this;
    }

    /**
     * @return array<int, array<string, string|null>>
     *
     * @throws void
     */
    #[Override]
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @phpstan-param iterable<int, int|string|array{href?: string, id?: string|null, title?: string|null, class?: string|null, target?: string|null}|AbstractPage> $links
     *
     * @throws InvalidArgumentException
     */
    #[Override]
    public function setLinks(iterable $links): self
    {
        $this->links = [];

        foreach ($links as $link) {
            if (is_string($link)) {
                $this->links[] = ['href' => $link];

                continue;
            }

            if (is_array($link)) {
                if (!array_key_exists('href', $link)) {
                    throw new InvalidArgumentException(
                        'The href property is required when using an array for links',
                    );
                }

                $this->links[] = $link;

                continue;
            }

            if ($link instanceof AbstractPage) {
                $this->links[] = [
                    'class' => $link->getClass(),
                    'href' => $link->getHref(),
                    'id' => $link->getId(),
                    'label' => $link->getLabel(),
                    'target' => $link->getTarget(),
                    'title' => $link->getTitle(),
                ];

                continue;
            }

            throw new InvalidArgumentException(
                'elements to used as links must be string, array, AbstractPage or PageInterface',
            );
        }

        return $this;
    }

    /** @throws void */
    #[Override]
    public function getSeparator(): string
    {
        return $this->separator;
    }

    /** @throws void */
    #[Override]
    public function setSeparator(string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Provide default input rules for this element
     *
     * @return array<string, false|string>
     * @phpstan-return array{name: string, required: false}
     *
     * @throws void
     */
    #[Override]
    public function getInputSpecification(): array
    {
        return [
            'name' => $this->getName() ?? '',
            'required' => false,
        ];
    }

    /**
     * Set the element value, As this Element has no value to send with the form, no value is set
     *
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function setValue(mixed $value): self
    {
        return $this;
    }
}
