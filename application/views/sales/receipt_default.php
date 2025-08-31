<?php /*
 * Default Receipt Template - Spanish/European style with tax breakdown
 */
?>
<?php 
if (isset($print_after_sale) === FALSE) { $print_after_sale = FALSE; }
$this->load->view('partial/print_receipt', array('print_after_sale' => $print_after_sale, 'selected_printer' => 'receipt_printer')); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.title = '';
  if (window.jsPrintSetup) {
    try {
      jsPrintSetup.setOption('headerStrLeft', '');
      jsPrintSetup.setOption('headerStrCenter', '');
      jsPrintSetup.setOption('headerStrRight', '');
      jsPrintSetup.setOption('footerStrLeft', '');
      jsPrintSetup.setOption('footerStrCenter', '');
      jsPrintSetup.setOption('footerStrRight', '');
    } catch (e) {}
  }
});
</script>
<style>
.suspended-header-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
.suspended-header-table td, .suspended-header-table th { border: 1px solid #333; padding: 4px 8px; vertical-align: top; }
.suspended-header-table th { background: #f4f4f4; text-align: left; }
.suspended-info-block { font-size: 13px; line-height: 1.4; margin-bottom: 6px; }
.suspended-items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.suspended-items-table th, .suspended-items-table td { border: 1px solid #333; padding: 6px 8px; }
.suspended-items-table th { background: #f4f4f4; }
.suspended-total-row td { font-size: 1.5em; font-weight: bold; text-align: right; border-top: 2px solid #000; }
.suspended-footer-note { margin-top: 18px; font-size: 13px; }
.suspended-employee { margin-top: 12px; font-size: 13px; }
.tax-breakdown-table { width: 40%; border-collapse: collapse; margin-top: 10px; }
.tax-breakdown-table th, .tax-breakdown-table td { border: none; padding: 4px 8px; text-align: left; }
.tax-breakdown-table th { font-weight: normal; }
.tax-breakdown-table .tax-label { width: 60%; }
.tax-breakdown-table .tax-amount { width: 40%; text-align: right; }
</style>

<div id="receipt_wrapper" style="font-size:<?php echo $this->config->item('receipt_font_size');?>px; max-width: 900px; margin: auto;">
	<!-- Header: Receipt and Client -->
	<table class="suspended-header-table">
		<tr>
			<th style="width:50%;">Recibo:</th>
			<th style="width:50%;">Cliente:</th>
		</tr>
		<tr>
			<td>
				<div class="suspended-info-block">
					Num: <?php echo $sale_id; ?><br>
					Fecha: <?php echo $transaction_time; ?><br>
					<?php if($this->config->item('company')) echo htmlspecialchars($this->config->item('company')) . '<br>';
					if($this->config->item('vat_number')){
						echo 'VAT: ' . htmlspecialchars($this->config->item('vat_number')) . '<br>';
					}
					if($this->config->item('address')) echo nl2br(htmlspecialchars($this->config->item('address'))) . '<br>';
					if($this->config->item('phone')) echo htmlspecialchars($this->config->item('phone')) . '<br>';
					if($this->config->item('email')) echo htmlspecialchars($this->config->item('email'));
					?>
				</div>
			</td>
			<td>
				<div class="suspended-info-block">
					<?php if(isset($customer) && !empty($customer)) echo htmlspecialchars($customer) . '<br>';
					if(isset($customer_phone) && !empty($customer_phone)) echo htmlspecialchars($customer_phone) . '<br>';
					if(isset($customer_address) && !empty($customer_address)) echo htmlspecialchars($customer_address) . '<br>';
					if(isset($customer_location) && !empty($customer_location)) echo htmlspecialchars($customer_location) . '<br>';
					if(isset($customer_email) && !empty($customer_email)) echo htmlspecialchars($customer_email);
					?>
				</div>
			</td>
		</tr>
	</table>

	<!-- Items Table -->
	<table class="suspended-items-table">
		<tr>
			<th style="width:28%;">Producto:</th>
			<th style="width:8%;">Cant.:</th>
			<th style="width:14%;">Precio:</th>
			<th style="width:10%;">Dto.:</th>
			<th style="width:14%;">SubTotal:</th>
			<th style="width:12%;">Imp.:</th>
			<th style="width:14%;">Total:</th>
		</tr>
		<?php
		$subtotal_sum = 0;
		$tax_sum = 0;
		// Helper for per-line tax calculation (mimics Sale_lib::get_item_tax logic)
		function calc_line_tax_and_subtotal($item, $CI) {
			$tax_info = $CI->Item_taxes->get_info($item['item_id']);
			$quantity = $item['quantity'];
			$unitPrice = $item['price'];
			$discount = $item['discount'];
			$discount_type = $item['discount_type'];
			$item_total = bcmul($quantity, $unitPrice);
			if ($discount_type == 1) { // percent
				$discount_amount = bcmul($item_total, bcdiv($discount, 100));
			} else {
				$discount_amount = bcmul($quantity, $discount);
			}
			$item_total = bcsub($item_total, $discount_amount);
			$has_fixed = false;
			$fixed_tax = 0.0;
			$fixed_base = 0.0;
			$percent_tax = 0.0;
			foreach($tax_info as $tax) {
				$tax_type = isset($tax['tax_type']) ? $tax['tax_type'] : 0;
				$tax_percent = $tax['percent'];
				if ($tax_type == 1) {
					// Fixed tax: extract base price and tax from unit price
					$has_fixed = true;
					$basePrice = $unitPrice / (1 + ($tax_percent / 100));
					$fixed_base = $basePrice * $quantity;
					$fixed_tax = ($unitPrice - $basePrice) * $quantity;
					break; // Only one fixed tax per item supported
				}
			}
			if ($has_fixed) {
				return [round($fixed_base, 2), round($fixed_tax, 2), round($fixed_base + $fixed_tax, 2)];
			} else {
				// Sum all percentage taxes
				foreach($tax_info as $tax) {
					$tax_percent = $tax['percent'];
					$percent_tax += $item_total * ($tax_percent / 100);
				}
				return [round($item_total, 2), round($percent_tax, 2), round($item_total + $percent_tax, 2)];
			}
		}
		$CI =& get_instance();
		?>
		<?php
		foreach($cart as $line=>$item): if($item['print_option'] == PRINT_YES):
			$discount = isset($item['discount']) ? $item['discount'] : 0;
			$discount_type = isset($item['discount_type']) ? $item['discount_type'] : 0;
			$discount_display = $discount > 0 
     			? ($discount_type == 1 
     			    ? to_currency($discount)   
     			    : to_decimals($discount) . '%' 
     			  ) 
     			: '-';
			
			// Create a clean copy of item data without discounts for tax calculation
			$clean_item = $item;
			$clean_item['discount'] = 0;
			$clean_item['discount_type'] = 0;
			
			// Get the original tax calculation (without discounts)
			list($line_subtotal, $line_tax, $line_total) = calc_line_tax_and_subtotal($clean_item, $CI);
			
			// Check if this is a tax-inclusive item
			$item_tax_info = $CI->Item_taxes->get_info($item['item_id']);
			$has_tax_inclusive = false;
			foreach($item_tax_info as $item_tax) {
				if (isset($item_tax['tax_type']) && $item_tax['tax_type'] == 1) {
					$has_tax_inclusive = true;
					break;
				}
			}
			
			// Calculate discount amount and apply it
			$discount_amount = 0;
			if ($discount > 0) {
				if ($discount_type == 1) { // Fixed amount discount
					$discount_amount = bcmul($item['quantity'], $discount);
				} else { // Percentage discount
					// Apply discount to total (base + tax)
					$discount_amount = bcmul($line_total, bcdiv($discount, 100));
				}
			}
			
			// Apply discount to total
			$line_total = bcsub($line_total, $discount_amount);
			
			$subtotal_sum += $line_subtotal;
			$tax_sum += $line_tax;
		?>
		<tr>
			<td><?php echo htmlspecialchars($item['name'] . (isset($item['attribute_values']) ? ' ' . $item['attribute_values'] : '')); ?></td>
			<td style="text-align:center;"><?php echo to_quantity_decimals($item['quantity']); ?></td>
			<td><?php echo to_currency($item['price']); ?></td>
			<td style="text-align:center;"><?php echo $discount_display; ?></td>
			<td><?php echo to_currency($line_subtotal); ?></td>
			<td><?php echo to_currency($line_tax); ?></td>
			<td><?php echo to_currency($line_total); ?></td>
		</tr>
		<?php endif; endforeach; ?>
		
		<?php
		// Calculate total discount amount
		$total_discount = 0;
		foreach($cart as $line=>$item) {
			if($item['print_option'] == PRINT_YES) {
				$discount = isset($item['discount']) ? $item['discount'] : 0;
				$discount_type = isset($item['discount_type']) ? $item['discount_type'] : 0;
				
				if ($discount > 0) {
					if ($discount_type == 1) { // Fixed amount discount
						$total_discount = bcadd($total_discount, bcmul($item['quantity'], $discount));
					} else { // Percentage discount
						// Get original total for this item to calculate discount
						$clean_item = $item;
						$clean_item['discount'] = 0;
						$clean_item['discount_type'] = 0;
						list($item_subtotal, $item_tax, $item_total) = calc_line_tax_and_subtotal($clean_item, $CI);
						$discount_amount = bcmul($item_total, bcdiv($discount, 100));
						$total_discount = bcadd($total_discount, $discount_amount);
					}
				}
			}
		}
		?>
		
		<tr>
			<td colspan="3" style="text-align:right;"><strong>SubTotal:</strong></td>
			<td style="text-align:center;"><?php echo to_currency($total_discount); ?></td>
			<td style="text-align:left;"><?php echo to_currency($subtotal_sum); ?></td>
			<td style="text-align:left;"><?php echo to_currency($tax_sum); ?></td>
			<td style="text-align:left;"><?php echo to_currency($total); ?></td>
		</tr>
	</table>

	<div class="suspended-total-row" style="margin-top:10px;">
		<span style="float:right;">Total a pagar: <span style="font-size:1.2em;"><strong><?php echo to_currency($total); ?></strong></span></span>
	</div>

	<!-- Tax Breakdown -->
	<?php if (!empty($taxes)): ?>
	<div style="margin-top: 30px;">
		<strong>Desglose de Impuestos:</strong>
		<table class="tax-breakdown-table">
			<?php 
			// Calculate individual tax amounts for each tax rate
			$tax_breakdown = array();
			
			// Initialize tax amounts for each tax rate
			foreach($taxes as $tax) {
				$tax_rate = isset($tax['tax_rate']) ? (float)$tax['tax_rate'] : 0;
				$tax_breakdown[$tax_rate] = array(
					'tax_group' => $tax['tax_group'],
					'tax_rate' => $tax_rate,
					'tax_amount' => 0
				);
			}
			
			// Calculate tax amounts for each item based on their actual tax rates
			foreach($cart as $line=>$item) {
				if($item['print_option'] == PRINT_YES) {
					// Get the tax info for this specific item
					$item_tax_info = $CI->Item_taxes->get_info($item['item_id']);
					
					foreach($item_tax_info as $item_tax) {
						$item_tax_rate = (float)$item_tax['percent'];
						
						// Find the matching tax rate in our breakdown
						if (isset($tax_breakdown[$item_tax_rate])) {
							// Calculate tax on the ORIGINAL price (before discount)
							$original_item_total = bcmul($item['quantity'], $item['price']);
							
							// Check if this item has tax-inclusive pricing
							$tax_type = isset($item_tax['tax_type']) ? $item_tax['tax_type'] : 0;
							
							if ($tax_type == 1) {
								$base_price = bcdiv($original_item_total, bcadd(1, bcdiv($item_tax_rate, 100)));
								$tax_amount = bcsub($original_item_total, $base_price);
							} else {
								// Regular pricing: calculate tax on base price
								$tax_amount = bcmul($original_item_total, bcdiv($item_tax_rate, 100));
							}
							
							// Add the tax amount for this item at this rate
							$tax_breakdown[$item_tax_rate]['tax_amount'] = bcadd($tax_breakdown[$item_tax_rate]['tax_amount'], $tax_amount);
						}
					}
				}
			}
			
			// Display the tax breakdown
			foreach($tax_breakdown as $tax): ?>
			<tr>
				<td class="tax-label"><?php echo htmlspecialchars($tax['tax_group']); ?> <?php echo $tax['tax_rate']; ?>%</td>
				<td class="tax-amount"><?php echo to_currency($tax['tax_amount']); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php endif; ?>

	<!-- Payments Section -->
	<?php if (!empty($payments)): ?>
	<div style="margin-top: 20px;">
		<strong>Pagos Realizados:</strong>
		<table class="suspended-items-table" style="margin-top: 10px;">
			<tr>
				<th style="width:30%;">Fecha y Hora</th>
				<th style="width:40%;">Método de Pago</th>
				<th style="width:30%;">Monto</th>
			</tr>
			<?php 
			$payments_total = 0;
			foreach($payments as $payment_id=>$payment): 
				$payments_total += $payment['payment_amount'];
			?>
			<tr>
				<td style="text-align:center;"><?php echo date('Y-m-d H:i', strtotime($payment['payment_time'])); ?></td>
				<td style="text-align:center;"><?php echo $payment['payment_type']; ?></td>
				<td style="text-align:right;"><?php echo to_currency($payment['payment_amount']); ?></td>
			</tr>
			<?php endforeach; ?>
			<tr style="background-color: #f8f9fa;">
				<td colspan="2" style="text-align:right;"><strong>Total Pagado:</strong></td>
				<td style="text-align:right;"><strong><?php echo to_currency($payments_total); ?></strong></td>
			</tr>
		</table>
	</div>
	<?php endif; ?>

	<!-- Amount Due/Change -->
	<?php if (!empty($payments)): ?>
	<div style="margin-top: 20px; text-align: right; font-size: 1.2em;">
		<?php
		$amount_due = $total - $payments_total;
		$precision = totals_decimals();
		$rounded_due = round($amount_due, $precision);
		$tolerance = pow(10, -$precision) / 2;
		
		// If amount due is very close to 0, set it to 0
		if (abs($rounded_due) <= $tolerance) {
			$amount_due = 0.0;
		}
		?>
		<strong>
			<?php if ($amount_due > 0): ?>
				Monto Pendiente: <?php echo to_currency($amount_due); ?>
			<?php elseif ($amount_due < 0): ?>
				Cambio: <?php echo to_currency(abs($amount_due)); ?>
			<?php else: ?>
				Pagado Completo
			<?php endif; ?>
		</strong>
	</div>
	<?php endif; ?>

	<!-- Previous Unpaid Amount Section -->
	<?php if(isset($customer_id) && !empty($customer_id)): ?>
		<?php
		// Get customer's total unpaid amount from all previous invoices
		$CI =& get_instance();
		$CI->load->model('Customer');
		$customer_stats = $CI->Customer->get_stats($customer_id);
		$previous_unpaid_amount = isset($customer_stats->amount_due) ? $customer_stats->amount_due : 0;
		?>
		<?php if($previous_unpaid_amount > 0): ?>
		<div style="margin: 15px 0; padding: 10px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;">
			<div style="text-align: center; font-weight: bold; color: #856404; font-size: 14px;">
				⚠️ Monto Pendiente Anterior: <?php echo to_currency($previous_unpaid_amount); ?>
			</div>
			<div style="text-align: center; font-size: 12px; color: #856404; margin-top: 5px;">
				Este monto representa el saldo pendiente de facturas anteriores
			</div>
		</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="suspended-employee">
		Empleado: <?php echo htmlspecialchars($employee); ?>
		<?php if (!empty($delivery_man_name)) { ?>
			<br>Repartidor: <?php echo htmlspecialchars($delivery_man_name); ?>
			<?php if (!empty($delivery_man_phone)) { ?>
				<br>Teléfono: <?php echo htmlspecialchars($delivery_man_phone); ?>
			<?php } ?>
			<?php if (!empty($delivery_man_email)) { ?>
				<br>Email: <?php echo htmlspecialchars($delivery_man_email); ?>
			<?php } ?>
		<?php } ?>
	</div>

	<div class="suspended-return-policy" style="margin-top: 15px; padding: 8px; border: 1px solid #ccc; font-size: 12px;">
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

	<?php if (!empty($comments)): ?>
	<div style="margin-top: 10px; padding: 6px; border-top: 1px dashed #888;">
		<strong>Comentario:</strong><br>
		<?= nl2br(htmlspecialchars($comments)) ?>
	</div>
	<?php endif; ?>

	<div id="barcode" style="margin-top:18px;">
		<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br>
		<?php echo $sale_id; ?>
	</div>
</div>
