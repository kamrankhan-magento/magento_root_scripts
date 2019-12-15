
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
$salesTable = $resource->getTableName('sales_order_grid');

$sql = "ALTER TABLE ".$salesTable. " ADD `account_no` varchar(255)";
echo (string) $sql;
$connection->query($sql);

echo"<br>";

$customerTable = $resource->getTableName('customer_entity_varchar');

$sql = "UPDATE sales_order_grid INNER JOIN customer_entity_varchar ON sales_order_grid.customer_id=customer_entity_varchar.entity_id SET sales_order_grid.account_no=customer_entity_varchar.value WHERE sales_order_grid.customer_id=customer_entity_varchar.entity_id AND customer_entity_varchar.attribute_id=248";

echo (string) $sql;
$connection->query($sql);

echo"<br>";
die("die here");

/*UPDATE sales_order_grid INNER JOIN customer_entity_varchar ON sales_order_grid.customer_id=customer_entity_varchar.entity_id SET sales_order_grid.account_no=customer_entity_varchar.value WHERE sales_order_grid.customer_id=customer_entity_varchar.entity_id AND customer_entity_varchar.attribute_id=248*/