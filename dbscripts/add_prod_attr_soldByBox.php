
<?php
use Magento\Framework\App\Bootstrap;
use Magento\Sales\Model\Order;

require '../../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

error_reporting(E_ALL);
ini_set('display_errors', 1);


$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$eavSetupFactory = $objectManager->create('Magento\Eav\Setup\EavSetupFactory');
$setup = $objectManager->create('Magento\Framework\Setup\ModuleDataSetupInterface');
$eavConfig = $objectManager->create('Magento\Eav\Model\Config');
//$eavSetupFactory = $objectManager->create('Magento\Framework\Setup\ModuleDataSetupInterface');

$eavSetup = $eavSetupFactory->create(['setup' => $setup]);

    $eavSetup->addAttribute(
      \Magento\Catalog\Model\Product::ENTITY,
      'sold_by_box',
      [
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Sold by the Box',
                    'input' => 'boolean',
                    'class' => '',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '1',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => ''
      ]
    );
       
        $setup->endSetup();

