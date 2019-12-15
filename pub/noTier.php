<?php
use Magento\Framework\App\Bootstrap;
use Gilbitron\Util\SimpleCach;

 require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();


$fileCsv = $objectManager->get('Magento\Framework\File\Csv');
$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
$rootPath  =  $directory->getRoot();

$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')->setStoreId(5);

$collection = $productCollection->addAttributeToSelect('*')
            ->load();

$fp = fopen("noTier.csv","w+");     			    
foreach($collection as $product){
    $_productloader = $objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
    $_product = $_productloader->getById($product->getId());
	echo "<pre>";
	print_r($_product->getData('tier_price'));
	if(count($_product->getData('tier_price')) <= 0)
	{
    	//echo $product->getSku() ."<br>";
	    $data = array();
	    $data[] = $product->getId();
	    $data[] = $product->getSku();
		$data[] = $_product->getName();
	    $data[] = $product->getCapitolSku();
	    fputcsv($fp, $data); 
	}
}



die("here");

