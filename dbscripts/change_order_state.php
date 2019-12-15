<?php

use Magento\Framework\App\Bootstrap;
use Magento\Sales\Model\Order;
require '../../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();


	
	$orderId = $_GET['order_id'];
	//$orderId = 18822;

if($orderId)
{
	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	$order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId);

	foreach ($order->getAllItems() as $item) {
		// echo "<pre>";
		// print_r($item->getData());
		$item->setQtycanceled(0);
		$item->setTaxCanceled(0);
		$item->setQtyBackordered(0);
		$item->save();
	}

	$orderState = Order::STATE_PROCESSING;
	$order->setState($orderState)->setStatus($orderState);
	$order->save();

}
	

	die();

