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


$tableName = "mb_microconnect_orders";
$resource = $obj->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
echo "<pre>";

$__orderCollection = $obj->create('Magento\Sales\Model\ResourceModel\Order\CollectionFactory');

$orderCollection = $__orderCollection->create();
        $orderCollection->getSelect()
            ->join(array('amasty_delivery' => "amasty_amdeliverydate_deliverydate"),  'main_table.entity_id= amasty_delivery.order_id',
                array('date')
            );
        $orderCollection->addFieldToFilter('store_id',1);
        //$orderCollection->addFieldToFilter('main_table.increment_id',"100174085");
        
        $orderCollection->addFieldToFilter('amasty_delivery.date',array("from"=>date("Y-m-d 00:00:00"),"date"=>true));


foreach($orderCollection as $_order){
    $ordernumber = $_order->getIncrementId();
    
    $sql = "SELECT * FROM `$tableName` WHERE `entry_id` = '$ordernumber'";
    			echo (string) $sql;
				$datasql =  $connection->fetchAll($sql);
               if(count($datasql)){
                $datasql = $datasql[0];
                $entityid = $datasql["mc_entity_id"];
                if($entityid){
                 $updatesql = "UPDATE `$tableName` SET `nav_key` = '',`status` = '1' WHERE `$tableName`.`mc_entity_id` = $entityid";
                 $connection->query($updatesql);   
                }
               }
               $orderModel = $obj->create('Magento\Sales\Api\Data\OrderInterface')->load($_order->getId());
               $obj->create('Mb\Microconnect\Model\CronData')->updateDb($orderModel,"ORDER");
                
                echo "updated $ordernumber";
				echo"<br>";
}


exit;
    $product_skus = array();
    //$count=0;
    foreach ($csvData as $row => $data) {

    	$sku = $data[0];
    	if($sku != "sku")
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