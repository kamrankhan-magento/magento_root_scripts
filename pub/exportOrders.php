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




$productCollection = $obj->create('Magento\Catalog\Model\ResourceModel\Product\Collection')->setStoreId(5);

$products = $productCollection->addAttributeToSelect('*')
            ->load();  
$nameArray = array();  			    
foreach($products as $product){
   $nameArray[$product->getSku()] =  $product->getName();
}





    
$ProductResource = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product');



$_customerloader = $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface');    


$tableName = "ae_price";
$resource = $obj->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();

echo "<pre>";

$orderCollection = $obj->create('Magento\Sales\Model\ResourceModel\Order\Collection');

$orders = $orderCollection->addFieldToFilter("store_id",array("in"=>array(5,3)));  
$fp = fopen("exportalltrans2.csv","w+"); 

$tempArray = array();
$tempArray["id"] = "Order No";
$tempArray["customer"] = "Customer account number";
$tempArray["sku"] = "Sku";
$tempArray["capitolsku"] = "Capitol Sku";
$tempArray["name"] = "Name";
$tempArray["size"] = "Size";
$tempArray["cost"] = "Cost";
$tempArray["sold"] = "Sold at (ex vat)";
$tempArray["cs_price"] = "Customer specific pricing"; 
$tempArray["qty"] = "Ordered Qty";
$tempArray["method"] = "Payment Method";
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
    
    
    foreach($AllItems as $item){
        $_productloader = $objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
        $_product = $_productloader->getById($item->getProductId());
        
        
        
        $customerprice = 0;
        if($customerid){
          $searchquery = "SELECT price FROM `$tableName` WHERE `price_customer_id` = " . $customerid . " AND `price_product_id` = " . $item->getProductId();  
          $searchresult = $connection->fetchAll($searchquery);
          $customerspecificprice = $searchresult[0]["price"];
          if(isset($customerspecificprice) && $customerspecificprice > 0){
            $customerprice = $customerspecificprice;
          }
        }
        
        
        if($customerprice == 0){
            $productAllTierPrices = $_product->getData('tier_price');
            foreach($productAllTierPrices as $tierPrice){
                if($tierPrice["cust_group"] == $GroupId){
                    $customerprice = $tierPrice["price"];
                }
            }
        }
        
        if($customerprice == 0){
            $customerprice = $_product->getPrice();
        }
        
        $tempArray = array();
        $tempArray["id"] = $order->getIncrementId();
         $tempArray["customer"] = $customerAccountnumber;
          $tempArray["sku"] = $item->getSku();
          $tempArray["capitolsku"] = $_product->getCapitolSku();
           $tempArray["name"] = ($nameArray[$item->getSku()])?$nameArray[$item->getSku()]:$item->getName();
            $tempArray["size"] = round($_product->getTsDimensionsWidth(),2) . "x" . round($_product->getTsDimensionsHeight(),2);
             $tempArray["cost"] = $_product->getCost();
              $tempArray["sold"] = $item->getPrice();
               $tempArray["cs_price"] = $customerprice;
                $tempArray["qty"] = $item->getQtyOrdered();
                $tempArray["method"] = $methodTitle;
               
    fputcsv($fp, $tempArray); 
               //print_r($tempArray);
    }
    $ii++;
    echo $ii . "\n";
}

fclose($fp);