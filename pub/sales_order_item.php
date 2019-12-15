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
            }

            $counter++;
        }

    }
}
$startDate = date("Y-m-d h:i:s",strtotime('2018-12-1')); // start date
$endDate = date("Y-m-d h:i:s", strtotime('2019-01-24')); // end date


$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();


$updateData = $product_skus_cost_array;

$conditions = [];
foreach ($updateData as $sku => $cost_price) {
    $case = $connection->quoteInto('?', $sku);
    $result = $connection->quoteInto('?', $cost_price);
    $conditions[$case] = $result;
}
$value = $connection->getCaseSql('sku', $conditions, 'base_cost');
$where = ['sku IN (?)' => array_keys($updateData)];
try {
    $connection->beginTransaction();
    $connection->update($resource->getTableName('sales_order_item'), ['base_cost' => $value], $where);
    $connection->commit();
} catch(\Exception $e) {
    $connection->rollBack();
}

try {
    $connection->beginTransaction();
    $connection->update($resource->getTableName('sales_invoice_item'), ['base_cost' => $value], $where);
    $connection->commit();
} catch(\Exception $e) {
    $connection->rollBack();
}




/*$salesorderitemObj = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\Item\Collection');
$salesorderitem_collection = $salesorderitemObj->addAttributeToFilter('created_at', array('from'=>$startDate, 'to'=>$endDate));


$salesinvoiceitemObj = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\Invoice\Item\Collection');
$salesinvoiceitem_collection = $salesinvoiceitemObj->addAttributeToFilter('created_at', array('from'=>$startDate, 'to'=>$endDate));

echo "<pre>";
print_r(count($product_skus_cost_array));
exit;*/