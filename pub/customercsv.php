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
$file = 'BCCeramicsSpecificPrices29012019.csv';


$accountNumber = "B00203C";

if (file_exists($file)) {

    $csvData = $fileCsv->getData($file);

    $account_sku_price_array = array();
    $product_skus = array();
    $counter = 1;
    foreach ($csvData as $row => $data) {
        if($counter!=1)
        {
            $product_skus[] = $data[0];
            $account_sku_price_array[$accountNumber.'-'.$data[0]] = $data[1];

        }

        $counter++;
    }

    $customerObj = $objectManager->create('Magento\Customer\Model\ResourceModel\Customer\Collection');
    $customer_collection = $customerObj->addAttributeToSelect('*')
        ->addAttributeToFilter('cus_account_no', array("eq" => $accountNumber));

    foreach ($customer_collection as $customer)
    {
        $customer_accounts_ids[$customer->getData("cus_account_no")] = $customer->getData("entity_id");
    }

    $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
        ->addAttributeToFilter('sku', array("in" => $product_skus));

    foreach ($productCollection as $product)
    {
        $product_sku_ids[$product->getData("sku")] = $product->getData("entity_id");
    }



    foreach ($account_sku_price_array as $key => $account_sku_price_data)
    {
        $key_array = explode("-",$key);

        if(array_key_exists($key_array[0], $customer_accounts_ids) && array_key_exists($key_array[1], $product_sku_ids))
        {
            $new_csv_array[] = array("customer_id" => $customer_accounts_ids[$key_array[0]],
                "product_id" => $product_sku_ids[$key_array[1]],
                "discount_value" => $account_sku_price_data,
                "discount_type" => "F");
        }


    }

    $heading = ['Customer ID','Product ID','Discount Value','Discount Type','Category','Tier quantity','Valid from','Valid to'];
    $outputFile = "mb-final-customer-price-edited-". date('Ymd_His').".csv";
    $handle = fopen($outputFile, 'w');
    fputcsv($handle, $heading);
    foreach ($new_csv_array as $new_csv_array_data) {
        $row = [
            $new_csv_array_data['customer_id'],
            $new_csv_array_data['product_id'],
            $new_csv_array_data['discount_value'],
            $new_csv_array_data['discount_type'],
            '',
            '',
            '',
            ''
        ];
        fputcsv($handle, $row);
    }

}