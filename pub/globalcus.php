<?php
use Magento\Framework\App\Bootstrap;
use Gilbitron\Util\SimpleCach;

 require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();


$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
 
//Select Data from table
$sql = "SELECT * FROM customer_entity group by email having count(email) > 1";
$result = $connection->fetchAll($sql);

foreach($result as $_fordel)
{
	echo $_fordel['entity_id'];
    echo "<br/>";
	echo $_fordel['email'];
    echo "<br/>";
	//$sql = "Delete FROM customer_entity Where entity_id =".$_fordel['entity_id'];
	//$connection->query($sql);

}

// echo "<pre>";
// print_r($result);

die();