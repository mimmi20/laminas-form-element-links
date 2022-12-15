<?php
/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Form\Links\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\EscapeHtml;
use Mimmi20\Form\Element\Links\LinksInterface as LinksElement;

use function array_key_exists;
use function array_merge;
use function array_unique;
use function assert;
use function explode;
use function implode;
use function is_string;
use function sprintf;
use function trim;

use const PHP_EOL;

final class FormLinks extends AbstractHelper
{
    /**
     * Attributes valid for the current tag
     *
     * Will vary based on whether a select, option, or optgroup is being rendered
     *
     * @var array<string, bool>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $validTagAttributes = [
        'href' => true,
        'target' => true,
    ];

    /**
     * @var array<string, bool>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $translatableAttributes = ['title' => true];
    private ?Translate $translate;
    private EscapeHtml $escapeHtml;

    public function __construct(
        EscapeHtml $escaper,
        ?Translate $translator = null
    ) {
        $this->escapeHtml = $escaper;
        $this->translate  = $translator;
    }

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @return FormLinks|string
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __invoke(?ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * Render a form <select> element from the provided $element
     *
     * @throws Exception\InvalidArgumentException
     */
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof LinksElement) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s',
                    __METHOD__,
                    LinksElement::class
                )
            );
        }

        $renderedLinks = [];

        foreach ($element->getLinks() as $link) {
            $classes = [];
            $label   = $link['label'] ?? '';
            unset($link['label']);

            $attributes = $element->getAttributes();

            if (array_key_exists('class', $attributes)) {
                $classes = array_merge($classes, explode(' ', $attributes['class']));
                unset($attributes['class']);
            }

            if (array_key_exists('class', $link)) {
                $classes = array_merge($classes, explode(' ', (string) $link['class']));
                unset($link['class']);
            }

            $linkAttributes          = array_merge($attributes, $link);
            $linkAttributes['class'] = trim(implode(' ', array_unique($classes)));

            if ('' !== $label) {
                // Translate the label
                if (null !== $this->translate) {
                    $label = ($this->translate)($label, $this->getTranslatorTextDomain());
                }

                $label = ($this->escapeHtml)($label);

                assert(is_string($label));
            }

            $renderedLinks[] = sprintf(
                '<a %s>%s</a>',
                $this->createAttributesString($linkAttributes),
                $label
            );
        }

        $indent = $this->getIndent();

        return $indent . implode(PHP_EOL . $indent . $element->getSeparator() . PHP_EOL . $indent, $renderedLinks);
    }

    private string $indent = '';

    /**
     * Set the indentation string for using in {@link render()}, optionally a
     * number of spaces to indent with
     *
     * @param int|string $indent
     */
    public function setIndent($indent): self
    {
        $this->indent = $this->getWhitespace($indent);

        return $this;
    }

    /**
     * Returns indentation
     */
    public function getIndent(): string
    {
        return $this->indent;
    }

    // Util methods:

    /**
     * Retrieve whitespace representation of $indent
     *
     * @param int|string $indent
     */
    protected function getWhitespace($indent): string
    {
        if (is_int($indent)) {
            $indent = str_repeat(' ', $indent);
        }

        return (string) $indent;
    }
}
