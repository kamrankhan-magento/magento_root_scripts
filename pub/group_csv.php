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
$file = 'customer_group_price.csv';

if (file_exists($file)) {

    $csvData = $fileCsv->getData($file);

    $accounts_no_array = array();
    $account_sku_price_array = array();
    $product_skus = array();
    $counter = 1;
    foreach ($csvData as $row => $data) {
        if($counter!=1)
        {
            $customer_group_prices[$data[0]]["price1"] = $data[2];
            $customer_group_prices[$data[0]]["price2"] = $data[3];
            $customer_group_prices[$data[0]]["price3"] = $data[4];
            $customer_group_prices[$data[0]]["price4"] = $data[5];
            $customer_group_prices[$data[0]]["price5"] = $data[6];


        }

        $counter++;
    }


    $heading = ['sku','tier_price_website','tier_price_customer_group','tier_price_qty','tier_price','tier_price_value_type'];
    $outputFile = "customer_group_advance_prices". date('Ymd_His').".csv";
    $handle = fopen($outputFile, 'w');
    fputcsv($handle, $heading);
    $websites = array("CT","CH1","ES","SF");
    foreach ($customer_group_prices as $sku => $customer_group_price) {

        foreach ($customer_group_price as $customer_group => $sku_price)
        {
            foreach ($websites as $website)
            {
                $row = [
                    $sku,
                    $website,
                    $customer_group,
                    1,
                    $sku_price,
                    'Fixed',
                ];
                fputcsv($handle, $row);
            }

        }

    }

}