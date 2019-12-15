<?php

use Magento\Framework\App\Bootstrap;


require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$fileCsv = $objectManager->get('Magento\Framework\File\Csv');
$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
$rootPath  =  $directory->getRoot();
$file = 'Capitol_Product_Upload.csv';

if (file_exists($file)) {

    $csvData = $fileCsv->getData($file);

    $accounts_no_array = array();
    $account_sku_price_array = array();
    $product_skus = array();
    $counter = 1;
    foreach ($csvData as $row => $data) {
        ///echo "<pre>";
        //print_r($data);
        if($counter!=1)
        {
            $product_skus[] = $data[0];
            $ts_dimensions_height_array[$data[0]] = $data[1];
            $ts_dimensions_width_array[$data[0]] = $data[2];
            $qty_per_sqm_array[$data[0]] = $data[3];

        }

        $counter++;
    }


    $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
        ->addAttributeToFilter('sku', array("in" => $product_skus));
    $updatemgr = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Action');
    $stores_array = array(5,6,3,4);
    foreach ($productCollection as $product)
    {
        $product_id = array($product->getData("entity_id"));
        $update_attributes_array = array("ts_dimensions_height" => $ts_dimensions_height_array[$product->getData("sku")],
            "ts_dimensions_width" => $ts_dimensions_width_array[$product->getData("sku")],
            "qty_per_sqm" =>$qty_per_sqm_array[$product->getData("sku")]);
        foreach ($stores_array as $store)
        {
            $updatemgr->updateAttributes($product_id, $update_attributes_array, $store);
        }
    }



}