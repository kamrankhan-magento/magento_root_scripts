<?php

use Magento\Framework\App\Bootstrap;
require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$storeManager = $obj->get('Magento\Store\Model\StoreManagerInterface');
$state->setAreaCode('adminhtml');
$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
$_orderloader = $objectManager->get('\Magento\Sales\Model\Order');    
$OrderResource = $objectManager->get('\Magento\Sales\Model\ResourceModel\Order');    
$_navstatusfactory = $objectManager->get('\Mb\Microconnect\Model\NavstatusFactory');    
    
        // \Magento\Backend\App\Action\Context $context,
        // \Magento\Sales\Model\Order $_orderloader, 
        // \Magento\Sales\Model\ResourceModel\Order $OrderResource,
        // \Mb\Microconnect\Model\NavstatusFactory $navstatusfc
 
        // $_navstatusfactory = $navstatusfc;
        // $_orderloader = $_orderloader;
        // $OrderResource = $OrderResource;
    
        $resultPage = $_navstatusfactory->create();
        $navTableCollection = $resultPage->getCollection();
        $navTableCollection->addFieldToFilter('magento_status',  array(
							                    array('like' => 'movement_created'),
							                    array('like' => 'no_stock')
							                ));
        $counter = 1;
        $orderNumArray = array();
        foreach ($navTableCollection as $key => $value) 
        {
            $status = $value->getData('status');
            $orderNum = $value->getOrderNumber();
            //echo $orderNum.'</br>';
            $mageOrderModel = $_orderloader->loadByIncrementId($orderNum);
            $mageOrderModel->save();
            /*if($status == 'Pick_Pending_No_Stock')
            {
                if($mageOrderModel->getData('status')=="processing")
                {
                    $mageOrderModel->setData('status', "no_stock");
                    $OrderResource->saveAttribute($mageOrderModel, 'status');
                    $orderNumArray[] = $orderNum;
                    $counter++;
                }

            }
            elseif($status == 'Pick_Pending_Movement_Created')
            {
                if($mageOrderModel->getData('status')=="processing") {
                    $mageOrderModel->setData('status', "movement_created");
                    $OrderResource->saveAttribute($mageOrderModel, 'status');
                    $orderNumArray[] = $orderNum;
                    $counter++;
                }
            }*/
        }
        /*echo "Total No of Orders Changed".$counter.'</br>';
        echo "<pre>";
        print_r($orderNumArray);
        exit;*/
        //echo $status.'</br>';
        //echo $orderNum.'</br>';
        // die("dead");
   

