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

namespace Mimmi20Test\Form\Links\View\Helper;

use Laminas\Form\Element\Text;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\I18n\Exception\RuntimeException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\EscapeHtml;
use Mimmi20\Form\Links\Element\LinksInterface as LinksElement;
use Mimmi20\Form\Links\View\Helper\FormLinks;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function sprintf;

use const PHP_EOL;

final class FormLinksTest extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithWrongElement(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element is of type %s',
                'Mimmi20\Form\Links\View\Helper\FormLinks::render',
                LinksElement::class,
            ),
        );
        $this->expectExceptionCode(0);

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderEmptyLinkList(): void
    {
        $expected = '';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn('');

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderSingleLink(): void
    {
        $class        = 'test-class';
        $ariaLabel    = 'test';
        $attributes   = ['class' => $class, 'aria-label' => $ariaLabel];
        $label        = 'test-label';
        $labelEscaped = 'test-label-escaped';
        $linkClass    = 'abc';
        $seperator    = '';

        $expected = sprintf(
            '<a aria-label="%s" href="&#x23;" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass,
            $labelEscaped,
        );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label)
            ->willReturn($labelEscaped);

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass,
                        'href' => '#',
                        'label' => $label,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderDoubleLink(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';

        $expected = sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $label1Escaped,
        ) . PHP_EOL
            . $seperator . PHP_EOL
            . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>',
                $ariaLabel,
                $class,
                $linkClass2,
                $label2Escaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1, EscapeHtml::RECURSE_NONE, $label1Escaped],
                    [$label2, EscapeHtml::RECURSE_NONE, $label2Escaped],
                ],
            );

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderDoubleLinkWithIndent(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';
        $indent        = '    ';

        $expected = $indent . sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $label1Escaped,
        ) . PHP_EOL
            . $indent . $seperator . PHP_EOL
            . $indent . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>',
                $ariaLabel,
                $class,
                $linkClass2,
                $label2Escaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1, EscapeHtml::RECURSE_NONE, $label1Escaped],
                    [$label2, EscapeHtml::RECURSE_NONE, $label2Escaped],
                ],
            );

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helper->setIndent($indent);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderDoubleLinkWithIndent2(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';
        $indent        = '    ';

        $expected = $indent . sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $label1Escaped,
        ) . PHP_EOL
            . $indent . $seperator . PHP_EOL
            . $indent . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>',
                $ariaLabel,
                $class,
                $linkClass2,
                $label2Escaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1, EscapeHtml::RECURSE_NONE, $label1Escaped],
                    [$label2, EscapeHtml::RECURSE_NONE, $label2Escaped],
                ],
            );

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1 . " \t",
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2 . " \t",
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helper->setIndent($indent);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderDoubleLinkWithIndent3(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class . " \t", 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';
        $indent        = '    ';

        $expected = $indent . sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $label1Escaped,
        ) . PHP_EOL
            . $indent . $seperator . PHP_EOL
            . $indent . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>',
                $ariaLabel,
                $class,
                $linkClass2,
                $label2Escaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1, EscapeHtml::RECURSE_NONE, $label1Escaped],
                    [$label2, EscapeHtml::RECURSE_NONE, $label2Escaped],
                ],
            );

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1 . ' ' . $class,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helper->setIndent($indent);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderDoubleLinkWithIndent4(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $seperator     = '||';
        $indent        = '    ';

        $expected = $indent . sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $label1Escaped,
        ) . PHP_EOL
            . $indent . $seperator . PHP_EOL
            . $indent . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s">%s</a>',
                $ariaLabel,
                $class,
                $label2Escaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1, EscapeHtml::RECURSE_NONE, $label1Escaped],
                    [$label2, EscapeHtml::RECURSE_NONE, $label2Escaped],
                ],
            );

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1 . ' ' . $class,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => null,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helper->setIndent($indent);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderDoubleLinkWithoutLabel(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = '';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';

        $expected = sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $label1,
        ) . PHP_EOL
            . $seperator . PHP_EOL
            . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>',
                $ariaLabel,
                $class,
                $linkClass2,
                $label2Escaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label2)
            ->willReturn($label2Escaped);

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderDoubleLinkWithTranslator(): void
    {
        $class                  = 'test-class';
        $ariaLabel              = 'test';
        $attributes             = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1                 = 'test-label1';
        $label1Tranlated        = 'test-label1-translated';
        $label1TranlatedEscaped = 'test-label1-translated-escaped';
        $linkClass1             = 'abc';
        $label2                 = 'test-label2';
        $label2Tranlated        = 'test-label2-translated';
        $label2TranlatedEscaped = 'test-label2-translated-escaped';
        $linkClass2             = 'xyz';
        $seperator              = '||';
        $textDomain             = 'test-domain';

        $expected = sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $label1TranlatedEscaped,
        ) . PHP_EOL
            . $seperator . PHP_EOL
            . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>',
                $ariaLabel,
                $class,
                $linkClass2,
                $label2TranlatedEscaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1Tranlated, EscapeHtml::RECURSE_NONE, $label1TranlatedEscaped],
                    [$label2Tranlated, EscapeHtml::RECURSE_NONE, $label2TranlatedEscaped],
                ],
            );

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1, $textDomain, null, $label1Tranlated],
                    [$label2, $textDomain, null, $label2Tranlated],
                ],
            );

        $helper = new FormLinks($escapeHtml, $translator);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderDoubleLinkWithTranslatorButWithoutLabel(): void
    {
        $class                  = 'test-class';
        $ariaLabel              = 'test';
        $attributes             = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1                 = '';
        $linkClass1             = 'abc';
        $label2                 = 'test-label2';
        $label2Tranlated        = 'test-label2-translated';
        $label2TranlatedEscaped = 'test-label2-translated-escaped';
        $linkClass2             = 'xyz';
        $seperator              = '||';
        $textDomain             = 'test-domain';

        $expected = sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $label1,
        ) . PHP_EOL
            . $seperator . PHP_EOL
            . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>',
                $ariaLabel,
                $class,
                $linkClass2,
                $label2TranlatedEscaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label2Tranlated)
            ->willReturn($label2TranlatedEscaped);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label2, $textDomain)
            ->willReturn($label2Tranlated);

        $helper = new FormLinks($escapeHtml, $translator);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeDoubleLink1(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';

        $expected = sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $label1Escaped,
        ) . PHP_EOL
            . $seperator . PHP_EOL
            . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>',
                $ariaLabel,
                $class,
                $linkClass2,
                $label2Escaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1, EscapeHtml::RECURSE_NONE, $label1Escaped],
                    [$label2, EscapeHtml::RECURSE_NONE, $label2Escaped],
                ],
            );

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helperObject = $helper();

        assert($helperObject instanceof FormLinks);

        self::assertSame($expected, $helperObject->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeDoubleLink2(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';

        $expected = sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $label1Escaped,
        ) . PHP_EOL
            . $seperator . PHP_EOL
            . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>',
                $ariaLabel,
                $class,
                $linkClass2,
                $label2Escaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1, EscapeHtml::RECURSE_NONE, $label1Escaped],
                    [$label2, EscapeHtml::RECURSE_NONE, $label2Escaped],
                ],
            );

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        self::assertSame($expected, $helper($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeDoubleLink3(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $linkClass1    = 'abc';
        $linkClass2    = 'xyz';
        $attributes    = ['class' => $class . ' ' . $linkClass1 . ' ' . $linkClass2, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $seperator     = '||';

        $expected = sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s&#x20;%s">%s</a>',
            $ariaLabel,
            $class,
            $linkClass1,
            $linkClass2,
            $label1Escaped,
        ) . PHP_EOL
            . $seperator . PHP_EOL
            . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s&#x20;%s">%s</a>',
                $ariaLabel,
                $class,
                $linkClass1,
                $linkClass2,
                $label2Escaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1, EscapeHtml::RECURSE_NONE, $label1Escaped],
                    [$label2, EscapeHtml::RECURSE_NONE, $label2Escaped],
                ],
            );

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        self::assertSame($expected, $helper($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeDoubleLink4(): void
    {
        $ariaLabel     = 'test';
        $attributes    = ['class' => null, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';

        $expected = sprintf(
            '<a aria-label="%s" href="&#x23;1" class="%s">%s</a>',
            $ariaLabel,
            $linkClass1,
            $label1Escaped,
        ) . PHP_EOL
            . $seperator . PHP_EOL
            . sprintf(
                '<a aria-label="%s" href="&#x23;2" class="%s">%s</a>',
                $ariaLabel,
                $linkClass2,
                $label2Escaped,
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$label1, EscapeHtml::RECURSE_NONE, $label1Escaped],
                    [$label2, EscapeHtml::RECURSE_NONE, $label2Escaped],
                ],
            );

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'class' => $linkClass1,
                        'href' => '#1',
                        'label' => $label1,
                    ],
                    [
                        'class' => $linkClass2,
                        'href' => '#2',
                        'label' => $label2,
                    ],
                ],
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helperObject = $helper();

        assert($helperObject instanceof FormLinks);

        self::assertSame($expected, $helperObject->render($element));
    }

    /** @throws Exception */
    public function testSetGetIndent1(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormLinks($escapeHtml, null);

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetIndent2(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormLinks($escapeHtml, null);

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
