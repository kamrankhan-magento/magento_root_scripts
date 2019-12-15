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

$file = 'dup_sku.csv';

if (file_exists($file)) {

    $csvData = $fileCsv->getData($file);

    $accounts_no_array = array();
    $account_sku_price_array = array();
    $product_skus = array();
    $counter = 1;
    foreach ($csvData as $row => $data) {
        if ($counter != 1) {
            $skus[] = $data[0];
            $skus[] = $data[1];
            $skus_mapping[$data[0]] = $data[1];
        }

        $counter++;
    }

    $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
        ->addAttributeToFilter('sku', array("in" => $skus));


    foreach ($productCollection as $product)
    {
        $ids_skus_mapping[$product->getSku()] = $product->getId();
    }



    foreach ($skus_mapping as $wrong_sku => $correct_sku)
    {

        if(array_key_exists($wrong_sku, $ids_skus_mapping) && array_key_exists($correct_sku, $ids_skus_mapping))
        {
            $update_sku_mapping[$ids_skus_mapping[$wrong_sku]] = $ids_skus_mapping[$correct_sku];

        }
    }

    $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
    $connection = $resource->getConnection();


    $updateData = $update_sku_mapping;

    $conditions = [];
    foreach ($updateData as $old_id => $new_id) {
        $case = $connection->quoteInto('?', $old_id);
        $result = $connection->quoteInto('?', $new_id);
        $conditions[$case] = $result;
    }
    $value = $connection->getCaseSql('price_product_id', $conditions, 'price_product_id');
    $where = ['price_product_id IN (?)' => array_keys($updateData)];
    try {
        $connection->beginTransaction();
        $connection->update($resource->getTableName('ae_price'), ['price_product_id' => $value], $where);
        $connection->commit();
    } catch(\Exception $e) {
        $connection->rollBack();
    }


}