<?php
use Magento\Framework\App\Bootstrap;
use Gilbitron\Util\SimpleCach;

 require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');


$productCollection = $obj->create('Magento\Catalog\Model\ResourceModel\Product\Collection')->setStoreId(0);

$products = $productCollection->addAttributeToSelect('*')
            ->load();
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$stkobj = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface');              
$fp = fopen("exportproductsharjeel.csv","w+");     			    
foreach($products as $product){
        echo $product->getSku() ."<br>";

    $data = array();
    $data[] = $product->getSku();
    $data[] = $product->getName();
    
$productStockObj = $stkobj->getStockItem($product->getId());

    $data[] = $productStockObj->getData("qty");
    fputcsv($fp, $data);
    
     
}
fclose($fp);