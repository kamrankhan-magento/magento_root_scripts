<?php
use Magento\Framework\App\Bootstrap;
use Gilbitron\Util\SimpleCach;

 require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$ccCollection = $objectManager->create("Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory");

$invoiceCollection = $ccCollection->create()->addAttributeToSelect('*');

            $invoiceCollection->setOrder('created_at','DESC');
            $invoiceCollection->load();
            	
            foreach($invoiceCollection as $invoice)
            {

            	$order = $objectManager->create('Magento\Sales\Model\Order')->load($invoice->getData('order_id'));
				$orderItems = $order->getAllItems();

				

            	// foreach($invoice->getItemsCollection()->getData() as $invoiceItems)
            	// {
            		foreach ($orderItems as $item) 
            		{
            			//if($item->getData('sku') == $invoiceItems['sku'])
            			//{
            				if($item->getData('row_invoiced') != $item->getData('row_total'))
            				{
            					echo "<pre>";
            					echo $order->getData('increment_id');
            					echo "<br>";
            					$orderBaseTax = 0;
            					foreach ($order->getAllVisibleItems() as $_item) {
					                $orderBaseTax += $_item->getBaseTaxAmount() + $_item->getBaseHiddenTaxAmount();
					            }

					            $orderBaseTax += $order->getShippingTaxAmount();

					            //echo $orderBaseTax;

            					$order->setTaxInvoiced($orderBaseTax)
            					->setBaseTaxInvoiced($orderBaseTax);
            					$order->save();


            					$item->setRowInvoiced($item->getData('row_total'));
            					$item->setBaseRowInvoiced($item->getData('row_total'));
            					$item->save();
            					//print_r($item->getData());

								  //die("here");
            				}
            			//}
					}
            	//}
            }


            die("here");
