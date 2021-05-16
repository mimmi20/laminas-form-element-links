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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
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
     */
    public function testSetAbstractPage(): void
    {
        $href   = 'http://www.test.com';
        $id     = 'abc';
        $title  = 'test-title';
        $class  = 'test-class';
        $target = null;

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

        $expectedLinks = [['id' => $id, 'title' => $title, 'class' => $class, 'href' => $href, 'target' => $target]];
        $links         = new Links();

        $links->setLinks([$page]);

        self::assertSame($expectedLinks, $links->getLinks());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetPageInterface(): void
    {
        $href   = 'http://www.test.com';
        $id     = 'abc';
        $title  = 'test-title';
        $class  = 'test-class';
        $target = null;

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

        $expectedLinks = [['id' => $id, 'title' => $title, 'class' => $class, 'href' => $href, 'target' => $target]];
        $links         = new Links();

        $links->setLinks([$page]);

        self::assertSame($expectedLinks, $links->getLinks());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
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
     */
    public function testCanRetrieveDefaultSeparator(): void
    {
        $expectedSeperator = ' || ';
        $expectedLinks     = [['href' => 'http://www.test.com']];
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

//    /**
//     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
//     * @throws Exception
//     * @throws InvalidArgumentException
//     * @throws DomainException
//     */
//    public function testCannotAllowNewElementsIfAllowAddIsFalse(): void
//    {
//        self::markTestSkipped();
//        $collection = $this->form->get('colors');
//
//        assert(
//            $collection instanceof ElementGroup,
//            sprintf(
//                '$collection should be an Instance of %s, but was %s',
//                ElementGroup::class,
//                get_class($collection)
//            )
//        );
//
//        self::assertTrue($collection->allowAdd());
//        $collection->setAllowAdd(false);
//        self::assertFalse($collection->allowAdd());
//
//        // By default, $collection contains 2 elements
//        $data   = [];
//        $data[] = 'blue';
//        $data[] = 'green';
//
//        $collection->populateValues($data);
//        self::assertCount(2, $collection->getElements());
//
//        $this->expectException(DomainException::class);
//        $data[] = 'orange';
//        $collection->populateValues($data);
//    }
//
//    /**
//     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
//     * @throws Exception
//     * @throws InvalidArgumentException
//     * @throws DomainException
//     * @throws \Laminas\InputFilter\Exception\InvalidArgumentException
//     */
//    public function testGetErrorMessagesForInvalidCollectionElements(): void
//    {
//        self::markTestSkipped();
//        // Configure InputFilter
//        $inputFilter = $this->form->getInputFilter();
//        $inputFilter->add(
//            [
//                'name' => 'colors',
//                'type' => ArrayInput::class,
//                'required' => true,
//            ]
//        );
//        $inputFilter->add(
//            [
//                'name' => 'fieldsets',
//                'type' => ArrayInput::class,
//                'required' => true,
//            ]
//        );
//
//        $this->form->setData([]);
//        $this->form->isValid();
//
//        self::assertSame(
//            [
//                'colors' => ['isEmpty' => "Value is required and can't be empty"],
//                'fieldsets' => ['isEmpty' => "Value is required and can't be empty"],
//            ],
//            $this->form->getMessages()
//        );
//    }
}
