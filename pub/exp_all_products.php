<?php


use Magento\Framework\App\Bootstrap;
require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$storeManager = $obj->get('Magento\Store\Model\StoreManagerInterface');
$state->setAreaCode('adminhtml');



$productCollection = $obj->create('Magento\Catalog\Model\ResourceModel\Product\Collection');

$products = $productCollection->addAttributeToSelect('*')
    ->load();
$fp = fopen("exp_all_prod.csv","w+");

$data = array();
$data[] = "sku";
$data[] = "capitol sku";
$data[] = "name";
$data[] = "size";
$data[] = "price";
fputcsv($fp, $data);
foreach($products as $product){

    echo $product->getId() . "\n";
    $data = array();
    $data[] = $product->getSku();
    $data[] = $product->getCapitolSku();
    $data[] = $product->getName();
    $data[] = $product->getResource()->getAttribute('tile_size')->getFrontend()->getValue($product);
    $data[] = $product->getFinalPrice();

    fputcsv($fp, $data);
}
fclose($fp);


// die("heree");
