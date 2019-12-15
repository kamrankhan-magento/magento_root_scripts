
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
$allorderstable = $resource->getTableName('mb_deliverydate_orders');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$setup = $objectManager->create('Magento\Framework\Setup\ModuleDataSetupInterface');

$sql = "ALTER Table " . $allorderstable . " ADD COLUMN mag_status VARCHAR(255) Not Null AFTER status";
$connection->query($sql);

