<?php /*
 * Suspended Receipt Template (No Tax) - Formatted to match provided sample
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
</style>
<div id="receipt_wrapper" style="font-size:<?php echo $this->config->item('receipt_font_size');?>px; max-width: 800px; margin: auto;">
	<!-- Header: Delivery Note and Client -->
	<table class="suspended-header-table">
		<tr>
			<th style="width:50%;">Albarán de entrega:</th>
			<th style="width:50%;">Cliente:</th>
		</tr>
		<tr>
			<td>
				<div class="suspended-info-block">
					Num.: <?php echo $sale_id; ?><br>
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
					if(isset($customer_address) && !empty($customer_address)) echo htmlspecialchars($customer_address) . '<br>';
					if(isset($customer_location) && !empty($customer_location)) echo htmlspecialchars($customer_location) . '<br>';
					if(isset($customer_phone) && !empty($customer_phone)) echo htmlspecialchars($customer_phone) . '<br>';
					if(isset($customer_email) && !empty($customer_email)) echo htmlspecialchars($customer_email);
					?>
				</div>
			</td>
		</tr>
	</table>

	<!-- Items Table -->
	<table class="suspended-items-table">
		<tr>
			<th style="width:40%;">Producto:</th>
			<th style="width:12%;">Precio:</th>
			<th style="width:12%;">Dto.:</th>
			<th style="width:12%;">Cantidad:</th>
			<th style="width:12%;">Total:</th>
		</tr>
		<?php 
		$subtotal_sum = 0;
		foreach($cart as $line=>$item): if($item['print_option'] == PRINT_YES): 
			$discount = isset($item['discount']) ? $item['discount'] : 0;
			$discount_type = isset($item['discount_type']) ? $item['discount_type'] : 0;
			$discount_display = $discount > 0 
				? ($discount_type == 1 
					? to_currency($discount)   
					: to_decimals($discount) . '%' 
				  ) 
				: '-';
			
			// Calculate item total with discount
			$item_total = $item['quantity'] * $item['price'];
			$discount_amount = 0;
			if ($discount > 0) {
				if ($discount_type == 1) { // fixed amount
					$discount_amount = $discount * $item['quantity'];
					
				} else { // percent
					$discount_amount = $item_total * ($discount / 100);
				}
			}
			$item_total_after_discount = $item_total - $discount_amount;
			$subtotal_sum += $item_total_after_discount;
		?>
		<tr>
			<td><?php echo htmlspecialchars($item['name'] . (isset($item['attribute_values']) ? ' ' . $item['attribute_values'] : '')); ?></td>
			<td><?php echo to_currency($item['price']); ?></td>
			<td style="text-align:center;"><?php echo $discount_display; ?></td>
			<td><?php echo to_quantity_decimals($item['quantity']); ?></td>
			<td><?php echo to_currency($item_total_after_discount); ?></td>
		</tr>
		<?php endif; endforeach; ?>
		<tr class="suspended-total-row">
			<td colspan="4" style="text-align:right;">Total a pagar:</td>
			<td><?php echo to_currency($total); ?></td>
		</tr>
	</table>

	<div class="suspended-employee">
		Empleado: <?php echo htmlspecialchars($employee); ?>
		<?php if (!empty($delivery_man_name)) { ?>
			<br>Repartidor: <?php echo htmlspecialchars($delivery_man_name); ?>
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