<?php

use Magento\Framework\App\Bootstrap;
require '../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$fileCsv = $objectManager->get('Magento\Framework\File\Csv');
$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
$rootPath = $directory->getRoot();

$tableName = "mb_microconnect_orders";
$resource = $obj->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();

$file = 'OrderstoRequeue-Sheet1.csv';

if (file_exists($file)) {


    $csvData = $fileCsv->getData($file);

    $accounts_no_array = array();
    $account_sku_price_array = array();
    $product_skus = array();
    //$count=0;
    foreach ($csvData as $row => $data) {

    	//$count++;
    	if($data[0]=="NO" || $data[0]=="Test" || $data[0]=="order no")
    	{
    		continue;
    	}
    	else{

    		//if($count<10)
    		//{
    			$sql = "UPDATE `mb_microconnect_orders` SET `status`=1 , `nav_key`='' WHERE `entry_id`='$data[0]'";
    			echo (string) $sql;
				$connection->query($sql);
				echo"<br>";
    		//}
    	}		
    }
}