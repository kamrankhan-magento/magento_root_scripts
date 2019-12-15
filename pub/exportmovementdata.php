<?php
use Magento\Framework\App\Bootstrap;
use Gilbitron\Util\SimpleCach;

 require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');



if(isset($_GET["delete_table"]) && $_GET["delete_table"] == 1){
    
    
    $resource = $obj->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
$tableName = "mb_microconnect_navstatus"; //gives table name with prefix

//Insert Data into table
$sql = "TRUNCATE TABLE `$tableName` ";
$connection->query($sql); 
}else{
  $productCollection = $obj->create('Mb\Microconnect\Model\Navstatus')->getCollection();

 
$headers = array();
$headers[] = "order_number";
$headers[] = "order_date";
$headers[] = "shipment_date";
$headers[] = "status";
$headers[] = "magento_status";
$headers[] = "movement_details";
             
$fp = fopen("php://output","w");   

header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="exportmomentlines.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    fputcsv($fp, $headers);  			    
foreach($productCollection as $product){
    $data = array();
    $data[] = $product->getOrderNumber();
    $data[] = $product->getCreatedDate();
    $data[] = $product->getModifiedDate();
    $data[] = $product->getStatus();
    $data[] = $product->getMagentoStatus();
    $data[] = $product->getMomentNumber();
    
    fputcsv($fp, $data);
    
     
}
fclose($fp);  
}



