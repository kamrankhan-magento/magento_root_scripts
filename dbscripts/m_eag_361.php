
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
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$setup = $objectManager->create('Magento\Framework\Setup\ModuleDataSetupInterface');


$sql = "CREATE TABLE `tm_google_map` (
  `entity_id` int(10) AUTO_INCREMENT PRIMARY KEY,
  `order_id` int(10) DEFAULT NULL,
  `order_increment` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `value` float DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'Order status',
  `pre_orders` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'previous orders',
  `geocoding_status` varchar(255) DEFAULT NULL COMMENT 'gecoding status'
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$connection->query($sql);
