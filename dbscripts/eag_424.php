
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
            $installer->getTable('mb_microconnect_creditmemos')
        )->addColumn(
            'mc_entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            ['identity' => TRUE, 'unsigned' => TRUE, 'nullable' => FALSE, 'primary' => TRUE],
            'Autoincremental ID'
        )->addColumn(
            'entry_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => FALSE],
            'Type of entry to the Nav database eg customer , order'
        )->addColumn(
            'entry_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => FALSE],
            'Id for above entry'
        )->addColumn(
            'creditmemo_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            ['nullable' => FALSE],
            'Creditmemo Id'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'unsigned' => TRUE],
            'Internal status'
        )->addColumn(
            'nav_key',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            ['nullable' => false],
            'key returned from NAV'
        )->addColumn(
            'sync_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['default' => NULL],
            'sync_time'
        )->addColumn(
            'update_sync_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['default' => NULL],
            'update_sync_time'
        )->addColumn(
            'is_deleted',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'Is this entity is canceled or closed in magento'
        )->addColumn(
            'is_error_in_nav',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'is there an error in nav processing'
        )->addColumn(
            'nav_error',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false, 'default' => null],
            'error from nav'
        )->addColumn(
            'retries',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            1,
            ['nullable' => false, 'default' => '0'],
            'total number of retries'
        )->addColumn(
            'on_hold',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'is on hold'
        )->addIndex(
            "MB_MICROCONNECT_CREDITMEMOS_ENTRY_ID",
            ['entry_id']
        )->addIndex(
            "MB_MICROCONNECT_CREDITMEMOS_ENTRY_TYPE",
            ['entry_type']
        );
        $installer->getConnection()->createTable($table);

