<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\Api\SearchCriteria\CollectionProcessor\ConditionProcessor;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\ConditionProcessor\CustomConditionInterface;

/**
 * Class CustomConditionProcessorBuilder
 * Collection of all custom condition processors
 *
 * @package Magento\Framework\Api\SearchCriteria\CollectionProcessor\ConditionProcessor
 */
class CustomConditionProcessorBuilder implements CustomConditionProcessorBuilderInterface
{
    /**
     * @var CustomConditionInterface[]
     */
    private $customConditionProcessor;

    /**
     * CustomConditionProcessorBuilder constructor.
     * @param array $customConditionProcessor
     */
    public function __construct(array $customConditionProcessor = [])
    {
        $this->customConditionProcessor = $customConditionProcessor;
    }

    /**
     * Get custom processor by field name
     *
     * @param $fieldName
     * @return \Magento\Framework\Api\SearchCriteria\CollectionProcessor\ConditionProcessor\CustomConditionInterface
     * @throws InputException
     */
    public function getProcessorByField($fieldName)
    {
        if (!$this->hasProcessorForField($fieldName)) {
            throw new InputException(
                __(sprintf('Custom processor for field "%s" is absent.', $fieldName))
            );
        }

        $processor = $this->customConditionProcessor[$fieldName];

        if (!$processor instanceof CustomConditionInterface) {
            throw new InputException(
                __('Custom processor must implement CustomConditionInterface.')
            );
        }

        return $processor;
    }

    /**
     * Check if collection has custom processor for given field name
     *
     * @param $fieldName
     * @return bool
     */
    public function hasProcessorForField($fieldName)
    {
        return array_key_exists($fieldName, $this->customConditionProcessor);
    }
}
