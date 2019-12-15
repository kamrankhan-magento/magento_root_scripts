<?php

//die("here");
use Magento\Framework\App\Bootstrap;
use Gilbitron\Util\SimpleCach;

 require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');


$productCollection = $obj->create('Magento\Catalog\Model\ResourceModel\Product\Collection')->setStoreId(1);

$products = $productCollection->addAttributeToSelect('*');
$productCollection->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
            ->load();  




$fp = fopen("tonsProd.csv","w+");     	

$data = array();
    $data[] = 'Sku';
    $data[] = 'Name';
   	$data[] = 'Height';
   	$data[] = 'Width';
   	$data[] = 'Quantity';
    fputcsv($fp, $data); 


foreach($products as $product){
    echo $product->getSku() ."<br>";

    $productStockObj = $obj->get('Magento\CatalogInventory\Api\StockRegistryInterface')->getStockItem($product->getEntityId());
    
    $data = array();
    $data[] = $product->getSku();
    $data[] = $product->getName();
   	$data[] = $product->getTileHeight();
   	$data[] = $product->getTileWidth();
   	$data[] = $productStockObj->getData('qty');
    fputcsv($fp, $data); 
}
fclose($fp);