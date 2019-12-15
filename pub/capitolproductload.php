<?php
use Magento\Framework\App\Bootstrap;
 
require __DIR__ . '/../app/bootstrap.php';
 
$params = $_SERVER;
 
$bootstrap = Bootstrap::create(BP, $params);

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend'); 

$fileCsv = $objectManager->get('Magento\Framework\File\Csv');
$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
$rootPath  =  $directory->getRoot();
$file = 'capitolproductload.csv';



if (file_exists($file)) {
    $csvData = $fileCsv->getData($file);
    $product_skus = array();
    $counter = 1;
    foreach ($csvData as $row => $data) {
        if($counter!=1)
        {
            $product_skus[] = $data[4];
        }

        $counter++;
    }
}

$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
        ->addAttributeToFilter('sku', array("in" => $product_skus));

foreach ($productCollection as $product)
    {
        $product_sku_ids[$product->getData("sku")] = $product->getData("entity_id");
    }
//echo "<pre>"; print_r($product_sku_ids); exit;
if( count($product_sku_ids) > 0 ){
    
    foreach( $product_sku_ids as $key => $val ){
        $data = array();
        $data['entry_type'] = 'Product';
        $data['entry_id'] = $key;
        $data['status'] = '2';
        $data['nav_key'] = 'Modify';
        $data['sync_time'] = date('Y-m-d H:i:s');
        $data['update_sync_time'] = date('Y-m-d H:i:s');
        $data['product_id'] = $val;
        $product = $objectManager->get('Mb\Microconnect\Model\Product');
        $product->setData($data);
        $product->save();        
    }
    
}

 
?>