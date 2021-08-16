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
    public function testGetInputSpecification(): void
    {
        $name     = 'test-name';
        $expected = [
            'name' => $name,
            'required' => false,
        ];
        $links    = new Links();

        $links->setName($name);

        self::assertSame($name, $links->getName());
        self::assertSame($expected, $links->getInputSpecification());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetStringHref(): void
    {
        $href1         = 'http://www.test.com';
        $href2         = 'http://www.test.org';
        $href3         = 'http://www.test.org/test';
        $expectedLinks = [['href' => $href1], ['href' => $href2], ['href' => $href3]];
        $links         = new Links();

        $links->setLinks([$href1, $href2, $href3]);

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
        $href1         = 'http://www.test.com';
        $href2         = 'http://www.test.org';
        $href3         = 'http://www.test.org/test';
        $id            = 'abc';
        $expectedLinks = [['href' => $href1, 'id' => $id], ['href' => $href2, 'id' => $id], ['href' => $href3, 'id' => $id]];
        $links         = new Links();

        $links->setLinks($expectedLinks);

        self::assertSame($expectedLinks, $links->getLinks());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetAbstractPage(): void
    {
        $href1  = 'http://www.test.com';
        $href2  = 'http://www.test.org';
        $href3  = 'http://www.test.org/test';
        $id     = 'abc';
        $title  = 'test-title';
        $class  = 'test-class';
        $target = null;
        $label  = 'test-label';

        $page1 = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page1->expects(self::once())
            ->method('getId')
            ->willReturn($id);
        $page1->expects(self::once())
            ->method('getTitle')
            ->willReturn($title);
        $page1->expects(self::once())
            ->method('getClass')
            ->willReturn($class);
        $page1->expects(self::once())
            ->method('getHref')
            ->willReturn($href1);
        $page1->expects(self::once())
            ->method('getTarget')
            ->willReturn($target);
        $page1->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);

        $page2 = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page2->expects(self::once())
            ->method('getId')
            ->willReturn($id);
        $page2->expects(self::once())
            ->method('getTitle')
            ->willReturn($title);
        $page2->expects(self::once())
            ->method('getClass')
            ->willReturn($class);
        $page2->expects(self::once())
            ->method('getHref')
            ->willReturn($href2);
        $page2->expects(self::once())
            ->method('getTarget')
            ->willReturn($target);
        $page2->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);

        $page3 = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page3->expects(self::once())
            ->method('getId')
            ->willReturn($id);
        $page3->expects(self::once())
            ->method('getTitle')
            ->willReturn($title);
        $page3->expects(self::once())
            ->method('getClass')
            ->willReturn($class);
        $page3->expects(self::once())
            ->method('getHref')
            ->willReturn($href3);
        $page3->expects(self::once())
            ->method('getTarget')
            ->willReturn($target);
        $page3->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);

        $expectedLinks = [
            ['id' => $id, 'title' => $title, 'class' => $class, 'href' => $href1, 'target' => $target, 'label' => $label],
            ['id' => $id, 'title' => $title, 'class' => $class, 'href' => $href2, 'target' => $target, 'label' => $label],
            ['id' => $id, 'title' => $title, 'class' => $class, 'href' => $href3, 'target' => $target, 'label' => $label],
        ];
        $links         = new Links();

        $links->setLinks([$page1, $page2, $page3]);

        self::assertSame($expectedLinks, $links->getLinks());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetPageInterface(): void
    {
        $href1  = 'http://www.test.com';
        $href2  = 'http://www.test.org';
        $href3  = 'http://www.test.org/test';
        $id     = 'abc';
        $title  = 'test-title';
        $class  = 'test-class';
        $target = null;
        $label  = 'test-label';

        $page1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page1->expects(self::once())
            ->method('getId')
            ->willReturn($id);
        $page1->expects(self::once())
            ->method('getTitle')
            ->willReturn($title);
        $page1->expects(self::once())
            ->method('getClass')
            ->willReturn($class);
        $page1->expects(self::once())
            ->method('getHref')
            ->willReturn($href1);
        $page1->expects(self::once())
            ->method('getTarget')
            ->willReturn($target);
        $page1->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);

        $page2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page2->expects(self::once())
            ->method('getId')
            ->willReturn($id);
        $page2->expects(self::once())
            ->method('getTitle')
            ->willReturn($title);
        $page2->expects(self::once())
            ->method('getClass')
            ->willReturn($class);
        $page2->expects(self::once())
            ->method('getHref')
            ->willReturn($href2);
        $page2->expects(self::once())
            ->method('getTarget')
            ->willReturn($target);
        $page2->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);

        $page3 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page3->expects(self::once())
            ->method('getId')
            ->willReturn($id);
        $page3->expects(self::once())
            ->method('getTitle')
            ->willReturn($title);
        $page3->expects(self::once())
            ->method('getClass')
            ->willReturn($class);
        $page3->expects(self::once())
            ->method('getHref')
            ->willReturn($href3);
        $page3->expects(self::once())
            ->method('getTarget')
            ->willReturn($target);
        $page3->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);

        $expectedLinks = [
            ['id' => $id, 'title' => $title, 'class' => $class, 'href' => $href1, 'target' => $target, 'label' => $label],
            ['id' => $id, 'title' => $title, 'class' => $class, 'href' => $href2, 'target' => $target, 'label' => $label],
            ['id' => $id, 'title' => $title, 'class' => $class, 'href' => $href3, 'target' => $target, 'label' => $label],
        ];
        $links         = new Links();

        $links->setLinks([$page1, $page2, $page3]);

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

        self::assertSame($links, $links->setValue($expectedSeperator));
        self::assertNotSame($expectedSeperator, $links->getValue());
    }
}
