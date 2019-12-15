
<?php
use Magento\Framework\App\Bootstrap;

require '../../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

error_reporting(E_ALL);
ini_set('display_errors', 1);



$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$setup = $objectManager->create('Magento\Framework\Setup\ModuleDataSetupInterface');

$installer = $setup;
    $installer->startSetup();
    //Reviews Table
    $yotporeviews = $installer->getConnection()->newTable(
        $installer->getTable('tons_yotpo_reviews')
            )->addColumn(
                'review_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE]
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => false, 'unsigned' => TRUE],
                'product_id'
            )->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => false, 'unsigned' => TRUE],
                'user_id'
            )->addColumn(
                'score',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false, 'unsigned' => TRUE],
                'score'
            )->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'title'
            )->addColumn(
                'content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'content'
            )->addColumn(
                'is_verified_buyer',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => true, 'unsigned' => TRUE],
                'is verified buyer'
            )->addColumn(
                'created_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false, 'unsigned' => TRUE],
                'Date Created'
            )->addColumn(
                'updated_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [ 'nullable' => false, 'unsigned' => TRUE ],
                'Date Updated'
            )->addColumn(
                'review_comments',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'comments'
            );
    $installer->getConnection()->createTable($yotporeviews);
            
    //User details Table
    $yotpouserdetails = $installer->getConnection()->newTable(
        $installer->getTable('tons_yotpo_user_details')
            )->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE]
            )->addColumn(
                'display_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'name'
            )->addColumn(
                'display_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'image'
            )->addColumn(
                'user_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'user type'
            )->addColumn(
                'social_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Social Image'
            );
    $installer->getConnection()->createTable($yotpouserdetails);
    
    //Questions Table
    $yotpoquestions = $installer->getConnection()->newTable(
        $installer->getTable('tons_yotpo_questions')
            )->addColumn(
                'question_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE]
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => false, 'unsigned' => TRUE],
                'product_id'
            )->addColumn(
                'content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'content'
            )->addColumn(
                'user_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'user type'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                2,
                ['nullable' => true, 'unsigned' => TRUE],
                'status'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false, 'unsigned' => TRUE],
                'Date Created'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [ 'nullable' => false, 'unsigned' => TRUE ],
                'Date Updated'
            );
    $installer->getConnection()->createTable($yotpoquestions);
    
    //Questions Asker details Table
    $yotpoquestionsaskerdetails = $installer->getConnection()->newTable(
        $installer->getTable('tons_yotpo_question_asker_details')
            )->addColumn(
                'asker_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE]
            )->addColumn(
                'display_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'name'
            )->addColumn(
                'slug',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'slug'
            )->addColumn(
                'social_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Social Image'
            )->addColumn(
                'is_social_connected',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Social Connected'
            )->addColumn(
                'bio',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'bio'
            )->addColumn(
                'score',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false, 'unsigned' => TRUE],
                'score'
            )->addColumn(
                'badges',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'badges'
            );
    $installer->getConnection()->createTable($yotpoquestionsaskerdetails);
    
    //Questions Asker Table
    $yotpoquestionsasker = $installer->getConnection()->newTable(
        $installer->getTable('tons_yotpo_question_asker')
            )->addColumn(
                'inc_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE],
                'Autoincremental ID'
            )->addColumn(
                'asker_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => FALSE]
            )->addColumn(
                'question_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => FALSE]
            );
    $installer->getConnection()->createTable($yotpoquestionsasker);
    
    
    //Yotpo Products
    $yotpoproducts = $installer->getConnection()->newTable(
        $installer->getTable('tons_yotpo_products')
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE]
            )->addColumn(
                'domain_key',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => FALSE]
            )->addColumn(
                'product_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'product name'
            )->addColumn(
                'embedded_widget_link',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false]
            )->addColumn(
                'testimonials_product_link',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false]
            )->addColumn(
                'product_link',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'product link'
            );
    $installer->getConnection()->createTable($yotpoproducts);
    
    //Yotpo Products Social Link
    $yotpoproductslink = $installer->getConnection()->newTable(
        $installer->getTable('tons_yotpo_products_social_link')
            )->addColumn(
                'social_link_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE],
                'Autoincremental ID'
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => FALSE]
            )->addColumn(
                'facebook',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'facebook'
            )->addColumn(
                'twitter',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'twitter'
            )->addColumn(
                'linkedin',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'linkedin'
            )->addColumn(
                'google_oauth2',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'google_oauth2'
            );
    $installer->getConnection()->createTable($yotpoproductslink);
    
    //Yotpo Answerer details
     $yotpoanswererdetails = $installer->getConnection()->newTable(
        $installer->getTable('tons_yotpo_answerer_details')
            )->addColumn(
                'answerer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE]
            )->addColumn(
                'display_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'name'
            )->addColumn(
                'slug',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'slug'
            )->addColumn(
                'social_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Social Image'
            )->addColumn(
                'is_social_connected',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Social Connected'
            )->addColumn(
                'bio',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'bio'
            )->addColumn(
                'score',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false, 'unsigned' => TRUE],
                'score'
            )->addColumn(
                'badges',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'badges'
            );
    $installer->getConnection()->createTable($yotpoanswererdetails);
    
    //Yotpo Answerer Answer
     $yotpoanswereranswer = $installer->getConnection()->newTable(
        $installer->getTable('tons_yotpo_answerer_answer')
            )->addColumn(
                'answer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE]
            )->addColumn(
                'answerer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => FALSE]
            );
    $installer->getConnection()->createTable($yotpoanswereranswer);
    
    //Sorted Public Answers
    $yotposortedanswer = $installer->getConnection()->newTable(
        $installer->getTable('tons_yotpo_sorted_public_answers')
            )->addColumn(
                'answer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE]
            )->addColumn(
                'question_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => FALSE]
            )->addColumn(
                'content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false]
            )->addColumn(
                'store_owner_comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => true, 'unsigned' => TRUE]
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false, 'unsigned' => TRUE]
            );
    $installer->getConnection()->createTable($yotposortedanswer);

$installer->endSetup();