<?php

/**
 * TechDivision\Import\Product\Observers\UrlRewriteUpdateObserverTest
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Observers;

use TechDivision\Import\Utils\EntityStatus;
use TechDivision\Import\Product\Utils\ColumnKeys;
use TechDivision\Import\Product\Utils\MemberNames;

/**
 * Test class for the product URL rewrite update observer implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product
 * @link      http://www.techdivision.com
 */
class UrlRewriteUpdateObserverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The observer we want to test.
     *
     * @var \TechDivision\Import\Product\Observers\UrlRewriteUpdateObserver
     */
    protected $observer;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->observer = new UrlRewriteUpdateObserver();
    }

    /**
     * Test's the handle() method with a successfull URL rewrite persist when using different categories.
     *
     * @return void
     */
    public function testHandleWithSuccessfullUpdateAndDifferentCategories()
    {

        // initialize the entity ID to use
        $entityId = 61413;

        // create a dummy CSV file row
        $headers = array(
            'sku'             => 0,
            'url_key'         => 1,
            'categories'      => 2,
            'store_view_code' => 3
        );

        // create a dummy CSV file header
        $row = array(
            0 => 'TEST-01',
            1 => 'bruno-compete-hoodie',
            2 => 'Default Category/Men/Bottoms/Pants,Default Category/Collections/Erin Recommends,Default Category',
            3 => null
        );

        // the found URL rewrites
        $urlRewrites = array(
            array(
                MemberNames::URL_REWRITE_ID   => 744,
                MemberNames::ENTITY_TYPE      => 'product',
                MemberNames::ENTITY_ID        => $entityId,
                MemberNames::REQUEST_PATH     => sprintf('%s-old.html', $row[$headers[ColumnKeys::URL_KEY]]),
                MemberNames::TARGET_PATH      => sprintf('catalog/product/view/id/%s', $entityId),
                MemberNames::REDIRECT_TYPE    => 0,
                MemberNames::STORE_ID         => 1,
                MemberNames::DESCRIPTION      => null,
                MemberNames::IS_AUTOGENERATED => 1,
                MemberNames::METADATA         => null
            ),
            array(
                MemberNames::URL_REWRITE_ID   => 745,
                MemberNames::ENTITY_TYPE      => 'product',
                MemberNames::ENTITY_ID        => $entityId,
                MemberNames::REQUEST_PATH     => sprintf('men/tops-men/hoodies-and-sweatshirts-men/%s-old.html', $row[$headers[ColumnKeys::URL_KEY]]),
                MemberNames::TARGET_PATH      => sprintf('catalog/product/view/id/%s/category/16', $entityId),
                MemberNames::REDIRECT_TYPE    => 0,
                MemberNames::STORE_ID         => 1,
                MemberNames::DESCRIPTION      => null,
                MemberNames::IS_AUTOGENERATED => 1,
                MemberNames::METADATA         => serialize(array('category_id' => 16))
            ),
            array(
                MemberNames::URL_REWRITE_ID   => 746,
                MemberNames::ENTITY_TYPE      => 'product',
                MemberNames::ENTITY_ID        => $entityId,
                MemberNames::REQUEST_PATH     => sprintf('collections/eco-friendly/%s-old.html', $row[$headers[ColumnKeys::URL_KEY]]),
                MemberNames::TARGET_PATH      => sprintf('catalog/product/view/id/%s/category/37', $entityId),
                MemberNames::REDIRECT_TYPE    => 0,
                MemberNames::STORE_ID         => 1,
                MemberNames::DESCRIPTION      => null,
                MemberNames::IS_AUTOGENERATED => 1,
                MemberNames::METADATA         => serialize(array('category_id' => 37))
            ),
            array(
                MemberNames::URL_REWRITE_ID   => 747,
                MemberNames::ENTITY_TYPE      => 'product',
                MemberNames::ENTITY_ID        => $entityId,
                MemberNames::REQUEST_PATH     => sprintf('men/tops-men/%s-old.html', $row[$headers[ColumnKeys::URL_KEY]]),
                MemberNames::TARGET_PATH      => sprintf('catalog/product/view/id/%s/category/13', $entityId),
                MemberNames::REDIRECT_TYPE    => 0,
                MemberNames::STORE_ID         => 1,
                MemberNames::DESCRIPTION      => null,
                MemberNames::IS_AUTOGENERATED => 1,
                MemberNames::METADATA         => serialize(array('category_id' => 13))
            )
        );

        // create a mock subject
        $mockSubject = $this->getMockBuilder('TechDivision\Import\Product\Subjects\BunchSubject')
                            ->setMethods(
                                    array(
                                        'hasHeader',
                                        'getHeader',
                                        'getHeaders',
                                        'getLastSku',
                                        'getLastEntityId',
                                        'getProductCategoryIds',
                                        'getRootCategory',
                                        'getRowStoreId',
                                        'getCategory',
                                        'persistUrlRewrite',
                                        'getUrlRewritesByEntityTypeAndEntityId'
                                    )
                                )
                                ->getMock();
        $mockSubject->expects($this->any())
                    ->method('getHeaders')
                    ->willReturn($headers);
        $mockSubject->expects($this->any())
                    ->method('hasHeader')
                    ->willReturn(true);
        $mockSubject->expects($this->any())
                    ->method('getHeader')
                    ->withConsecutive(
                        array(ColumnKeys::SKU),
                        array(ColumnKeys::URL_KEY),
                        array(ColumnKeys::STORE_VIEW_CODE)
                    )
                    ->willReturnOnConsecutiveCalls(0, 1, 2);
        $mockSubject->expects($this->any())
                    ->method('getLastEntityId')
                    ->willReturn($entityId);
        $mockSubject->expects($this->exactly(4))
                    ->method('getCategory')
                    ->withConsecutive(array(19), array(35), array(2), array(2), array(16), array(37), array(13))
                    ->willReturnOnConsecutiveCalls(
                        array(MemberNames::ENTITY_ID => 19, MemberNames::URL_PATH => 'men/bottoms-men/pants-men'),
                        array(MemberNames::ENTITY_ID => 35, MemberNames::URL_PATH => 'collections/erin-recommends'),
                        array(MemberNames::ENTITY_ID =>  2, MemberNames::URL_PATH => null),
                        array(MemberNames::ENTITY_ID =>  2, MemberNames::URL_PATH => null)
                    );
        $mockSubject->expects($this->once())
                    ->method('getLastSku')
                    ->willReturn('TEST-02');
        $mockSubject->expects($this->any())
                    ->method('getRootCategory')
                    ->willReturn(array(MemberNames::ENTITY_ID =>  2, MemberNames::URL_PATH => null));
        $mockSubject->expects($this->once())
                    ->method('getProductCategoryIds')
                    ->willReturn(array(19 => $entityId, 35 => $entityId));
        $mockSubject->expects($this->any())
                    ->method('getRowStoreId')
                    ->willReturn($storeId = 1);
        $mockSubject->expects($this->once())
                    ->method('getUrlRewritesByEntityTypeAndEntityId')
                    ->with(UrlRewriteObserver::ENTITY_TYPE, $entityId)
                    ->willReturn($urlRewrites);
        $mockSubject->expects($this->exactly(7))
                    ->method('persistUrlRewrite')
                    ->withConsecutive(
                        array(
                            array(
                                EntityStatus::MEMBER_NAME     => EntityStatus::STATUS_CREATE,
                                MemberNames::ENTITY_TYPE      => UrlRewriteObserver::ENTITY_TYPE,
                                MemberNames::ENTITY_ID        => $entityId,
                                MemberNames::REQUEST_PATH     => sprintf('men/bottoms-men/pants-men/%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::TARGET_PATH      => sprintf('catalog/product/view/id/%d/category/%d', $entityId, 19),
                                MemberNames::REDIRECT_TYPE    => 0,
                                MemberNames::STORE_ID         => $storeId,
                                MemberNames::DESCRIPTION      => null,
                                MemberNames::IS_AUTOGENERATED => 1,
                                MemberNames::METADATA         => serialize(array('category_id' => 19))
                            )
                        ),
                        array(
                            array(
                                EntityStatus::MEMBER_NAME     => EntityStatus::STATUS_CREATE,
                                MemberNames::ENTITY_TYPE      => UrlRewriteObserver::ENTITY_TYPE,
                                MemberNames::ENTITY_ID        => $entityId,
                                MemberNames::REQUEST_PATH     => sprintf('collections/erin-recommends/%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::TARGET_PATH      => sprintf('catalog/product/view/id/%d/category/%d', $entityId, 35),
                                MemberNames::REDIRECT_TYPE    => 0,
                                MemberNames::STORE_ID         => $storeId,
                                MemberNames::DESCRIPTION      => null,
                                MemberNames::IS_AUTOGENERATED => 1,
                                MemberNames::METADATA         => serialize(array('category_id' => 35))
                            )
                        ),
                        array(
                            array(
                                EntityStatus::MEMBER_NAME     => EntityStatus::STATUS_CREATE,
                                MemberNames::ENTITY_TYPE      => UrlRewriteObserver::ENTITY_TYPE,
                                MemberNames::ENTITY_ID        => $entityId,
                                MemberNames::REQUEST_PATH     => sprintf('%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::TARGET_PATH      => sprintf('catalog/product/view/id/%s', $entityId),
                                MemberNames::REDIRECT_TYPE    => 0,
                                MemberNames::STORE_ID         => $storeId,
                                MemberNames::DESCRIPTION      => null,
                                MemberNames::IS_AUTOGENERATED => 1,
                                MemberNames::METADATA         => serialize(array())
                            )
                        ),
                        array(
                            array(
                                EntityStatus::MEMBER_NAME     => EntityStatus::STATUS_UPDATE,
                                MemberNames::ENTITY_TYPE      => UrlRewriteObserver::ENTITY_TYPE,
                                MemberNames::URL_REWRITE_ID   => 744,
                                MemberNames::ENTITY_ID        => $entityId,
                                MemberNames::REQUEST_PATH     => sprintf('%s-old.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::TARGET_PATH      => sprintf('%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::REDIRECT_TYPE    => 301,
                                MemberNames::STORE_ID         => $storeId,
                                MemberNames::DESCRIPTION      => null,
                                MemberNames::IS_AUTOGENERATED => 1,
                                MemberNames::METADATA         => serialize(array())
                            )
                        ),
                        array(
                            array(
                                EntityStatus::MEMBER_NAME     => EntityStatus::STATUS_UPDATE,
                                MemberNames::ENTITY_TYPE      => UrlRewriteObserver::ENTITY_TYPE,
                                MemberNames::URL_REWRITE_ID   => 745,
                                MemberNames::ENTITY_ID        => $entityId,
                                MemberNames::REQUEST_PATH     => sprintf('men/tops-men/hoodies-and-sweatshirts-men/%s-old.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::TARGET_PATH      => sprintf('%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::REDIRECT_TYPE    => 301,
                                MemberNames::STORE_ID         => $storeId,
                                MemberNames::DESCRIPTION      => null,
                                MemberNames::IS_AUTOGENERATED => 1,
                                MemberNames::METADATA         => serialize(array())
                            )
                        ),
                        array(
                            array(
                                EntityStatus::MEMBER_NAME     => EntityStatus::STATUS_UPDATE,
                                MemberNames::ENTITY_TYPE      => UrlRewriteObserver::ENTITY_TYPE,
                                MemberNames::URL_REWRITE_ID   => 746,
                                MemberNames::ENTITY_ID        => $entityId,
                                MemberNames::REQUEST_PATH     => sprintf('collections/eco-friendly/%s-old.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::TARGET_PATH      => sprintf('%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::REDIRECT_TYPE    => 301,
                                MemberNames::STORE_ID         => $storeId,
                                MemberNames::DESCRIPTION      => null,
                                MemberNames::IS_AUTOGENERATED => 1,
                                MemberNames::METADATA         => serialize(array())
                            )
                        ),
                        array(
                            array(
                                EntityStatus::MEMBER_NAME     => EntityStatus::STATUS_UPDATE,
                                MemberNames::ENTITY_TYPE      => UrlRewriteObserver::ENTITY_TYPE,
                                MemberNames::URL_REWRITE_ID   => 747,
                                MemberNames::ENTITY_ID        => $entityId,
                                MemberNames::REQUEST_PATH     => sprintf('men/tops-men/%s-old.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::TARGET_PATH      => sprintf('%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                                MemberNames::REDIRECT_TYPE    => 301,
                                MemberNames::STORE_ID         => $storeId,
                                MemberNames::DESCRIPTION      => null,
                                MemberNames::IS_AUTOGENERATED => 1,
                                MemberNames::METADATA         => serialize(array())
                            )
                        )
                    );

        // inject the subject und invoke the handle() method
        $this->observer->setSubject($mockSubject);
        $this->assertSame($row, $this->observer->handle($row));
    }
}
