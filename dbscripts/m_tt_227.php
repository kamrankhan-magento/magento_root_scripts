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



$sql = "INSERT INTO `translation` (`string`, `store_id`, `translate`, `locale`, `crc_string`) VALUES ('For delivery questions.', '0', 'For delivery updates.', 'en_GB', '2205035978');";
$connection->query($sql);
