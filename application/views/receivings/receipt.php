<?php $this->load->view("partial/header"); ?>

<?php
	if (isset($error_message))
	{
		echo "<div class='alert alert-dismissible alert-danger'>".$error_message."</div>";
		exit;
	}

    $this->load->view('partial/print_receipt', array('print_after_sale' => $print_after_sale, 'selected_printer'=>'receipt_printer')); 

?>

<div class="print_hide" id="control_buttons" style="text-align:right">
	<a href="javascript:printdoc();"><div class="btn btn-info btn-sm", id="show_print_button"><?php echo '<span class="glyphicon glyphicon-print">&nbsp</span>' . $this->lang->line('common_print'); ?></div></a>
	<?php echo anchor("receivings", '<span class="glyphicon glyphicon-save">&nbsp</span>' . $this->lang->line('receivings_register'), array('class'=>'btn btn-info btn-sm', 'id'=>'show_sales_button')); ?>
</div>

<div id="receipt_wrapper">
	<div id="receipt_header">
		<?php
		if ($this->config->item('company_logo') != '') 
		{ 
		?>
			<div id="company_name"><img id="image" src="<?php echo base_url('uploads/' . $this->config->item('company_logo')); ?>" alt="company_logo" /></div>
		<?php
		}
		?>

		<?php
		if ($this->config->item('receipt_show_company_name')) 
		{ 
		?>
			<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<?php
		}
		?>

		<div id="company_address"><?php echo nl2br($this->config->item('address')); ?></div>
		<div id="company_phone"><?php echo $this->config->item('phone'); ?></div>
		<div id="sale_receipt"><?php echo $this->lang->line('receivings_receipt'); ?></div>
		<div id="sale_time"><?php echo $transaction_time ?></div>
	</div>

	<div id="receipt_general_info">
		<?php
		if(isset($supplier))
		{
		?>
			<div id="customer"><?php echo $this->lang->line('suppliers_supplier').": ".$supplier; ?></div>
		<?php
		}
		?>
		<div id="sale_id"><?php echo $this->lang->line('receivings_id').": ".$receiving_id; ?></div>
		<?php 
		if (!empty($reference))
		{
		?>
			<div id="reference"><?php echo $this->lang->line('receivings_reference').": ".$reference; ?></div>	
		<?php 
		}
		?>
		<div id="employee"><?php echo $this->lang->line('employees_employee').": ".$employee; ?></div>
	</div>

	<table id="receipt_items">
		<tr>
			<th style="width:40%;"><?php echo $this->lang->line('items_item'); ?></th>
			<th style="width:20%;"><?php echo $this->lang->line('common_price'); ?></th>
			<th style="width:20%;"><?php echo $this->lang->line('sales_quantity'); ?></th>
			<th style="width:15%;text-align:right;"><?php echo $this->lang->line('sales_total'); ?></th>
		</tr>

		<?php
        // Accumulators for summary
        $sum_discount = 0; 
        $sum_tax = 0;
        $subtotal = 0;

        foreach(array_reverse($cart, TRUE) as $line=>$item)
		{
            // Compute discount and tax amounts for summary
            $extended = $item['price'] * $item['quantity'] * ($item['receiving_quantity'] != 0 ? $item['receiving_quantity'] : 1);
            $discount_amount = ($item['discount_type'] == PERCENT) ? ($extended * $item['discount'] / 100) : $item['discount'];
            $after_discount = $extended - $discount_amount;
            $tax_val = isset($item['tax']) ? $item['tax'] : 0;
            $tax_type_val = isset($item['tax_type']) ? $item['tax_type'] : 0;
            $tax_amount = ($tax_type_val == PERCENT) ? ($after_discount * $tax_val / 100) : $tax_val;
            $sum_discount += max(0, $discount_amount);
            $sum_tax += max(0, $tax_amount);
            $subtotal += $after_discount;
		?>
			<tr>
				<td><?php echo $item['name'] . ' ' . $item['attribute_values']; ?></td>
				<td><?php echo to_currency($item['price']); ?></td>
				<td><?php echo to_quantity_decimals($item['quantity']) . " " . ($show_stock_locations ? " [" . $item['stock_name'] . "]" : ""); 
				?>&nbsp;&nbsp;&nbsp;x <?php echo $item['receiving_quantity'] != 0 ? to_quantity_decimals($item['receiving_quantity']) : 1; ?></td>
				<td><div class="total-value"><?php echo to_currency($item['total']); ?></div></td>
			</tr>
			<tr>
				<td ><?php echo $item['serialnumber']; ?></td>
			</tr>
			<?php
            // Discount line
            if (isset($item['discount']) && floatval($item['discount']) > 0)
            {
            ?>
                <tr>
                    <?php if(isset($item['discount_type']) && $item['discount_type'] == FIXED) { ?>
                        <td colspan="3" class="discount"><?php echo to_currency($item['discount']) . " Discount"; ?></td>
                    <?php } else { ?>
                        <td colspan="3" class="discount"><?php echo to_decimals($item['discount']) . "% Discount"; ?></td>
                    <?php } ?>
                </tr>
            <?php }

            // Tax line
            if (isset($item['tax']) && floatval($item['tax']) > 0)
            {
            ?>
                <tr>
                    <?php if(isset($item['tax_type']) && $item['tax_type'] == FIXED) { ?>
                        <td colspan="3" class="discount"><?php echo to_currency($item['tax']) . " Tax"; ?></td>
                    <?php } else { ?>
                        <td colspan="3" class="discount"><?php echo to_decimals($item['tax']) . "% Tax"; ?></td>
                    <?php } ?>
                </tr>
            <?php }
			?>
		<?php
		}
		?>	
        <?php if($sum_discount > 0) { ?>
        <tr>
            <td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_discount'); ?></td>
            <td><div class="total-value"><?php echo to_currency($sum_discount); ?></div></td>
        </tr>
        <?php } ?>
        
        <?php if($sum_tax > 0) { ?>
        <tr>
            <td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_tax'); ?></td>
            <td><div class="total-value"><?php echo to_currency($sum_tax); ?></div></td>
        </tr>
        <?php } ?>
        
        <tr>
            <td colspan="3" style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('sales_total'); ?></td>
            <td style='border-top:2px solid #000000;'><div class="total-value"><?php echo to_currency($total); ?></div></td>
        </tr>
        
        <?php
        // Show payment history and remaining balance
        if(isset($receiving_id) && !empty($receiving_id))
        {
            $receiving_id_num = str_replace('RECV ', '', $receiving_id);
            $CI =& get_instance();
            $CI->load->model('Receiving');
            $payments = $CI->Receiving->get_receiving_payments($receiving_id_num);
            
            // Debug: Show what payments were found
            if(ENVIRONMENT === 'development') {
                echo "<!-- Debug: Found " . ($payments ? $payments->num_rows() : 0) . " payments for receiving " . $receiving_id_num . " -->";
                if($payments && $payments->num_rows() > 0) {
                    foreach($payments->result() as $payment) {
                        echo "<!-- Debug: Payment - Type: " . $payment->payment_type . ", Amount: " . $payment->payment_amount . " -->";
                    }
                }
            }
            
            if($payments && $payments->num_rows() > 0)
            {
                $total_paid = 0;
                
                // Show individual payment methods
                foreach($payments->result() as $payment)
                {
                    $total_paid += $payment->payment_amount;
                ?>
                    <tr>
                        <td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_payment'); ?> (<?php echo $payment->payment_type; ?>)</td>
                        <td><div class="total-value"><?php echo to_currency($payment->payment_amount); ?></div></td>
                    </tr>
                <?php
                }
                
                $remaining_balance = $total - $total_paid;
                
                // Show total paid
                if($total_paid > 0)
                {
                ?>
                    <tr>
                        <td colspan="3" style='text-align:right;border-top:1px solid #000000;'><?php echo $this->lang->line('sales_amount_paid'); ?></td>
                        <td style='border-top:1px solid #000000;'><div class="total-value"><?php echo to_currency($total_paid); ?></div></td>
                    </tr>
                <?php
                }
                
                // Show remaining balance
                if($remaining_balance > 0)
                {
                ?>
                    <tr>
                        <td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_amount_due'); ?></td>
                        <td><div class="total-value"><?php echo to_currency($remaining_balance); ?></div></td>
                    </tr>
                <?php
                }
                else
                {
                ?>
                    <tr>
                        <td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_amount_due'); ?></td>
                        <td><div class="total-value"><?php echo to_currency(0); ?></div></td>
                    </tr>
                <?php
                }
            }
        }
        ?>
		<?php 
		if($mode!='requisition')
		{
		?>
			<?php if(isset($amount_change))
			{
			?>
				<tr>
					<td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_amount_tendered'); ?></td>
					<td><div class="total-value"><?php echo to_currency($amount_tendered); ?></div></td>
				</tr>

				<tr>
					<td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_change_due'); ?></td>
					<td><div class="total-value"><?php echo $amount_change; ?></div></td>
				</tr>
			<?php
			}
			?>
		<?php 
		}
		?>
	</table>

	<div id="sale_return_policy">
		<?php echo nl2br($this->config->item('return_policy')); ?>
	</div>

	<div id='barcode'>
		<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br>
		<?php echo $receiving_id; ?>
	</div>
</div>
<?php $this->load->view("partial/footer"); ?>
