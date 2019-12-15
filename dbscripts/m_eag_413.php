
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


$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
$salesTable = $resource->getTableName('eav_attribute');


$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$eavSetupFactory = $objectManager->create('Magento\Eav\Setup\EavSetupFactory');
$setup = $objectManager->create('Magento\Framework\Setup\ModuleDataSetupInterface');
$eavConfig = $objectManager->create('Magento\Eav\Model\Config');
//$eavSetupFactory = $objectManager->create('Magento\Framework\Setup\ModuleDataSetupInterface');

$eavSetup = $eavSetupFactory->create(['setup' => $setup]);
		$eavSetup->addAttribute(
			\Magento\Catalog\Model\Product::ENTITY,
			'full_sample_price',
			[
                'type' => 'varchar',
                'label' => 'Full Sample Price',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'default' => '',
                'sort_order' => 100,
                'system' => false,
                'position' => 105
			]
		);
		$sampleAttribute = $eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, 'full_sample_price');

		// more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
		/*$sampleAttribute->setData(
			'used_in_forms',
			['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address','adminhtml_customer']

		);
		$sampleAttribute->addData([
            'attribute_set_id' => 1,
            'attribute_group_id' => 1
        ]);        */
		$sampleAttribute->save();

