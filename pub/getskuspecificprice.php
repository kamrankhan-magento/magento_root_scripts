<?php

use Magento\Framework\App\Bootstrap;

require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$storeManager = $obj->get('Magento\Store\Model\StoreManagerInterface');
$state->setAreaCode('adminhtml');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();


$_customerloader = $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface');
$fileCsv = $objectManager->get('Magento\Framework\File\Csv');
$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
$rootPath = $directory->getRoot();


$tableName = "ae_price";
$resource = $obj->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();


$file = 'dup_sku.csv';
echo "<pre>";
if (file_exists($file)) {

    $csvData = $fileCsv->getData($file);

    $accounts_no_array = array();
    $account_sku_price_array = array();
    $product_skus = array();
    $counter = 1;
    foreach ($csvData as $row => $data) {
        if ($counter != 1) {
            $skus[] = $data[1];
            $full_skus[] = $data[1];
            $full_skus[] = $data[0];
        }

        $counter++;
    }

}


$order_collection = $objectManager->get("Magento\Sales\Model\ResourceModel\Order\Collection");
$order_collection->getSelect()
    ->join(
        ["sop" => "sales_order_payment"],
        'main_table.entity_id = sop.parent_id',
        array('method')
    )
    ->join(
        ["sopro" => "sales_order_item"],
        'main_table.entity_id = sopro.order_id',
        array('*')
    )
    ->where('sopro.sku IN (' . implode(",", $full_skus) . ') AND sop.method IN ("adminpaymentmethod","paybycredit")');


$orderDAta = $order_collection->getData();

$fp = fopen("finalskuwiseprice.csv", "w+");

$data = array();
$data[] = "ordernumber";
$data[] = "itemid";
$data[] = "price";
fputcsv($fp, $data);

$iid = 0;
$allorders = array();
foreach ($orderDAta as $ordat) {


    $sku = $ordat["sku"];
    $orderNumber = $ordat["entity_id"];
    $productid = $ordat["product_id"];

    $itemId = $ordat["item_id"];
    $itemPrice = $ordat["price"];


    $_productloader = $objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
    $_product = $_productloader->getById($productid);


    $GroupId = 0;
    $customerid = "";

    if ($ordat["customer_id"]) {
        $customerid = $ordat["customer_id"];
        $customer = $_customerloader->getById($customerid);
        $GroupId = $customer->getGroupId();

    }
    $customerprice = 0;
    if ($customerid) {
        $searchquery = "SELECT price FROM `$tableName` WHERE `price_customer_id` = " . $customerid . " AND `price_product_id` = " . $productid;
        $searchresult = $connection->fetchAll($searchquery);
        if (isset($searchresult[0])) {
            $customerspecificprice = $searchresult[0]["price"];
            if (isset($customerspecificprice) && $customerspecificprice > 0) {
                $customerprice = $customerspecificprice;
            }
        }

    }
    if ($customerprice == 0) {
        $productAllTierPrices = $_product->getData('tier_price');
        foreach ($productAllTierPrices as $tierPrice) {
            if ($GroupId != 0 && $tierPrice["cust_group"] == $GroupId) {
                $customerprice = $tierPrice["price"];
            }
        }
    }

    if ($customerprice == 0) {
        $customerprice = $_product->getPrice();
    }


    if ($itemPrice > $customerprice) {
        $data = array();
        $data[] = $orderNumber;
        $data[] = $itemId;
        $data[] = $customerprice;
        fputcsv($fp, $data);
        $iid++;
        echo $iid . " ) " . $orderNumber . "----" . $sku . "----" . $customerprice . "\n";
        $allorders[$orderNumber] = $orderNumber;
    }


}

echo "Total Orders need to change : " . count($allorders) . "\n";
fclose($fp);    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    