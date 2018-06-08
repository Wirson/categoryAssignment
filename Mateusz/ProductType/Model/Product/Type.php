<?php

namespace Mateusz\ProductType\Model\Product;

/**
 * Class Type
 * @package Mateusz\ProductType\Model\Product
 */
class Type extends \Magento\Catalog\Model\Product\Type\AbstractType
{
    const TYPE_CODE = 'outstanding';

    /**
     * Delete data specific for Outstanding product type
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return void
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {

    }

}
