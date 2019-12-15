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
$first_file = 'customer_group_price.csv';

$second_file = "CapitolWholesaleProductLoad-edited.csv";

if (file_exists($first_file)) {

    $csvData = $fileCsv->getData($first_file);

    $accounts_no_array = array();
    $account_sku_price_array = array();
    $product_skus = array();
    $counter = 1;
    foreach ($csvData as $row => $data) {
        if ($counter != 1) {
            $hight_price[$data[0]] = $data[1];
        }

        $counter++;
    }

}


    if (file_exists($second_file)) {

        $secondCsvData = $fileCsv->getData($second_file);


        $counter = 1;
        $outputFile = "CapitolWholesaleProductLoad-edited_merged.csv";
        $handle = fopen($outputFile, 'w');

        foreach ($secondCsvData as $secrow => $secdata) {
            if ($counter == 1) {
                $heading = $secdata;
                fputcsv($handle, $heading);
            }
            else
            {
                if(array_key_exists($secdata[4], $hight_price))
                {
                    $secdata[11] = $hight_price[$secdata[4]];
                    $row = $secdata;
                    fputcsv($handle, $row);
                }
                else
                {
                    $row = $secdata;
                    fputcsv($handle, $row);
                }

            }
            $counter++;
        }

    }

