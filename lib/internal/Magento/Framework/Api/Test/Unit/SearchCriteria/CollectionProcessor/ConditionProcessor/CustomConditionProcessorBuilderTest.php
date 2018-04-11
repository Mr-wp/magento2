<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Api\Test\Unit\SearchCriteria\CollectionProcessor\ConditionPro;

use Magento\Framework\Api\SearchCriteria\CollectionProcessor\ConditionProcessor\CustomConditionProcessorBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\ConditionProcessor\CustomConditionInterface;

class CustomConditionProcessorBuilderTest extends \PHPUnit\Framework\TestCase
{
    private $customConditionProcessorBuilder;
    private $customConditionMock;

    protected function setUp()
    {
        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->customConditionMock = $this->getMockBuilder(CustomConditionInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->customConditionProcessorBuilder = $objectManagerHelper
            ->getObject(
                CustomConditionProcessorBuilder::class,
                [
                    'customConditionProcessor' => [
                        'my-valid-field' => $this->customConditionMock,
                        'my-invalid-field' => 'olo-lo'
                    ]
                ]
            );
    }

    public function testPositiveHasProcessorForField()
    {
        $testField = 'my-valid-field';

        $this->assertTrue(
            $this->customConditionProcessorBuilder->hasProcessorForField($testField)
        );
    }

    public function testNegativeHasProcessorForField()
    {
        $testField = 'unknown-field';

        $this->assertFalse(
            $this->customConditionProcessorBuilder->hasProcessorForField($testField)
        );
    }

    public function testPositiveGetProcessorByField()
    {
        $testField = 'my-valid-field';

        $this->assertEquals(
            $this->customConditionMock,
            $this->customConditionProcessorBuilder->getProcessorByField($testField)
        );
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     * @expectedExceptionMessage Custom processor for field "unknown-field" is absent.
     */
    public function testNegativeGetProcessorByFieldExceptionFieldIsAbsent()
    {
        $testField = 'unknown-field';
        $this->customConditionProcessorBuilder->getProcessorByField($testField);
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     * @expectedExceptionMessage Custom processor must implement CustomConditionInterface.
     */
    public function testNegativeGetProcessorByFieldExceptionWrongClass()
    {
        $testField = 'my-invalid-field';
        $this->customConditionProcessorBuilder->getProcessorByField($testField);
    }
}
