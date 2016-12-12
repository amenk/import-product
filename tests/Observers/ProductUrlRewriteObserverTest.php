<?php

/**
 * TechDivision\Import\Product\Observers\ProductUrlRewriteObserverTest
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

use TechDivision\Import\Product\Utils\ColumnKeys;

/**
 * Test class for the product URL rewrite observer implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product
 * @link      http://www.techdivision.com
 */
class ProductUrlRewriteObserverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The observer we want to test.
     *
     * @var \TechDivision\Import\Product\Observers\ProductUrlRewriteObserver
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
        $this->observer = new ProductUrlRewriteObserver();
    }

    /**
     * Test's the getUrlRewriteByUrlKey() method successfull.
     *
     * @return void
     */
    public function testGetUrlRewriteByUrlKeySuccessfull()
    {

        // create a mock subject
        $mockSubject = $this->getMockBuilder('TechDivision\Import\Product\Subjects\BunchSubject')
                            ->setMethods(array('getUrlRewritesByEntityTypeAndEntityId'))
                            ->getMock();
        $mockSubject->expects($this->once())
                    ->method('getUrlRewritesByEntityTypeAndEntityId')
                    ->with($entityType = 'product', $entityId = 61413)
                    ->willReturn(
                        $urlRewrite = array(
                            'url_rewrite_id'   => 744,
                            'entity_type'      => 'product',
                            'entity_id'        => 61413,
                            'request_path'     => 'bruno-compete-hoodie-test.html',
                            'target_path'      => 'catalog/product/view/id/61413',
                            'redirect_type'    => 0,
                            'store_id'         => 1,
                            'description'      => 'A custom rewrite',
                            'is_autogenerated' => 1,
                            'metadata'         => null
                        )
                    );

        // inject the subject
        $this->observer->setSubject($mockSubject);

        // make sure, the URL rewites will be loaded
        $this->assertSame($urlRewrite, $this->observer->getUrlRewritesByEntityTypeAndEntityId($entityType, $entityId));
    }

    /**
     * Test's the persistUrlRewrite() method successfull.
     *
     * @return void
     */
    public function testPersistUrlRewriteSuccessfull()
    {

        // create a mock subject
        $mockSubject = $this->getMockBuilder('TechDivision\Import\Product\Subjects\BunchSubject')
                            ->setMethods(array('persistUrlRewrite'))
                            ->getMock();
        $mockSubject->expects($this->once())
                    ->method('persistUrlRewrite')
                    ->with(
                        $urlRewrite = array(
                            'entity_type'      => 'product',
                            'entity_id'        => 61413,
                            'request_path'     => 'bruno-compete-hoodie-test.html',
                            'target_path'      => 'catalog/product/view/id/61413',
                            'redirect_type'    => 0,
                            'store_id'         => 1,
                            'description'      => 'A custom rewrite',
                            'is_autogenerated' => 1,
                            'metadata'         => null
                        )
                    );

        // inject the subject
        $this->observer->setSubject($mockSubject);

        // make sure, the URL rewite will be persisted
        $this->assertNull($this->observer->persistUrlRewrite($urlRewrite));
    }

    /**
     * Test's the updateUrlRewrite() method successfull.
     *
     * @return void
     */
    public function testUpdateUrlRewriteSuccessfull()
    {

        // create a mock subject
        $mockSubject = $this->getMockBuilder('TechDivision\Import\Product\Subjects\BunchSubject')
                            ->setMethods(array('updateUrlRewrite'))
                            ->getMock();
        $mockSubject->expects($this->once())
                    ->method('updateUrlRewrite')
                    ->with(
                        $urlRewrite = array(
                            'url_rewrite_id'   => 744,
                            'entity_type'      => 'product',
                            'entity_id'        => 61413,
                            'request_path'     => 'bruno-compete-hoodie-test.html',
                            'target_path'      => 'catalog/product/view/id/61413',
                            'redirect_type'    => 0,
                            'store_id'         => 1,
                            'description'      => 'A custom rewrite',
                            'is_autogenerated' => 1,
                            'metadata'         => null
                        )
                    );

        // inject the subject
        $this->observer->setSubject($mockSubject);

        // make sure, the URL rewite will be updated
        $this->assertNull($this->observer->updateUrlRewrite($urlRewrite));
    }

    /**
     * Test's the handle() method with a successfull URL rewrite persist.
     *
     * @return void
     */
    public function testHandleWithPersistSuccessfull()
    {

        // create a dummy CSV file header/row
        $headers = array('sku' => 0, 'url_key' => 1);
        $row = array(0 => 'TEST-01', 1 => 'bruno-compete-hoodie-test.html');

        // create a mock subject
        $mockSubject = $this->getMockBuilder('TechDivision\Import\Product\Subjects\BunchSubject')
                            ->setMethods(
                                array(
                                    'getHeaders',
                                    'isLastSku',
                                    'getLastEntityId',
                                    'getUrlRewritesByEntityTypeAndEntityId',
                                    'persistUrlRewrite'
                                )
                            )
                            ->getMock();
        $mockSubject->expects($this->once())
                    ->method('getHeaders')
                    ->willReturn($headers);
        $mockSubject->expects($this->once())
                    ->method('getLastEntityId')
                    ->willReturn($entityId = 61413);
        $mockSubject->expects($this->once())
                    ->method('isLastSku')
                    ->with($row[$headers[ColumnKeys::SKU]])
                    ->willReturn(false);
        $mockSubject->expects($this->once())
                    ->method('getUrlRewritesByEntityTypeAndEntityId')
                    ->with($entityType = 'product', $entityId)
                    ->willReturn(array());
        $mockSubject->expects($this->once())
                    ->method('persistUrlRewrite')
                    ->with(
                        $urlRewrite = array(
                            'url_rewrite_id'   => $entityId,
                            'entity_type'      => $entityType,
                            'entity_id'        => 61413,
                            'request_path'     => $row[$headers[ColumnKeys::URL_KEY]],
                            'target_path'      => sprintf('catalog/product/view/id/%s', $entityId),
                            'redirect_type'    => 0,
                            'store_id'         => 1,
                            'description'      => 'A custom rewrite',
                            'is_autogenerated' => 1,
                            'metadata'         => null
                        )
                    );

        // inject the subject und invoke the handle() method
        $this->observer->setSubject($mockSubject);
        $this->assertSame($row, $this->observer->handle($row));
    }

    /**
     * Test's the handle() method with a successfull URL rewrite update when using the same categories.
     *
     * @return void
     */
    public function testHandleWithSameCategoriesAndUpdateSuccessfull()
    {

        // initialize the entity ID to use
        $entityId = 61413;

        // create a dummy CSV file row
        $headers = array(
            'sku'        => 0,
            'url_key'    => 1,
            'categories' => 2
        );

        // create a dummy CSV file header
        $row = array(
            0 => 'TEST-01',
            1 => 'bruno-compete-hoodie',
            2 => 'Default Category/Men/Tops/Hoodies & Sweatshirts,Default Category/Collections/Eco Friendly,Default Category'
        );

        // the found URL rewrites
        $urlRewrites = array(
            array(
                'url_rewrite_id'   => 744,
                'entity_type'      => 'product',
                'entity_id'        => $entityId,
                'request_path'     => 'bruno-compete-hoodie-old.html',
                'target_path'      => sprintf('catalog/product/view/id/%s', $entityId),
                'redirect_type'    => 0,
                'store_id'         => 1,
                'description'      => null,
                'is_autogenerated' => 0,
                'metadata'         => null
            ),
            array(
                'url_rewrite_id'   => 745,
                'entity_type'      => 'product',
                'entity_id'        => $entityId,
                'request_path'     => 'men/tops-men/hoodies-and-sweatshirts-men/bruno-compete-hoodie-old.html',
                'target_path'      => sprintf('catalog/product/view/id/%s/category/16', $entityId),
                'redirect_type'    => 0,
                'store_id'         => 1,
                'description'      => null,
                'is_autogenerated' => 0,
                'metadata'         => 'a:1:{s:11:"category_id";s:2:"16";}'
            ),
            array(
                'url_rewrite_id'   => 746,
                'entity_type'      => 'product',
                'entity_id'        => $entityId,
                'request_path'     => 'collections/eco-friendly/bruno-compete-hoodie-old.html',
                'target_path'      => sprintf('catalog/product/view/id/%s/category/37', $entityId),
                'redirect_type'    => 0,
                'store_id'         => 1,
                'description'      => null,
                'is_autogenerated' => 0,
                'metadata'         => 'a:1:{s:11:"category_id";s:2:"37";}'
            ),
            array(
                'url_rewrite_id'   => 747,
                'entity_type'      => 'product',
                'entity_id'        => $entityId,
                'request_path'     => 'men/tops-men/bruno-compete-hoodie-old.html',
                'target_path'      => sprintf('catalog/product/view/id/%s/category/13', $entityId),
                'redirect_type'    => 0,
                'store_id'         => 1,
                'description'      => null,
                'is_autogenerated' => 0,
                'metadata'         => 'a:1:{s:11:"category_id";s:2:"13";}'
            )
        );

        // create a mock subject
        $mockSubject = $this->getMockBuilder('TechDivision\Import\Product\Subjects\BunchSubject')
                            ->setMethods(array('getHeaders', 'isLastSku', 'getLastEntityId', 'getUrlRewriteByUrlKey', 'updateUrlRewrite', 'persistUrlRewrite'))
                            ->getMock();
        $mockSubject->expects($this->once())
                    ->method('getHeaders')
                    ->willReturn($headers);
        $mockSubject->expects($this->once())
                    ->method('getLastEntityId')
                    ->willReturn($entityId);
        $mockSubject->expects($this->once())
                    ->method('isLastSku')
                    ->with($row[$headers[ColumnKeys::SKU]])
                    ->willReturn(false);
        $mockSubject->expects($this->once())
                    ->method('getUrlRewritesByEntityTypeAndEntityId')
                    ->with($entityType = 'product', $entityId)
                    ->willReturn($urlRewrites);
        $mockSubject->expects($this->exactly(4))
                    ->method('updateUrlRewrite')
                    ->withConsecutive(
                        array(
                            'url_rewrite_id'   => 744,
                            'entity_type'      => 'product',
                            'entity_id'        => $entityId,
                            'request_path'     => 'bruno-compete-hoodie-old.html',
                            'target_path'      => sprintf('%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                            'redirect_type'    => 301,
                            'store_id'         => 1,
                            'description'      => null,
                            'is_autogenerated' => 0,
                            'metadata'         => null
                        ),
                        array(
                            'url_rewrite_id'   => 745,
                            'entity_type'      => 'product',
                            'entity_id'        => $entityId,
                            'request_path'     => 'men/tops-men/hoodies-and-sweatshirts-men/bruno-compete-hoodie-old.html',
                            'target_path'      => sprintf('men/tops-men/hoodies-and-sweatshirts-men/%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                            'redirect_type'    => 301,
                            'store_id'         => 1,
                            'description'      => null,
                            'is_autogenerated' => 0,
                            'metadata'         => 'a:1:{s:11:"category_id";s:2:"16";}'
                        ),
                        array(
                            'url_rewrite_id'   => 746,
                            'entity_type'      => 'product',
                            'entity_id'        => $entityId,
                            'request_path'     => 'collections/eco-friendly/bruno-compete-hoodie-old.html',
                            'target_path'      => sprintf('collections/eco-friendly/%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                            'redirect_type'    => 301,
                            'store_id'         => 1,
                            'description'      => null,
                            'is_autogenerated' => 0,
                            'metadata'         => 'a:1:{s:11:"category_id";s:2:"37";}'
                        ),
                        array(
                            'url_rewrite_id'   => 747,
                            'entity_type'      => 'product',
                            'entity_id'        => $entityId,
                            'request_path'     => 'bruno-compete-hoodie-old.html',
                            'target_path'      => sprintf('men/tops-men/%s.html', $row[$headers[ColumnKeys::URL_KEY]]),
                            'redirect_type'    => 301,
                            'store_id'         => 1,
                            'description'      => null,
                            'is_autogenerated' => 0,
                            'metadata'         => 'a:1:{s:11:"category_id";s:2:"13";}'
                        )
                    );
        $mockSubject->expects($this->exactly(4))
                    ->method('persistUrlRewrite')
                    ->withConsecutive(
                        array(
                            'url_rewrite_id'   => 748,
                            'entity_type'      => 'product',
                            'entity_id'        => $entityId,
                            'request_path'     => 'bruno-compete-hoodie.html',
                            'target_path'      => sprintf('catalog/product/view/id/%s', $entityId),
                            'redirect_type'    => 0,
                            'store_id'         => 1,
                            'description'      => null,
                            'is_autogenerated' => 1,
                            'metadata'         => null
                        ),
                        array(
                            'url_rewrite_id'   => 749,
                            'entity_type'      => 'product',
                            'entity_id'        => $entityId,
                            'request_path'     => 'men/tops-men/hoodies-and-sweatshirts-men/bruno-compete-hoodie.html',
                            'target_path'      => sprintf('catalog/product/view/id/%s/category/16', $entityId),
                            'redirect_type'    => 0,
                            'store_id'         => 1,
                            'description'      => null,
                            'is_autogenerated' => 1,
                            'metadata'         => 'a:1:{s:11:"category_id";s:2:"16";}'
                        ),
                        array(
                            'url_rewrite_id'   => 750,
                            'entity_type'      => 'product',
                            'entity_id'        => $entityId,
                            'request_path'     => 'collections/eco-friendly/bruno-compete-hoodie.html',
                            'target_path'      => sprintf('catalog/product/view/id/%s/category/37', $entityId),
                            'redirect_type'    => 0,
                            'store_id'         => 1,
                            'description'      => null,
                            'is_autogenerated' => 1,
                            'metadata'         => 'a:1:{s:11:"category_id";s:2:"37";}'
                        ),
                        array(
                            'url_rewrite_id'   => 751,
                            'entity_type'      => 'product',
                            'entity_id'        => $entityId,
                            'request_path'     => 'men/tops-men/bruno-compete-hoodie.html',
                            'target_path'      => sprintf('catalog/product/view/id/%s/category/13', $entityId),
                            'redirect_type'    => 0,
                            'store_id'         => 1,
                            'description'      => null,
                            'is_autogenerated' => 1,
                            'metadata'         => 'a:1:{s:11:"category_id";s:2:"13";}'
                        )
                    );

        // inject the subject und invoke the handle() method
        $this->observer->setSubject($mockSubject);
        $this->assertSame($row, $this->observer->handle($row));
    }
}
