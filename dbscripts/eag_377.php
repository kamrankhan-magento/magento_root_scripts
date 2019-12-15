
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

/*$sql = "ALTER TABLE ".$salesTable. " ADD `allow_order` int";
echo (string) $sql;*/
//$connection->query($sql);
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$eavSetupFactory = $objectManager->create('Magento\Eav\Setup\EavSetupFactory');
$setup = $objectManager->create('Magento\Framework\Setup\ModuleDataSetupInterface');
$eavConfig = $objectManager->create('Magento\Eav\Model\Config');
//$eavSetupFactory = $objectManager->create('Magento\Framework\Setup\ModuleDataSetupInterface');

$eavSetup = $eavSetupFactory->create(['setup' => $setup]);
		$eavSetup->addAttribute(
			\Magento\Customer\Model\Customer::ENTITY,
			'allow_order',
			[
                'type' => 'int',
                'label' => 'Allow Order',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required' => false,
                'default' => '1',
                'sort_order' => 107,
                'system' => false,
                'position' => 105
			]
		);
		$sampleAttribute = $eavConfig->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'allow_order');

		// more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
		$sampleAttribute->setData(
			'used_in_forms',
			['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address','adminhtml_customer']

		);
		$sampleAttribute->addData([
            'attribute_set_id' => 1,
            'attribute_group_id' => 1
        ]);        
		$sampleAttribute->save();

