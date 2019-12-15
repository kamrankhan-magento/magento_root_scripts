<?php
use Magento\Framework\App\Bootstrap;
 
require __DIR__ . '/../../app/bootstrap.php';
 
$params = $_SERVER;
 
$bootstrap = Bootstrap::create(BP, $params);

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

//$reviews = $objectManager->create('\TM\YotpoReviews\Cron\GetReviews');
$_helper = $objectManager->create('\TM\YotpoReviews\Helper\Data');
$productCollection = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');
$time_start = microtime(true);
$api_key = $_helper->getConfigValue( 'yotpo_reviews/general/api_key' );

try {
	ini_set('max_execution_time', 30000000000000);
	$collection = $productCollection->addAttributeToFilter('status', array('eq' => 1));
    $collection->setPageSize(100);
	$pages = $collection->getLastPageNumber();
    $filename = $objectManager->getUrl();
    echo $filename; exit;
    $currentPage = 1;
    do{
        $collection->setCurPage($currentPage);
        $collection->load();
        foreach ($collection as $product) {
    
    		$ch = curl_init();
    		$url = "https://api.yotpo.com/v1/widget/" . $api_key . "/products/" . $product->getId() . "/reviews.json?sort[]=date&page=1&per_page=200";

    		curl_setopt($ch, CURLOPT_URL, $url);
    		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		$reviews = curl_exec($ch);
    		$resposeCOde = curl_getinfo($ch);
    		if ($resposeCOde['http_code'] != 200)
    		{
    			curl_close($ch);
    			return false;
    		}
    
    		if (isset($reviews) && !is_null($reviews)) { 
    			$review_data_array = json_decode($reviews, true); 
    			if ($review_data_array['response']['pagination']['total'] > 0) {
    				
                    $product_array[] = array(
                        'product_id' => $review_data_array['response']['products'][0]['domain_key'],
    					'domain_key' => $review_data_array['response']['products'][0]['domain_key'],
    					'product_name' => $review_data_array['response']['products'][0]['name'],
    					'embedded_widget_link' => $review_data_array['response']['products'][0]['embedded_widget_link'],
    					'testimonials_product_link' => $review_data_array['response']['products'][0]['testimonials_product_link'],
    					'product_link' => $review_data_array['response']['products'][0]['product_link']
                    );
                    
    				$product_social_link[] =   array(
                        'product_id' => $review_data_array['response']['products'][0]['domain_key'],
    					'facebook' => $review_data_array['response']['products'][0]['social_links']['facebook'],
    					'twitter' => $review_data_array['response']['products'][0]['social_links']['twitter'],
    					'linkedin' =>$review_data_array['response']['products'][0]['social_links']['linkedin'],
    					'google_oauth2' => $review_data_array['response']['products'][0]['social_links']['google_oauth2']
                    );
    
    				$reviews_array = $review_data_array['response']['reviews'];
    				foreach($reviews_array as $review)
    				{
    					$review_array[] = array(
                            'review_id' => $review['id'],
    						'product_id' =>  $review_data_array['response']['products'][0]['domain_key'],
    						'user_id' => $review['user']['user_id'],
    						'score' =>   $review['score'],
    						'title' =>   $review['title'],
    						'content' => $review['content'],
    						'is_verified_buyer' =>  (isset($review['verified_buyer']) && $review['verified_buyer'] != "" )?$review['verified_buyer']:"0",
    						'review_comments' => isset( $review['comment'] )?json_encode( $review['comment'] ):"Null",
    						'created_date' =>  $review['created_at'],
    						'updated_date' => date("Y-m-d h:i:s")
    					);
                        
    					$user_array[] = array(
                            'user_id' => $review['user']['user_id'],
    						'display_name' => $review['user']['display_name'],
    						'display_image' => isset( $review['user']['display_image'] )? $review['user']['display_image'] : "Null",
    						'user_type' => $review['user']['user_type'],
    						'social_image' =>(isset($review['user']['social_image']) && $review['user']['social_image'] != "" )?$review['user']['social_image']:"Null"
                        );
    					 
                        if(!empty($review['images_data']) && $review['images_data'] != NULL)
    					{
    						foreach($review['images_data'] as $image)
    						{
    							$image_array[$review['id']][] =  array(
                                    'id_images' => $image['id'],
    								'id_review' => $review['id'],
    								'thumb_url' => $image['thumb_url'],
    								'original_url' => $image['original_url'],
                                    'product_id' => $review_data_array['response']['products'][0]['domain_key'] 
                                );
    						}
    					}
    				}
    			}else{
    				continue;
    			}
    		}
    	}

	/*End Code*/

	/*Save Product and Review Data In Db*/
	$_helper->saveProductData($product_array, $product_social_link);            
    $_helper->saveReviewData($review_array, $user_array, $image_array);
	
	$currentPage++;
    $collection->clear();
    }while ($currentPage <= $pages);
}
catch(Exception $e)
{
	echo $e->getMessage(); exit;
}


?>