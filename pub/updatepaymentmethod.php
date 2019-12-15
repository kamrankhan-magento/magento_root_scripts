<?php
error_reporting(0);
use Magento\Framework\App\Bootstrap;
 require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$storeManager = $obj->get('Magento\Store\Model\StoreManagerInterface');
$state->setAreaCode('adminhtml');



$__orderCollection = $obj->create('Magento\Sales\Model\ResourceModel\Order\CollectionFactory');

$orderCollection = $__orderCollection->create();
        $orderCollection->sales_order_table = "main_table";
        $orderCollection->sales_order_payment_table = $orderCollection->getTable("sales_order_payment");
        $orderCollection->getSelect()
            ->join(array('payment' => $orderCollection->sales_order_payment_table), $orderCollection->sales_order_table . '.entity_id= payment.parent_id',
                array('payment_method' => 'payment.method',
                    'order_id' => $orderCollection->sales_order_table.'.entity_id'
                )
            );
        $orderCollection->addFieldToFilter('store_id',3);
        $orderCollection->addFieldToFilter('payment.method','multiple_payment');
        



echo "<pre>";
 $fp = fopen("paybycredit.csv","w+");
foreach($orderCollection->getData() as $oo){


    $orderId = $oo["entity_id"];
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
    $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
    $connection = $resource->getConnection();
    $tableName = $resource->getTableName('bms_pointofsales_order_payment'); //gives table name with prefix
    
    $sql = "Select * FROM " . $tableName . " WHERE order_id = $orderId";
$result = $connection->fetchAll($sql); // gives associated array, table fields as key in array.
$totalAmount = 0;
        foreach($result as $rst){
            $totalAmount = $rst["amount"];
        }


if($oo["customer_id"]){
 if($totalAmount == 0){
  echo $orderId . "----" . $oo["increment_id"] . "---" . "Possible Bay by Credit" . "-----" . $oo["customer_id"] . "<br>";  
  
  $sql = "Update sales_order_payment Set method = 'paybycredit' where parent_id = " .$orderId;
    $connection->query($sql);

   
  $data = array();
    $data[] = $oo["increment_id"];
    $data[] = $oo["customer_id"];
    fputcsv($fp, $data); 
}else{
    //echo $oo["increment_id"] . "---" . $oo["payment_method"]  . "<br>";
}   
}



}
fclose($fp);
exit;