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

namespace Mimmi20Test\Form\Element\Links;

use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Navigation\Exception\BadMethodCallException;
use Laminas\Navigation\Page\AbstractPage;
use Mezzio\Navigation\Page\PageInterface;
use Mimmi20\Form\Element\Links\Links;
use Mimmi20Test\Form\Element\Links\TestAsset\TestFormStringUrl;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function get_class;
use function sprintf;

final class LinksTest extends TestCase
{
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetAndGetSeperator(): void
    {
        $expectedSeperator = ' || ';
        $links             = new Links();

        $links->setSeparator($expectedSeperator);

        self::assertSame($expectedSeperator, $links->getSeparator());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetStringHref(): void
    {
        $href          = 'http://www.test.com';
        $expectedLinks = [['href' => $href]];
        $links         = new Links();

        $links->setLinks([$href]);

        self::assertSame($expectedLinks, $links->getLinks());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSetArrayWithoutHref(): void
    {
        $links = new Links();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The href property is required when using an array for links');
        $this->expectExceptionCode(0);
        $links->setLinks([[]]);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetArrayHref(): void
    {
        $href          = 'http://www.test.com';
        $id            = 'abc';
        $expectedLinks = [['href' => $href, 'id' => $id]];
        $links         = new Links();

        $links->setLinks($expectedLinks);

        self::assertSame($expectedLinks, $links->getLinks());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws BadMethodCallException
     */
    public function testSetAbstractPage(): void
    {
        $href   = 'http://www.test.com';
        $id     = 'abc';
        $title  = 'test-title';
        $class  = 'test-class';
        $target = null;
        $label  = 'test-label';

        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page->expects(self::once())
            ->method('getId')
            ->willReturn($id);
        $page->expects(self::once())
            ->method('getTitle')
            ->willReturn($title);
        $page->expects(self::once())
            ->method('getClass')
            ->willReturn($class);
        $page->expects(self::once())
            ->method('getHref')
            ->willReturn($href);
        $page->expects(self::once())
            ->method('getTarget')
            ->willReturn($target);
        $page->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);

        $expectedLinks = [['id' => $id, 'title' => $title, 'class' => $class, 'href' => $href, 'target' => $target, 'label' => $label]];
        $links         = new Links();

        $links->setLinks([$page]);

        self::assertSame($expectedLinks, $links->getLinks());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetPageInterface(): void
    {
        $href   = 'http://www.test.com';
        $id     = 'abc';
        $title  = 'test-title';
        $class  = 'test-class';
        $target = null;
        $label  = 'test-label';

        $page = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page->expects(self::once())
            ->method('getId')
            ->willReturn($id);
        $page->expects(self::once())
            ->method('getTitle')
            ->willReturn($title);
        $page->expects(self::once())
            ->method('getClass')
            ->willReturn($class);
        $page->expects(self::once())
            ->method('getHref')
            ->willReturn($href);
        $page->expects(self::once())
            ->method('getTarget')
            ->willReturn($target);
        $page->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);

        $expectedLinks = [['id' => $id, 'title' => $title, 'class' => $class, 'href' => $href, 'target' => $target, 'label' => $label]];
        $links         = new Links();

        $links->setLinks([$page]);

        self::assertSame($expectedLinks, $links->getLinks());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSetWrongDatatype(): void
    {
        $links = new Links();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('elements to used as links must be string, array, AbstractPage or PageInterface');
        $this->expectExceptionCode(0);
        $links->setLinks([1]);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCanRetrieveDefaultSeparator(): void
    {
        $href              = 'http://www.test.com';
        $expectedSeperator = ' || ';
        $expectedLinks     = [['href' => $href]];
        $form              = new TestFormStringUrl();
        $links             = $form->get('links');

        assert(
            $links instanceof Links,
            sprintf(
                '$colle$linksction should be an Instance of %s, but was %s',
                Links::class,
                get_class($links)
            )
        );

        self::assertSame($expectedSeperator, $links->getSeparator());
        self::assertSame($expectedLinks, $links->getLinks());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testValidationIsEveryTimeTrue(): void
    {
        $form = new TestFormStringUrl();

        $form->setData([]);

        self::assertTrue($form->isValid());
        self::assertSame(
            [],
            $form->getMessages()
        );
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetAndGetValue(): void
    {
        $expectedSeperator = ' || ';
        $links             = new Links();

        $links->setValue($expectedSeperator);

        self::assertNotSame($expectedSeperator, $links->getValue());
    }
}
