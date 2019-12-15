<?php
use Magento\Framework\App\Bootstrap;
require '../../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
$Table = $resource->getTableName('mb_order_processing_pallet');
$sql = "ALTER TABLE ".$Table. " ADD `status_at_pallex` varchar(255)";
$connection->query($sql);
echo"Script Exectured Successfully";