<?php

use Magento\Framework\App\Bootstrap;


require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$fileCsv = $objectManager->get('Magento\Framework\File\Csv');
$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
$rootPath = $directory->getRoot();

$file = "finalskuwiseprice.csv";
function updateItemPrice($item_id,$order_id,$item_price,$objectManager)
{
    try
    {
        $item =  $objectManager->get("Magento\Sales\Model\Order\Item")->load($item_id); //Order Itemid
        if($item) { //Check item object exists or not
            $quantity = $item->getQtyOrdered();
            $rowTotal = $item_price * $quantity;

            # calculateTaxAmount
            if (!empty($item->getTaxPercent())) {
                $taxAmountForItem = ($item_price * $item->getTaxPercent()) / 100;
                $taxAmount = ($rowTotal * $item->getTaxPercent()) / 100;
                $item->setTaxAmount($taxAmount)
                    ->setBaseTax_amount($taxAmount);
            }
            $itemPriceInclTax = $item_price + $taxAmountForItem;
            $rowTotalInclTax = $rowTotal + $taxAmount;
            #  Price In Grid Update
            $item->setPrice($item_price)
                ->setPriceInclTax($itemPriceInclTax)
                ->setBasePrice($item_price)
                ->setBasePriceInclTax($itemPriceInclTax);

            #Subtotal On Order * Qty
            $item->setRowTotal($rowTotal)
                ->setBaseRowTotal($rowTotal)
                ->setRowTotalInclTax($rowTotalInclTax)
                ->setBaseRowTotalInclTax($rowTotalInclTax)
                ->setTaxInvoiced($taxAmount)
                ->setBaseTaxInvoiced($taxAmount)
                ->setRowInvoiced($rowTotalInclTax)
                ->setBaseRowInvoiced($rowTotalInclTax);

            # Apply Item Discounted Amount (Recaculated Discount)
            if (!empty($item->getDiscountPercent())) {
                $discountedAmount = $item->getRowTotal() * $item->getDiscountPercent() / 100;
                $item->setDiscountAmount($discountedAmount)
                    ->setBaseDiscountAmount($discountedAmount);
            }
            $item->save();

            $order = $objectManager->get("Magento\Sales\Model\Order")->load($order_id); //Orderid
            $orderSubTotal = 0;
            $orderBaseTax = 0;
            $orderDiscountAmount = 0;
            foreach ($order->getAllVisibleItems() as $_item) {
                $orderSubTotal += $_item->getBaseRowTotal();
                $orderBaseTax += $_item->getBaseTaxAmount() + $_item->getBaseHiddenTaxAmount();
                $orderDiscountAmount += $_item->getBaseDiscountAmount();
            }

            $grandTotal = ($orderSubTotal + $order->getBaseShippingInclTax() + $orderBaseTax) - $orderDiscountAmount;
            // print_r($orderSubTotal); exit;
            # Update Order Totals
            $orderSubTotalIncTax = $orderSubTotal + $orderBaseTax;
            $order->setSubtotal($orderSubTotal)
                ->setSubtotalInvoiced($orderSubTotal)
                ->setBaseSubtotalInvoiced($orderSubTotal)
                ->setSubtotalInclTax($orderSubTotalIncTax)
                ->setBaseSubtotal($orderSubTotal)
                ->setBaseSubtotalInclTax($orderSubTotalIncTax)
                ->setDiscountAmount($orderDiscountAmount)
                ->setBaseDiscountAmount($orderDiscountAmount)
                ->setTaxInvoiced($orderBaseTax)
                ->setTaxAmount($orderBaseTax)
                ->setBaseTaxAmount($orderBaseTax)
                ->setBaseTaxInvoiced($orderBaseTax)
                ->setGrandTotal($grandTotal)
                ->setBaseGrandTotal($grandTotal)
                ->setBaseTotalInvoiced($grandTotal)
                ->setTotalInvoiced($grandTotal)
                ->setBaseTotalPaid($grandTotal)
                ->setTotalPaid($grandTotal);
            //$order->collectTotals();
            $order ->save();
            $orderFactory = $objectManager->get("Magento\Sales\Model\OrderFactory");
            $order = $orderFactory->create()->load($order_id);
            if ($order->hasInvoices()) {
                //echo "here"; exit;
                foreach ($order->getInvoiceCollection() as $invoice) {
                    $update_invoce = false;
                    foreach ($invoice->getAllItems() as $invoice_item) {

                        if($invoice_item->getOrderItemId()==$item_id) // getting qty = 0
                        {
                            $invoice_item->setTaxAmount($taxAmount)
                                ->setBaseTaxAmount($taxAmount);
                            $invoice_item->setPrice($item_price)
                                ->setPriceInclTax($itemPriceInclTax)
                                ->setBasePrice($item_price)
                                ->setBasePriceInclTax($itemPriceInclTax);

                            #Subtotal On Order * Qty
                            $invoice_item->setRowTotal($rowTotal)
                                ->setBaseRowTotal($rowTotal)
                                ->setRowTotalInclTax($rowTotalInclTax)
                                ->setBaseRowTotalInclTax($rowTotalInclTax)
                                ->setTaxInvoiced($taxAmount)
                                ->setBaseTaxInvoiced($taxAmount)
                                ->setRowInvoiced($rowTotalInclTax)
                                ->setBaseRowInvoiced($rowTotalInclTax);

                            # Apply Item Discounted Amount (Recaculated Discount)
                            if (!empty($invoice_item->getDiscountPercent())) {
                                $discountedAmount = $invoice_item->getRowTotal() * $invoice_item->getDiscountPercent() / 100;
                                $invoice_item->setDiscountAmount($discountedAmount)
                                    ->setBaseDiscountAmount($discountedAmount);
                            }
                            echo (string) $invoice_item->getSelect();
                            $invoice_item->save();

                            $update_invoce = true;
                        }
                    }
                    if($update_invoce)
                    {
                        $invoice->setSubtotal($orderSubTotal)
                            ->setSubtotalInclTax($orderSubTotalIncTax)
                            ->setBaseSubtotal($orderSubTotal)
                            ->setBaseSubtotalInclTax($orderSubTotalIncTax)
                            ->setDiscountAmount($orderDiscountAmount)
                            ->setBaseDiscountAmount($orderDiscountAmount)
                            ->setTaxAmount($orderBaseTax)
                            ->setBaseTaxAmount($orderBaseTax)
                            ->setGrandTotal($grandTotal)
                            ->setBaseGrandTotal($grandTotal);
                        //$order->collectTotals();
                        echo (string) $invoice->getSelect();
                        $invoice->save();
                    }
                }
            }
        }
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}
if (file_exists($file)) {

    $csvData = $fileCsv->getData($file);

    $accounts_no_array = array();
    $account_sku_price_array = array();
    $product_skus = array();
    $counter = 1;
    foreach ($csvData as $row => $data) {
        if ($counter != 1) {
            $data_array[] = array("order_id"=>$data[0],"item_id" => $data[1],"price" => $data[2]);
        }

        $counter++;
    }

    try
    {
        foreach ($data_array as $order_data)
        {
            //echo $order_data['item_id']; exit;
            updateItemPrice($order_data['item_id'],$order_data['order_id'],$order_data['price'],$objectManager);

            echo $order_data['order_id'] . " ) " . $order_data['order_id'] . "----" . $order_data['item_id'] . "----" . $order_data['price'] . "\n";
        }
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }

    //echo "<pre>";
    //print_r($data_array);
    //exit;
}