<?php /*
 * Suspended Receipt Template (With Tax) - Spanish/European style with tax breakdown
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
	<!-- Header: Delivery Note and Client -->
	<table class="suspended-header-table">
		<tr>
			<th style="width:50%;">Albarán:</th>
			<th style="width:50%;">Cliente:</th>
		</tr>
		<tr>
			<td>
				<div class="suspended-info-block">
					Num.: <?php echo $sale_id; ?><br>
					Fecha: <?php echo $transaction_date . ' ' . $transaction_time; ?><br>
					<?php if($this->config->item('company')) echo htmlspecialchars($this->config->item('company')) . '<br>';
					if($this->config->item('address')) echo nl2br(htmlspecialchars($this->config->item('address'))) . '<br>';
					if($this->config->item('phone')) echo htmlspecialchars($this->config->item('phone')) . '<br>';
					if($this->config->item('email')) echo htmlspecialchars($this->config->item('email'));
					?>
				</div>
			</td>
			<td>
				<div class="suspended-info-block">
					<?php if(isset($customer)) echo htmlspecialchars($customer) . '<br>';
					if(isset($customer_address)) echo htmlspecialchars($customer_address) . '<br>';
					if(isset($customer_location)) echo htmlspecialchars($customer_location) . '<br>';
					if(isset($customer_phone) && $customer_phone) echo htmlspecialchars($customer_phone) . '<br>';
					if(isset($customer_email) && $customer_email) echo htmlspecialchars($customer_email);
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
			$discount_display = $discount > 0 ? ($discount_type == 1 ? to_decimals($discount) . '%' : to_currency($discount)) : '0%';
			list($line_subtotal, $line_tax, $line_total) = calc_line_tax_and_subtotal($item, $CI);
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
		<tr>
			<td colspan="4" style="text-align:right;"><strong>SubTotal:</strong></td>
			<td><?php echo to_currency($subtotal_sum); ?></td>
			<td><?php echo to_currency($tax_sum); ?></td>
			<td><?php echo to_currency($total); ?></td>
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
			<?php foreach($taxes as $tax): ?>
			<tr>
				<td class="tax-label"><?php echo htmlspecialchars($tax['tax_group']); ?> <?php echo isset($tax['tax_rate']) ? (is_numeric($tax['tax_rate']) ? (float)$tax['tax_rate'] . '%' : $tax['tax_rate']) : ''; ?></td>
				<td class="tax-amount"><?php echo to_currency($tax['sale_tax_amount']); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php endif; ?>

	<div class="suspended-employee">
		Empleado: <?php echo htmlspecialchars($employee); ?>
		<?php if (!empty($delivery_man_name)) { ?>
			<br>Repartidor: <?php echo htmlspecialchars($delivery_man_name); ?>
			<?php if (!empty($delivery_man_phone)) { ?>
				<br>Tel: <?php echo htmlspecialchars($delivery_man_phone); ?>
			<?php } ?>
			<?php if (!empty($delivery_man_email)) { ?>
				<br>Email: <?php echo htmlspecialchars($delivery_man_email); ?>
			<?php } ?>
		<?php } ?>
	</div>

	<div class="suspended-footer-note">
		Todos los gastos e impuestos están incluidos en el total
	</div>

	<!-- Signatures -->
	<div style="margin-top:20px;">
		<table style="width:100%;">
			<tr>
				<td style="text-align:left;white-space:nowrap;">customer ____________________</td>
				<td style="text-align:center;white-space:nowrap;">delivery man ____________________</td>
				<td style="text-align:right;white-space:nowrap;">paid ____________________</td>
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