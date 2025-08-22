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
			<th style="width:55%;">Producto:</th>
			<th style="width:15%;">Precio:</th>
			<th style="width:15%;">Cantidad:</th>
			<th style="width:15%;">Total:</th>
		</tr>
		<?php foreach($cart as $line=>$item): if($item['print_option'] == PRINT_YES): ?>
		<tr>
			<td><?php echo htmlspecialchars($item['name'] . (isset($item['attribute_values']) ? ' ' . $item['attribute_values'] : '')); ?></td>
			<td><?php echo to_currency($item['price']); ?></td>
			<td><?php echo to_quantity_decimals($item['quantity']); ?></td>
			<td><?php echo to_currency($item[($this->config->item('receipt_show_total_discount') ? 'total' : 'discounted_total')]); ?></td>
		</tr>
		<?php endif; endforeach; ?>
		<tr class="suspended-total-row">
			<td colspan="3" style="text-align:right;">Total a pagar:</td>
			<td><?php echo to_currency($total); ?></td>
		</tr>
	</table>

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
		Todos los gastos están incluidos en el total
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