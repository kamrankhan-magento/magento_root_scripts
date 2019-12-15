<?php

use Magento\Framework\App\Bootstrap;


require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();



$productCollectionFactory = $objectManager->get('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');

$storeid = 1;
$collection = $productCollectionFactory->create();
$collection->addAttributeToSelect('*');
$collection->addStoreFilter($storeid);


echo "<pre>";
print_r($collection->getData());
exit;