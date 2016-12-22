<?php

/**
 * TechDivision\Import\Product\Subjects\BunchSubjectTest
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

namespace TechDivision\Import\Product\Subjects;

/**
 * Test class for the product action implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product
 * @link      http://www.techdivision.com
 */
class BunchSubjectTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The subject we want to test.
     *
     * @var \TechDivision\Import\Product\Subjects\BunchSubject
     */
    protected $subject;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->subject = new BunchSubject();
    }

    /**
     * Test's the getUrlRewritesByEntityTypeAndEntityId() method successfull.
     *
     * @return void
     */
    public function testGetUrlRewritesByEntityTypeAndEntityId()
    {

        // load a mock processor
        $mockProcessor = $this->getMockBuilder($processorInterface = 'TechDivision\Import\Product\Services\ProductBunchProcessorInterface')
                              ->setMethods(get_class_methods($processorInterface))
                              ->getMock();
        $mockProcessor->expects($this->once())
                      ->method('getUrlRewritesByEntityTypeAndEntityId')
                      ->with($entityType = 'product', $entityId = 61413)
                      ->willReturn(
                          $expected = array(
                              'url_rewrite_id'   => 744,
                              'entity_type'      => 'product',
                              'entity_id'        => $entityId,
                              'request_path'     => 'bruno-compete-hoodie-test.html',
                              'target_path'      => sprintf('catalog/product/view/id/%d', $entityId),
                              'redirect_type'    => 0,
                              'store_id'         => 1,
                              'description'      => 'A custom rewrite',
                              'is_autogenerated' => 1,
                              'metadata'         => null
                          )
                      );

        // inject the processor
        $this->subject->setProductProcessor($mockProcessor);

        // make sure we get the expected array with URL rewrites
        $this->assertSame($expected, $this->subject->getUrlRewritesByEntityTypeAndEntityId($entityType, $entityId));
    }

    /**
     * Test's the persistUrlRewrite() method successfull.
     *
     * @return void
     */
    public function testPersistUrlRewriteSuccessufull()
    {

        // load a mock processor
        $mockProcessor = $this->getMockBuilder($processorInterface = 'TechDivision\Import\Product\Services\ProductBunchProcessorInterface')
                              ->setMethods(get_class_methods($processorInterface))
                              ->getMock();
        $mockProcessor->expects($this->once())
                      ->method('persistUrlRewrite')
                      ->with(
                          $urlRewrite = array(
                              'url_rewrite_id'   => 744,
                              'entity_type'      => 'product',
                              'entity_id'        => $entityId = 61413,
                              'request_path'     => 'bruno-compete-hoodie-test.html',
                              'target_path'      => sprintf('catalog/product/view/id/%d', $entityId),
                              'redirect_type'    => 0,
                              'store_id'         => 1,
                              'description'      => 'A custom rewrite',
                              'is_autogenerated' => 1,
                              'metadata'         => null
                          )
                      )
                      ->willReturn(null);

        // inject the processor
        $this->subject->setProductProcessor($mockProcessor);

        // make sure that the URL rewrite will be persisted
        $this->assertNull($this->subject->persistUrlRewrite($urlRewrite));
    }

    /**
     * Test's the removeUrlRewrite() method successfull.
     *
     * @return void
     */
    public function testRemoveUrlRewriteSuccessufull()
    {

        // load a mock processor
        $mockProcessor = $this->getMockBuilder($processorInterface = 'TechDivision\Import\Product\Services\ProductBunchProcessorInterface')
                              ->setMethods(get_class_methods($processorInterface))
                              ->getMock();
        $mockProcessor->expects($this->once())
                      ->method('removeUrlRewrite')
                      ->with($urlRewrite = array('url_rewrite_id' => 744))
                      ->willReturn(null);

        // inject the processor
        $this->subject->setProductProcessor($mockProcessor);

        // make sure that the URL rewrite will be removed
        $this->assertNull($this->subject->removeUrlRewrite($urlRewrite));
    }
}
