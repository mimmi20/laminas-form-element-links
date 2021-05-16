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

use Laminas\Filter\StringTrim;
use Laminas\Form\Element;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\InputFilter\InputProviderInterface;
use Laminas\Navigation\Page\AbstractPage;
use Laminas\Validator\Uri;
use Laminas\Validator\ValidatorInterface;
use Mezzio\Navigation\Page\PageInterface;
use Traversable;

use function array_key_exists;
use function is_array;
use function is_iterable;
use function is_string;

final class Links extends Element implements InputProviderInterface
{
    private ?ValidatorInterface $validator = null;

    /** @var array<int, array<string, string|null>> */
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
     * @param array<int, AbstractPage|array|PageInterface|string>|Traversable $options
     *
     * @throws InvalidArgumentException
     */
    public function setOptions($options): self
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
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param array<int, AbstractPage|array|PageInterface|string>|iterable $links
     *
     * @throws InvalidArgumentException
     */
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
                        'The href property is required when using an array for links'
                    );
                }

                $this->links[] = $link;
                continue;
            }

            if ($link instanceof AbstractPage) {
                $this->links[] = [
                    'id' => $link->getId(),
                    'title' => $link->getTitle(),
                    'class' => $link->getClass(),
                    'href' => $link->getHref(),
                    'target' => $link->getTarget(),
                ];
                continue;
            }

            if ($link instanceof PageInterface) {
                $this->links[] = [
                    'id' => $link->getId(),
                    'title' => $link->getTitle(),
                    'class' => $link->getClass(),
                    'href' => $link->getHref(),
                    'target' => $link->getTarget(),
                ];
                continue;
            }

            throw new InvalidArgumentException(
                'elements to used as links must be string, array, AbstractPage or PageInterface'
            );
        }

        return $this;
    }

    public function getSeparator(): string
    {
        return $this->separator;
    }

    public function setSeparator(string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Get primary validator
     */
    public function getValidator(): ValidatorInterface
    {
        if (null === $this->validator) {
            $this->validator = new Uri();
        }

        return $this->validator;
    }

    /**
     * Sets the primary validator to use for this element
     */
    public function setValidator(ValidatorInterface $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Provide default input rules for this element
     *
     * Attaches an email validator.
     *
     * @return array<string, array<int, array<string, class-string>|ValidatorInterface>|int|string|false>
     */
    public function getInputSpecification(): array
    {
        return [
            'name' => $this->getName(),
            'required' => false,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                $this->getValidator(),
            ],
        ];
    }

    /**
     * Set the element value
     *
     * @param mixed $value
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Retrieve the element value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
