<?php
use Magento\Framework\App\Bootstrap;
 
require __DIR__ . '/../app/bootstrap.php';
 
$params = $_SERVER;
 
$bootstrap = Bootstrap::create(BP, $params);

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend'); 

$quotes = array(35770,31847,32388);

$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
$myTable = $resource->getTableName('tm_quote_system');

foreach( $quotes as $quote ){
    $connection->delete( $myTable,['quote_number = ?' => $quote] );
    $model = $objectManager->create('\Magento\Quote\Model\Quote')->loadByIdWithoutStore($quote);    
    $model->delete();
}
echo "Quotes are Deleted Successfully..."; exit;
 
?>