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


    
$ProductResource = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product');



$_customerloader = $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface');    


$tableName = "ae_price";
$resource = $obj->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();

echo "<pre>";

$orderCollection = $obj->create('Magento\Sales\Model\ResourceModel\Order\Collection');

$orders = $orderCollection->addFieldToFilter("store_id",array("in"=>array(5,3)));  
$fp = fopen("exportallshipments2.csv","w+"); 

$tempArray = array();
$tempArray["id"] = "Order No";
$tempArray["customer"] = "Customer account number";
$tempArray["delivery"] = "Delivery Charges";
$tempArray["method"] = "Method";
fputcsv($fp, $tempArray); 
$ii = 0; 			    
foreach($orders as $order){
    
    
    $payment = $order->getPayment();
    $method = $payment->getMethodInstance();
    $methodTitle = $method->getTitle();
    
    $AllItems = $order->getAllItems();
    $customerAccountnumber = "";
    $GroupId = 0;
    
    $customerid = "";
    
    if($order->getCustomerId()){
        $customerid = $order->getCustomerId();
        $customer = $_customerloader->getById($order->getCustomerId());
        $GroupId = $customer->getGroupId();
        $customerAttributeData = $customer->__toArray();
        if (isset($customerAttributeData['custom_attributes']['cus_account_no']['value']) && isset($customerAttributeData['custom_attributes']['cus_account_no']['value'])) {
	        	$customerAccountnumber = $customerAttributeData['custom_attributes']['cus_account_no']['value'];
	        }
    }
    
    $tempArray = array();
        $tempArray["id"] = $order->getIncrementId();
         $tempArray["customer"] = $customerAccountnumber;
          $tempArray["delivery"] = $order->getShippingAmount();
           $tempArray["method"] = $order->getShippingDescription();
               
    fputcsv($fp, $tempArray); 
    
    
    
    $ii++;
    echo $ii . "\n";
}

fclose($fp);