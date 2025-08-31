
<div id="receipt_wrapper" style="font-size:<?php echo $this->config->item('receipt_font_size');?>px">
	<div id="receipt_header">
		<?php
		if($this->config->item('company_logo') != '')
		{
		?>
			<div id="company_name">
				<img id="image" src="<?php echo base_url('uploads/' . $this->config->item('company_logo')); ?>" alt="company_logo" />
			</div>
		<?php
		}
		?>

		<?php
		if($this->config->item('receipt_show_company_name'))
		{
		?>
			<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<?php
		}
		?>

		<div id="company_address"><?php echo nl2br($this->config->item('address')); ?></div>
		<div id="company_phone"><?php echo $this->config->item('phone'); ?></div>
		<div id="sale_receipt"><?php echo $this->lang->line('sales_receipt'); ?></div>
		<div id="sale_time"><?php echo $transaction_time ?></div>
	</div>

	<div id="receipt_general_info">
		<?php
		if(isset($customer))
		{
		?>
			<div id="customer"><?php echo $this->lang->line('customers_customer').": ".$customer; ?></div>
			<?php if (!empty($customer_phone)) { ?>
				<div id="customer_phone"><strong><?php echo $this->lang->line('common_phone_number'); ?>:</strong> <?php echo htmlspecialchars($customer_phone); ?></div>
			<?php } ?>
		<?php
		}
		?>

		<div id="sale_id"><?php echo $this->lang->line('sales_id').": ".$sale_id; ?></div>

		<?php
		if(!empty($invoice_number))
		{
		?>
			<div id="invoice_number"><?php echo $this->lang->line('sales_invoice_number').": ".$invoice_number; ?></div>
		<?php
		}
		?>

		<div id="employee"><?php echo $this->lang->line('employees_employee').": ".$employee; ?></div>

		<?php if (!empty($delivery_man_name)) { ?>
			<div id="delivery_man"><strong>Delivery Man:</strong> <?php echo htmlspecialchars($delivery_man_name); ?></div>
			<?php if (!empty($delivery_man_phone)) { ?>
				<div id="delivery_man_phone"><strong>Phone:</strong> <?php echo htmlspecialchars($delivery_man_phone); ?></div>
			<?php } ?>
			<?php if (!empty($delivery_man_email)) { ?>
				<div id="delivery_man_email"><strong>Email:</strong> <?php echo htmlspecialchars($delivery_man_email); ?></div>
			<?php } ?>
		<?php } ?>
	</div>

	<table id="receipt_items">
		<tr>
			<th style="width:40%;"><?php echo $this->lang->line('sales_description_abbrv'); ?></th>
			<th style="width:20%;"><?php echo $this->lang->line('sales_price'); ?></th>
			<th style="width:20%;"><?php echo $this->lang->line('sales_quantity'); ?></th>
			<th style="width:20%;" class="total-value"><?php echo $this->lang->line('sales_total'); ?></th>
			<?php
			if($this->config->item('receipt_show_tax_ind'))
			{
			?>
				<th style="width:20%;"></th>
			<?php
			}
			?>
		</tr>
		<?php
		foreach($cart as $line=>$item)
		{
			if($item['print_option'] == PRINT_YES)
			{
			?>
				<tr>
					<td><?php echo ucfirst($item['name'] . ' ' . $item['attribute_values']); ?></td>
					<td><?php echo to_currency($item['price']); ?></td>
					<td><?php echo to_quantity_decimals($item['quantity']); ?></td>
					<td class="total-value"><?php echo to_currency($item[($this->config->item('receipt_show_total_discount') ? 'total' : 'discounted_total')]); ?></td>
					<?php
					if($this->config->item('receipt_show_tax_ind'))
					{
					?>
						<td><?php echo $item['taxed_flag'] ?></td>
					<?php
					}
					?>
				</tr>
				<tr>
					<?php
					if($this->config->item('receipt_show_description'))
					{
					?>
						<td colspan="2"><?php echo $item['description']; ?></td>
					<?php
					}

					if($this->config->item('receipt_show_serialnumber'))
					{
					?>
						<td><?php echo $item['serialnumber']; ?></td>
					<?php
					}
					?>
				</tr>
				<?php
				if($item['discount'] > 0)
				{
				?>
					<tr>
						<?php
						if($item['discount_type'] == FIXED)
						{
						?>
							<td colspan="3" class="discount"><?php echo to_currency($item['discount']) . " " . $this->lang->line("sales_discount") ?></td>
						<?php
						}
						elseif($item['discount_type'] == PERCENT)
						{
						?>
							<td colspan="3" class="discount"><?php echo to_decimals($item['discount']) . " " . $this->lang->line("sales_discount_included") ?></td>
						<?php
						}	
						?>
						<td class="total-value"><?php echo to_currency($item['discounted_total']); ?></td>
					</tr>
				<?php
				}
			}
		}
		?>

		<?php
		if($this->config->item('receipt_show_total_discount') && $discount > 0)
		{
		?>
			<tr>
				<td colspan="3" style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('sales_sub_total'); ?></td>
				<td style='text-align:right;border-top:2px solid #000000;'><?php echo to_currency($prediscount_subtotal); ?></td>
			</tr>
			<tr>
				<td colspan="3" class="total-value"><?php echo $this->lang->line('sales_customer_discount'); ?>:</td>
				<td class="total-value"><?php echo to_currency($discount * -1); ?></td>
			</tr>
		<?php
		}
		?>

		<?php
		if($this->config->item('receipt_show_taxes'))
		{
		?>
			<tr>
				<td colspan="3" style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('sales_sub_total'); ?></td>
				<td style='text-align:right;border-top:2px solid #000000;'><?php echo to_currency($subtotal); ?></td>
			</tr>
			<?php
			foreach($taxes as $tax_group_index=>$tax)
			{
			?>
				<tr>
					<td colspan="3" class="total-value">
					<?php 
						if(isset($tax['tax_type']) && $tax['tax_type'] == '1') {
							// Fixed amount tax
							echo $tax['tax_group']; 
						} else {
							// Percentage tax
							echo $tax['tax_group'] . ' (' . (float)$tax['tax_rate'] . '%)'; 
						}
					?>:
					</td>
					<td class="total-value"><?php echo to_currency_tax($tax['sale_tax_amount']); ?></td>
				</tr>
			<?php
			}
			?>
		<?php
		}
		?>

		<tr>
		</tr>

		<?php $border = (!$this->config->item('receipt_show_taxes') && !($this->config->item('receipt_show_total_discount') && $discount > 0)); ?>
		<tr>
			<td colspan="3" style="text-align:right;<?php echo $border? 'border-top: 2px solid black;' :''; ?>"><?php echo $this->lang->line('sales_total'); ?></td>
			<td style="text-align:right;<?php echo $border? 'border-top: 2px solid black;' :''; ?>"><?php echo to_currency($total); ?></td>
		</tr>

		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>

		<?php
		$only_sale_check = FALSE;
		$show_giftcard_remainder = FALSE;
		foreach($payments as $payment_id=>$payment)
		{
			$only_sale_check |= $payment['payment_type'] == $this->lang->line('sales_check');
			$splitpayment = explode(':', $payment['payment_type']);
			$show_giftcard_remainder |= $splitpayment[0] == $this->lang->line('sales_giftcard');
		?>
			<tr>
				<td class="total-line" style="white-space:nowrap;"><small><?php
					if (!empty($payment['payment_time'])) {
						echo date('Y-m-d H:i', strtotime($payment['payment_time']));
					} else {
						echo isset($transaction_time) ? $transaction_time : '';
					}
				?></small></td>
				<td class="total-line" style="white-space:nowrap;"><?php echo $splitpayment[0]; ?> </td>
				<td></td>
				<td class="total-value"><?php echo to_currency( $payment['payment_amount'] * -1 ); ?></td>
			</tr>
		<?php
		}
		?>

		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>

		<?php
		if(isset($cur_giftcard_value) && $show_giftcard_remainder)
		{
		?>
			<tr>
				<td colspan="3" style="text-align:right;"><?php echo $this->lang->line('sales_giftcard_balance'); ?></td>
				<td class="total-value"><?php echo to_currency($cur_giftcard_value); ?></td>
			</tr>
		<?php
		}
		
		// Calculate amount due directly in the receipt template
		$payments_total = 0;
		foreach($payments as $payment_id => $payment) {
			if(!isset($payment['cash_adjustment']) || !$payment['cash_adjustment']) {
				$payments_total += $payment['payment_amount'];
			}
		}
		
		$amount_due = $total - $payments_total;
		$precision = totals_decimals();
		$rounded_due = round($amount_due, $precision);
		$tolerance = pow(10, -$precision) / 2;
		
		// If amount due is very close to 0, set it to 0
		if (abs($rounded_due) <= $tolerance) {
			$amount_due = 0.0;
		}
		?>
		<tr>
			<td colspan="3" style="text-align:right;"> <?php echo $this->lang->line($amount_due >= 0 ? ($only_sale_check ? 'sales_check_balance' : 'sales_change_due') : 'sales_amount_due') ; ?> </td>
			<td class="total-value"><?php echo to_currency($amount_due); ?></td>
		</tr>
	</table>
	<?php if (!empty($comments)): ?>
	<div style="margin-top: 10px; padding: 6px; border-top: 1px dashed #888;">
		<strong>Comment:</strong><br>
		<?= nl2br(htmlspecialchars($comments)) ?>
	</div>
<?php endif; ?>


	<div id="sale_return_policy">
		<?php echo nl2br($this->config->item('return_policy')); ?>
	</div>

	<!-- Signatures -->
	<div style="margin-top:20px;">
		<table style="width:100%;">
			<tr>
				<td style="text-align:left;white-space:nowrap;">cliente ____________________</td>
				<td style="text-align:center;white-space:nowrap;">repartidor ____________________</td>
				<td style="text-align:right;white-space:nowrap;">pagado ____________________</td>
			</tr>
		</table>
	</div>

	<div id="barcode">
		<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br>
		<?php echo $sale_id; ?>
	</div>
</div>
