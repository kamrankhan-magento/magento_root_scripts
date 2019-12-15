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

$tableName = "mb_microconnect_products";
$resource = $obj->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
echo "<pre>";
$file = 'skus-to-sync-insert.csv';

if (file_exists($file)) {


    $csvData = $fileCsv->getData($file);
    $product_skus = array();
    //$count=0;
    foreach ($csvData as $row => $data) {

    	$sku = $data[0];
    	if($sku != "Sku")
    	{
    	$sql = "SELECT * FROM `$tableName` WHERE `entry_id` = '$sku'";
    			echo (string) $sql;
				$datasql =  $connection->fetchAll($sql);
               if(count($datasql)){
                $datasql = $datasql[0];
                $entityid = $datasql["mc_entity_id"];
                if($entityid){
                 $updatesql = "UPDATE `$tableName` SET `nav_key` = '',`status` = '1' WHERE `$tableName`.`mc_entity_id` = $entityid";
                 $connection->query($updatesql);   
                }
                
                
               }else{
                echo "no Exits $sku";
               }
				echo"<br>";
    	}
    		
    }
}