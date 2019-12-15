
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

$store_id = 5;

$orderSql = 'DELETE FROM `sales_order` WHERE `store_id`='.$store_id;
$orderGridSql = 'DELETE FROM `sales_order_grid` WHERE `store_id`='.$store_id;
$shipmentSql = 'DELETE FROM `sales_shipment` WHERE `store_id`='.$store_id;
$shipmentGridSql = 'DELETE FROM `sales_shipment_grid` WHERE `store_id`='.$store_id;
$invoiceSql = 'DELETE FROM `sales_invoice` WHERE `store_id`='.$store_id;
$invoiceGridSql = 'DELETE FROM `sales_invoice_grid` WHERE `store_id`='.$store_id;
$creditMemoSql = 'DELETE FROM `sales_creditmemo` WHERE `store_id`='.$store_id;
$creditMemoGridSql = 'DELETE FROM `sales_creditmemo_grid` WHERE `store_id`='.$store_id;


$connection->query($orderSql);
$connection->query($orderGridSql);
$connection->query($shipmentSql);
$connection->query($shipmentGridSql);
$connection->query($invoiceSql);
$connection->query($invoiceGridSql);
$connection->query($creditMemoSql);
$connection->query($creditMemoGridSql);

echo"Orders Deleted Successfully";



