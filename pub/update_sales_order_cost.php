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
$files = array('cost-import.csv','cost-import-tons-v2.csv','cost-import-v3.csv');

$startDate = date("Y-m-d h:i:s",strtotime('2018-12-1')); // start date
$endDate = date("Y-m-d h:i:s", strtotime('2019-01-24')); // end date
foreach ($files as $file)
{
    if (file_exists($file)) {

        $csvData = $fileCsv->getData($file);

        $accounts_no_array = array();
        $account_sku_price_array = array();
        $product_skus = array();
        $counter = 1;
        foreach ($csvData as $row => $data) {
            if($counter!=1)
            {
                $product_skus_cost_array[$data[0]] = $data[1];
                $full_skus[] = $data[0];
            }

            $counter++;
        }

    }
}
$order_item_collection = $objectManager->get("Magento\Sales\Model\ResourceModel\Order\Item\Collection");
$order_item_collection->addFieldToFilter('created_at', array('from' => $startDate));
$order_item_collection->addFieldToFilter('sku', array('in' => $full_skus));


foreach ($order_item_collection as $order_item)
{
    if (array_key_exists($order_item->getSku(), $product_skus_cost_array))
    {
        $order_base_cost_array[$order_item->getOrderId()][$order_item->getSku()] = $order_item->getQtyOrdered() * $product_skus_cost_array[$order_item->getSku()];

    }
}



foreach ($order_base_cost_array as $order_id => $order_cost)
{
    $total_cost = 0;
    foreach ($order_cost as $cost)
    {
        $total_cost += $cost;
    }
    $order_total_cost_array[$order_id] = $total_cost;
}


$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();


$updateData = $order_total_cost_array;

$conditions = [];
foreach ($updateData as $order_id => $cost_price) {
    $case = $connection->quoteInto('?', $order_id);
    $result = $connection->quoteInto('?', $cost_price);
    $conditions[$case] = $result;
}
$value = $connection->getCaseSql('entity_id', $conditions, 'base_total_invoiced_cost');
$where = ['entity_id IN (?)' => array_keys($updateData)];
try {
    $connection->beginTransaction();
    $connection->update($resource->getTableName('sales_order'), ['base_total_invoiced_cost' => $value], $where);
    $connection->commit();
} catch(\Exception $e) {
    $connection->rollBack();
}