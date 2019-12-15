<?php


use Magento\Framework\App\Bootstrap;
require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$storeManager = $obj->get('Magento\Store\Model\StoreManagerInterface');
$state->setAreaCode('adminhtml');


$productCollection = $obj->create('Magento\Catalog\Model\ResourceModel\Product\Collection');

$products = $productCollection->addAttributeToSelect('*')->setStoreId(2)
            ->load(); 

$sdsd=array();
foreach($products as $pd){
    $sdsd[$pd->getSku()] = $pd->getName();
}

$productCollection = $obj->create('Magento\Catalog\Model\ResourceModel\Product\Collection');

$products = $productCollection->addAttributeToSelect('*')->setStoreId(1)
            ->load();  
$fp = fopen("exportcolinproducts.csv","w+");  

$data = array();
    $data[] = "sku";
	$data[] = "tons name";
	$data[] = "capitol name";
    fputcsv($fp, $data);   			    
foreach($products as $product){

	echo $product->getId() . "\n";
    $data = array();
    $data[] = $product->getSku();
	$data[] = $product->getName();
    if(isset($sdsd[$product->getSku()])){
        $data[] = $sdsd[$product->getSku()];
    }else{
        $data[] = $product->getName();
    }
	
    fputcsv($fp, $data);
}
fclose($fp);


// die("heree");
