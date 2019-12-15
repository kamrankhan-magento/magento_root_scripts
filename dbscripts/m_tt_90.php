
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
$table = $installer->getConnection()->newTable(
            $installer->getTable('tons_review_images')
        )->addColumn(
            'inc_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE],
            'Autoincremental ID'
        )->addColumn(
            'id_images',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            20,
            ['nullable' => false, 'unsigned' => TRUE],
            'images ids'
        )->addColumn(
            'id_review',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            20,
            ['nullable' => false, 'unsigned' => TRUE],
            'review id'
        )->addColumn(
            'thumb_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => FALSE],
            'thumb url'
        )->addColumn(
            'original_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => FALSE],
            'original image url'
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            20,
            ['nullable' => false, 'unsigned' => TRUE],
            'product id'
        );
        $installer->getConnection()->createTable($table);

