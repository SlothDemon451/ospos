<?php $this->load->view("partial/header"); ?>

<?php
if(isset($error))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error."</div>";
}

if(!empty($warning))
{
	echo "<div class='alert alert-dismissible alert-warning'>".$warning."</div>";
}

if(isset($success))
{
	echo "<div class='alert alert-dismissible alert-success'>".$success."</div>";
}
?>

<div id="register_wrapper">

<!-- Top register controls -->

	<?php echo form_open($controller_name."/change_mode", array('id'=>'mode_form', 'class'=>'form-horizontal panel panel-default')); ?>
		<div class="panel-body form-group">
			<ul>
				<li class="pull-left first_li">
					<label class="control-label"><?php echo $this->lang->line('sales_mode'); ?></label>
				</li>
				<li class="pull-left">
					<?php echo form_dropdown('mode', $modes, $mode, array('onchange'=>"$('#mode_form').submit();", 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
				</li>
				<?php
				if($this->config->item('dinner_table_enable') == TRUE)
				{
				?>
					<li class="pull-left first_li">
						<label class="control-label"><?php echo $this->lang->line('sales_table'); ?></label>
					</li>
					<li class="pull-left">
						<?php echo form_dropdown('dinner_table', $empty_tables, $selected_table, array('onchange'=>"$('#mode_form').submit();", 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
					</li>
				<?php
				}
				if(count($stock_locations) > 1)
				{
				?>
					<li class="pull-left">
						<label class="control-label"><?php echo $this->lang->line('sales_stock_location'); ?></label>
					</li>
					<li class="pull-left">
						<?php echo form_dropdown('stock_location', $stock_locations, $stock_location, array('onchange'=>"$('#mode_form').submit();", 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
					</li>
				<?php
				}
				?>
				<li class="pull-right">
					<button class='btn btn-default btn-sm modal-dlg' id='show_suspended_sales_button' data-href="<?php echo site_url($controller_name."/suspended"); ?>"
							title="<?php echo $this->lang->line('sales_suspended_sales'); ?>">
						<span class="glyphicon glyphicon-align-justify">&nbsp</span><?php echo $this->lang->line('sales_suspended_sales'); ?>
					</button>
				</li>

				<?php
				if($this->Employee->has_grant('reports_sales', $this->session->userdata('person_id')))
				{
				?>
					<li class="pull-right">
						<?php echo anchor($controller_name."/manage", '<span class="glyphicon glyphicon-list-alt">&nbsp</span>' . $this->lang->line('sales_takings'),
									array('class'=>'btn btn-primary btn-sm', 'id'=>'sales_takings_button', 'title'=>$this->lang->line('sales_takings'))); ?>
					</li>
				<?php
				}
				?>

			</ul>
		</div>
	<?php echo form_close(); ?>

	<?php $tabindex = 0; ?>

	<?php echo form_open($controller_name."/add", array('id'=>'add_item_form', 'class'=>'form-horizontal panel panel-default')); ?>
		<div class="panel-body form-group">
			<div class="row" style="display: flex; align-items: center; gap: 10px;">
				<div class="col-xs-5" style="padding-right: 0;">
					<?php echo form_input(array('name'=>'item', 'id'=>'item', 'class'=>'form-control input-sm', 'size'=>'50', 'tabindex'=>++$tabindex, 'placeholder'=>$this->lang->line('sales_start_typing_item_name'))); ?>
				</div>
				<div class="col-xs-4" style="padding-right: 0;">
					<select id="cascading_select" class="form-control input-sm">
						<option value="">Select Category</option>
					</select>
				</div>
				<div class="col-xs-3" style="min-width: 120px;">
					<button id='new_item_button' class='btn btn-info btn-sm modal-dlg' data-btn-new="<?php echo $this->lang->line('common_new') ?>" data-btn-submit="<?php echo $this->lang->line('common_submit')?>" data-href="<?php echo site_url("items/view"); ?>"
						title="<?php echo $this->lang->line($controller_name . '_new_item'); ?>">
						<span class="glyphicon glyphicon-tag">&nbsp;</span><?php echo $this->lang->line($controller_name. '_new_item'); ?>
					</button>
				</div>
			</div>
		</div>
	<?php echo form_close(); ?>


<!-- Sale Items List -->

	<table class="sales_table_100" id="register">
		<thead>
			<tr>
				<th style="width: 5%; "><?php echo $this->lang->line('common_delete'); ?></th>
				<th style="width: 15%;"><?php echo $this->lang->line('sales_item_number'); ?></th>
				<th style="width: 15%;"><?php echo $this->lang->line('sales_item_name'); ?></th>
				<th style="width: 12%;"><?php echo $this->lang->line('item_categories_name'); ?></th>
				<th style="width: 12%;"><?php echo $this->lang->line('item_subcategories_name'); ?></th>
				<th style="width: 10%;"><?php echo $this->lang->line('sales_price'); ?></th>
				<th style="width: 10%;"><?php echo $this->lang->line('sales_quantity'); ?></th>
				<th style="width: 15%;"><?php echo $this->lang->line('sales_discount'); ?></th>
				<th style="width: 10%;"><?php echo $this->lang->line('sales_total'); ?></th>
				<th style="width: 5%; "><?php echo $this->lang->line('sales_update'); ?></th>
			</tr>
		</thead>

		<tbody id="cart_contents">
			<?php
			if(count($cart) == 0)
			{
			?>
				<tr>
					<td colspan='10'>
						<div class='alert alert-dismissible alert-info'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
					</td>
				</tr>
			<?php
			}
			else
			{
				foreach(array_reverse($cart, TRUE) as $line=>$item)
				{
			?>
					<?php echo form_open($controller_name."/edit_item/$line", array('class'=>'form-horizontal', 'id'=>'cart_'.$line)); ?>
						<tr>
							<td>
								<span data-item-id="<?php echo $line; ?>" class="delete_item_button"><span class="glyphicon glyphicon-trash"></span></span>
								<?php
								echo form_hidden('location', $item['item_location']);
								echo form_input(array('type'=>'hidden', 'name'=>'item_id', 'value'=>$item['item_id']));
								?>
							</td>
							<?php
							if($item['item_type'] == ITEM_TEMP)
							{
							?>
								<td><?php echo form_input(array('name'=>'item_number', 'id'=>'item_number','class'=>'form-control input-sm', 'value'=>$item['item_number'], 'tabindex'=>++$tabindex)); ?></td>
								<td style="align: center;">
									<?php echo form_input(array('name'=>'name','id'=>'name', 'class'=>'form-control input-sm', 'value'=>$item['name'], 'tabindex'=>++$tabindex)); ?>
								</td>
								<td></td>
								<td></td>
							<?php
							}
							else
							{
							?>
								<td><?php echo $item['item_number']; ?></td>
								<td style="align: center;">
									<?php echo $item['name'] . ' '. implode(' ', array($item['attribute_values'], $item['attribute_dtvalues'])); ?>
									<br/>
									<?php if ($item['stock_type'] == '0'): echo '[' . to_quantity_decimals($item['in_stock']) . ' in ' . $item['stock_name'] . ']'; endif; ?>
								</td>
								<td>
									<?php
									if (!empty($item['item_category_id'])) {
										$CI =& get_instance();
										$CI->load->model('Item_category');
										$category = $CI->Item_category->get_info($item['item_category_id']);
										echo $category->name;
									}
									?>
								</td>
								<td>
									<?php
									if (!empty($item['item_subcategory_id'])) {
										$CI =& get_instance();
										$CI->load->model('Item_subcategory');
										$subcategory = $CI->Item_subcategory->get_info($item['item_subcategory_id']);
										echo $subcategory->name;
									}
									?>
								</td>
							<?php
							}
							?>

							<td>
								<?php
								if($item['item_type'] == ITEM_AMOUNT_ENTRY)
								{
									echo form_input(array('name'=>'price', 'class'=>'form-control input-sm', 'value'=>to_currency_no_money($item['price']), 'tabindex'=>++$tabindex, 'onClick'=>'this.select();'));
								}
								else
								{
									// For items with the tax_option set to 'with_tax', show the price including tax
									if(isset($item['tax_option']) && $item['tax_option'] == 'with_tax')
									{
										// Get the total with tax
										$total_with_tax = $item['price'];
										
										// Look for tax related to this specific item
										foreach($taxes as $tax_key => $tax)
										{
											// Only add taxes that match this item and are fixed amount taxes
											if(strpos($tax_key, $item['item_id']) !== false && isset($tax['tax_type']) && $tax['tax_type'] == '1')
											{
												// For fixed tax, add it to the price (divide by quantity to get per-unit price)
												$total_with_tax = bcadd($total_with_tax, bcdiv($tax['sale_tax_amount'], $item['quantity'], 4), 4);
											}
										}
										echo to_currency($total_with_tax);
									}
									else
									{
										echo to_currency($item['price']);
									}
									echo form_hidden('price', to_currency_no_money($item['price']));
								}
								?>
							</td>

							<td>
								<?php
								if($item['is_serialized'])
								{
									echo to_quantity_decimals($item['quantity']);
									echo form_hidden('quantity', $item['quantity']);
								}
								else
								{
									echo form_input(array('name'=>'quantity', 'class'=>'form-control input-sm', 'value'=>to_quantity_decimals($item['quantity']), 'tabindex'=>++$tabindex, 'onClick'=>'this.select();'));
								}
								?>
							</td>

							<td>
								<div class="input-group">
									<?php echo form_input(array('name'=>'discount', 'class'=>'form-control input-sm', 'value'=>$item['discount_type'] ? to_currency_no_money($item['discount']) : to_decimals($item['discount']), 'tabindex'=>++$tabindex, 'onClick'=>'this.select();')); ?>
									<span class="input-group-btn">
										<?php echo form_checkbox(array('id'=>'discount_toggle', 'name'=>'discount_toggle', 'value'=>1, 'data-toggle'=>"toggle",'data-size'=>'small', 'data-onstyle'=>'success', 'data-on'=>'<b>'.$this->config->item('currency_symbol').'</b>', 'data-off'=>'<b>%</b>', 'data-line'=>$line, 'checked'=>$item['discount_type'])); ?>
									</span>
								</div>
							</td>

							<td>
								<?php
								if($item['item_type'] == ITEM_AMOUNT_ENTRY)
								{
									echo form_input(array('name'=>'discounted_total', 'class'=>'form-control input-sm', 'value'=>to_currency_no_money($item['discounted_total']), 'tabindex'=>++$tabindex, 'onClick'=>'this.select();'));
								}
								else
								{
									// For items with the tax_option set to 'with_tax', show the price including tax
									if(isset($item['tax_option']) && $item['tax_option'] == 'with_tax')
									{
										// Get the total with tax
										$total_with_tax = $item['discounted_total'];
										
										// Look for tax related to this specific item
										foreach($taxes as $tax_key => $tax)
										{
											// Only add taxes that match this item and are fixed amount taxes
											if(strpos($tax_key, $item['item_id']) !== false && isset($tax['tax_type']) && $tax['tax_type'] == '1')
											{
												// For fixed tax, add it to the price (divide by quantity to get per-unit price)
												$total_with_tax = bcadd($total_with_tax, bcdiv($tax['sale_tax_amount'], $item['quantity'], 4), 4);
											}
										}
										echo to_currency($total_with_tax);
									}
									else
									{
										echo to_currency($item['discounted_total']);
									}
								}
								?>
							</td>

							<td><a href="javascript:document.getElementById('<?php echo 'cart_'.$line ?>').submit();" title=<?php echo $this->lang->line('sales_update')?> ><span class="glyphicon glyphicon-refresh"></span></a></td>
						</tr>
						<tr>
							<?php
							if($item['item_type'] == ITEM_TEMP)
							{
							?>
								<td><?php echo form_input(array('type'=>'hidden', 'name'=>'item_id', 'value'=>$item['item_id'])); ?></td>
								<td style="align: center;" colspan="6">
									<?php echo form_input(array('name'=>'item_description', 'id'=>'item_description', 'class'=>'form-control input-sm', 'value'=>$item['description'], 'tabindex'=>++$tabindex)); ?>
								</td>
								<td> </td>
							<?php
							}
							else
							{
							?>
								<td> </td>
								<?php
								if($item['allow_alt_description'])
								{
								?>
									<td style="color: #2F4F4F;"><?php echo $this->lang->line('sales_description_abbrv'); ?></td>
								<?php
								}
								?>

								<td colspan='12' style="text-align: left;">
									<?php
									if($item['allow_alt_description'])
									{
										echo form_input(array('name'=>'description', 'class'=>'form-control input-sm', 'value'=>$item['description'], 'onClick'=>'this.select();'));
									}
									else
									{
										if($item['description'] != '')
										{
											echo $item['description'];
											echo form_hidden('description', $item['description']);
										}
										else
										{
											echo $this->lang->line('sales_no_description');
											echo form_hidden('description','');
										}
									}
									?>
								</td>
								<td>&nbsp;</td>
								<td style="color: #2F4F4F;">
									<?php
									if($item['is_serialized'])
									{
										echo $this->lang->line('sales_serial');
									}
									?>
								</td>
								<td colspan='4' style="text-align: left;">
									<?php
									if($item['is_serialized'])
									{
										echo form_input(array('name'=>'serialnumber', 'class'=>'form-control input-sm', 'value'=>$item['serialnumber'], 'onClick'=>'this.select();'));
									}
									else
									{
										echo form_hidden('serialnumber', '');
									}
									?>
								</td>
							<?php
							}
							?>
						</tr>
					<?php echo form_close(); ?>
			<?php
				}
			}
			?>
		</tbody>
	</table>
</div>

<!-- Overall Sale -->

<div id="overall_sale" class="panel panel-default">
	<div class="panel-body">
		<?php echo form_open($controller_name."/select_customer", array('id'=>'select_customer_form', 'class'=>'form-horizontal')); ?>
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
			<?php
			if(isset($customer))
			{
			?>
				<table class="sales_table_100">
					<tr>
						<th style="width: 55%;"><?php echo $this->lang->line("sales_customer"); ?></th>
						<th style="width: 45%; text-align: right;"><?php echo anchor('customers/view/'.$customer_id, $customer, array('class' => 'modal-dlg', 'data-btn-submit' => $this->lang->line('common_submit'), 'title' => $this->lang->line('customers_update'))); ?></th>
					</tr>
					<?php
					if(!empty($customer_email))
					{
					?>
						<tr>
							<th style="width: 55%;"><?php echo $this->lang->line("sales_customer_email"); ?></th>
							<th style="width: 45%; text-align: right;"><?php echo $customer_email; ?></th>
						</tr>
					<?php
					}
					?>
					<?php
					if(!empty($customer_address))
					{
					?>
						<tr>
							<th style="width: 55%;"><?php echo $this->lang->line("sales_customer_address"); ?></th>
							<th style="width: 45%; text-align: right;"><?php echo $customer_address; ?></th>
						</tr>
					<?php
					}
					?>
					<?php
					if(!empty($customer_location))
					{
					?>
						<tr>
							<th style="width: 55%;"><?php echo $this->lang->line("sales_customer_location"); ?></th>
							<th style="width: 45%; text-align: right;"><?php echo $customer_location; ?></th>
						</tr>
					<?php
					}
					?>
					<tr>
						<th style="width: 55%;"><?php echo $this->lang->line("sales_customer_discount"); ?></th>
						<th style="width: 45%; text-align: right;"><?php echo ($customer_discount_type == FIXED)?to_currency($customer_discount):$customer_discount . '%'; ?></th>
					</tr>
					<?php if($this->config->item('customer_reward_enable') == TRUE): ?>
					<?php
					if(!empty($customer_rewards))
					{
					?>
						<tr>
							<th style="width: 55%;"><?php echo $this->lang->line("rewards_package"); ?></th>
							<th style="width: 45%; text-align: right;"><?php echo $customer_rewards['package_name']; ?></th>
						</tr>
						<tr>
							<th style="width: 55%;"><?php echo $this->lang->line("customers_available_points"); ?></th>
							<th style="width: 45%; text-align: right;"><?php echo $customer_rewards['points']; ?></th>
						</tr>
					<?php
					}
					?>
					<?php endif; ?>
					<tr>
						<th style="width: 55%;"><?php echo $this->lang->line("sales_customer_total"); ?></th>
						<th style="width: 45%; text-align: right;"><?php echo to_currency($customer_total); ?></th>
					</tr>
					<?php
					if(!empty($mailchimp_info))
					{
					?>
						<tr>
							<th style="width: 55%;"><?php echo $this->lang->line("sales_customer_mailchimp_status"); ?></th>
							<th style="width: 45%; text-align: right;"><?php echo $mailchimp_info['status']; ?></th>
						</tr>
					<?php
					}
					?>
				</table>

				<button class="btn btn-danger btn-sm" id="remove_customer_button" title="<?php echo $this->lang->line('common_remove').' '.$this->lang->line('customers_customer')?>">
					<span class="glyphicon glyphicon-remove">&nbsp</span><?php echo $this->lang->line('common_remove').' '.$this->lang->line('customers_customer') ?>
				</button>

				<?php
			}
			else
			{
			?>
				<div class="form-group" id="select_customer">
					<label id="customer_label" for="customer" class="control-label" style="margin-bottom: 1em; margin-top: -1em;"><?php echo $this->lang->line('sales_select_customer') . ' ' . $customer_required; ?></label>
					<?php echo form_input(array('name'=>'customer', 'id'=>'customer', 'class'=>'form-control input-sm', 'value'=>$this->lang->line('sales_start_typing_customer_name'))); ?>

					<button class='btn btn-info btn-sm modal-dlg' data-btn-submit="<?php echo $this->lang->line('common_submit') ?>" data-href="<?php echo site_url("customers/view"); ?>"
							title="<?php echo $this->lang->line($controller_name. '_new_customer'); ?>">
						<span class="glyphicon glyphicon-user">&nbsp</span><?php echo $this->lang->line($controller_name. '_new_customer'); ?>
					</button>					
					<button class='btn btn-default btn-sm modal-dlg' id='show_keyboard_help' data-href="<?php echo site_url("$controller_name/sales_keyboard_help"); ?>"
							title="<?php echo $this->lang->line('sales_key_title'); ?>">
						<span class="glyphicon glyphicon-share-alt">&nbsp</span><?php echo $this->lang->line('sales_key_help'); ?>
					</button>
				</div>
			<?php
			}
			?>
		<?php echo form_close(); ?>

		<style>
			.bootstrap-select>.dropdown-toggle.bs-placeholder {
				color: #fff !important;
			}
		</style>

		<!-- Delivery Man Section: Always visible, styled like customer -->
		<div class="form-group" id="delivery_man_section" style="margin-top: 10px; margin-bottom: 15px; border-top: 1px solid #000; padding-top: 10px;">
			<label for="delivery_man_search" class="control-label">Delivery Man</label>
			<?php if(!isset($delivery_man_id) || !$delivery_man_id): ?>
				<form method="post" action="<?php echo site_url('sales/set_delivery_man'); ?>" id="delivery_man_form" style="margin-bottom:0;">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
					<select name="delivery_man_id" id="delivery_man_search" class="selectpicker form-control input-sm" data-live-search="true" title="Select Delivery Man" onchange="$('#delivery_man_form').submit();">
						<option value="">Select Delivery Man</option>
						<?php foreach($employees as $employee): ?>
							<option value="<?php echo $employee['person_id']; ?>">
								<?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</form>
			<?php else: ?>
				<table class="sales_table_100" style="margin-top:5px;">
					<?php foreach($employees as $employee): if($employee['person_id'] == $delivery_man_id): ?>
						<tr><th style="width: 55%;">Delivery Man</th><th style="width: 45%; text-align: right;"><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></th></tr>
						<?php if (!empty($employee['phone_number'])): ?>
							<tr><td>Phone</td><td style="text-align: right;"><?php echo htmlspecialchars($employee['phone_number']); ?></td></tr>
						<?php endif; ?>
						<?php if (!empty($employee['email'])): ?>
							<tr><td>Email</td><td style="text-align: right;"><?php echo htmlspecialchars($employee['email']); ?></td></tr>
						<?php endif; ?>
					<?php break; endif; endforeach; ?>
				</table>
				<form method="post" action="<?php echo site_url('sales/set_delivery_man'); ?>" id="remove_delivery_man_form" style="margin-top:8px;">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
					<input type="hidden" name="delivery_man_id" value="">
					<button class="btn btn-danger btn-sm" type="submit">
						<span class="glyphicon glyphicon-remove">&nbsp;</span>Remove Delivery Man
					</button>
				</form>
			<?php endif; ?>
		</div>

		<table class="sales_table_100" id="sale_totals">
			<tr>
				<th style="width: 55%;"><?php echo $this->lang->line('sales_quantity_of_items',$item_count); ?></th>
				<th style="width: 45%; text-align: right;"><?php echo $total_units; ?></th>
			</tr>
			<tr>
				<th style="width: 55%;"><?php echo $this->lang->line('sales_sub_total'); ?></th>
				<th style="width: 45%; text-align: right;"><?php echo to_currency($subtotal); ?></th>
			</tr>

			<?php
			foreach($taxes as $tax_group_index=>$tax)
			{
			?>
				<tr>
					<th style="width: 55%;">
					<?php 
						if(isset($tax['tax_type']) && $tax['tax_type'] == 1) {
							// Fixed amount tax - display only the tax group name
							echo $tax['tax_group']; 
						} else {
							// Percentage tax - display percentage and tax group name
							echo (float)$tax['tax_rate'] . '% ' . $tax['tax_group']; 
						}
					?>
					</th>
					<th style="width: 45%; text-align: right;"><?php echo to_currency_tax($tax['sale_tax_amount']); ?></th>
				</tr>
			<?php
			}
			?>

			<tr>
				<th style="width: 55%; font-size: 150%"><?php echo $this->lang->line('sales_total'); ?></th>
				<th style="width: 45%; font-size: 150%; text-align: right;"><span id="sale_total"><?php echo to_currency($total); ?></span></th>
			</tr>
		</table>

		<?php
		// Only show this part if there are Items already in the register
		if(count($cart) > 0)
		{
		?>
			<table class="sales_table_100" id="payment_totals">
				<tr>
					<th style="width: 55%;"><?php echo $this->lang->line('sales_payments_total'); ?></th>
					<th style="width: 45%; text-align: right;"><?php echo to_currency($payments_total); ?></th>
				</tr>
				<tr>
					<th style="width: 55%; font-size: 120%"><?php echo $this->lang->line('sales_amount_due'); ?></th>
					<th style="width: 45%; font-size: 120%; text-align: right;"><span id="sale_amount_due"><?php echo to_currency($amount_due); ?></span></th>
				</tr>
			</table>

			<div id="payment_details">
				<?php
				// Show Complete sale button instead of Add Payment if there is no amount due left
				if($payments_cover_total)
				{
				?>
					<?php echo form_open($controller_name."/add_payment", array('id'=>'add_payment_form', 'class'=>'form-horizontal')); ?>
						<table class="sales_table_100">
							<tr>
								<td><?php echo $this->lang->line('sales_payment'); ?></td>
								<td>
									<?php echo form_dropdown('payment_type', $payment_options, $selected_payment_type, array('id'=>'payment_types', 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit', 'disabled'=>'disabled')); ?>
								</td>
							</tr>
							<tr>
								<td><span id="amount_tendered_label"><?php echo $this->lang->line('sales_amount_tendered'); ?></span></td>
								<td>
									<?php echo form_input(array('name'=>'amount_tendered', 'id'=>'amount_tendered', 'class'=>'form-control input-sm disabled', 'disabled'=>'disabled', 'value'=>'0', 'size'=>'5', 'tabindex'=>++$tabindex, 'onClick'=>'this.select();')); ?>
								</td>
							</tr>
						</table>
					<?php echo form_close(); ?>

					<?php
					// Only show this part if in sale or return mode
					if($pos_mode)
					{
						$due_payment = FALSE;

						if(count($payments) > 0)
						{
							foreach($payments as $payment_id => $payment)
							{
								if($payment['payment_type'] == $this->lang->line('sales_due'))
								{
									$due_payment = TRUE;
								}
							}
						}

						if(!$due_payment || ($due_payment && isset($customer)))
						{
					?>
							<div class='btn btn-sm btn-success pull-right' id='finish_sale_button' tabindex="<?php echo ++$tabindex; ?>"><span class="glyphicon glyphicon-ok">&nbsp</span><?php echo $this->lang->line('sales_complete_sale'); ?></div>
					<?php
						}
					}
					?>
				<?php
				}
				else
				{
				?>
					<?php echo form_open($controller_name."/add_payment", array('id'=>'add_payment_form', 'class'=>'form-horizontal')); ?>
						<table class="sales_table_100">
							<tr>
								<td><?php echo $this->lang->line('sales_payment'); ?></td>
								<td>
									<?php echo form_dropdown('payment_type', $payment_options,  $selected_payment_type, array('id'=>'payment_types', 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
								</td>
							</tr>
							<tr>
								<td><span id="amount_tendered_label"><?php echo $this->lang->line('sales_amount_tendered'); ?></span></td>
								<td>
									<?php echo form_input(array('name'=>'amount_tendered', 'id'=>'amount_tendered', 'class'=>'form-control input-sm non-giftcard-input', 'value'=>to_currency_no_money($amount_due), 'size'=>'5', 'tabindex'=>++$tabindex, 'onClick'=>'this.select();')); ?>
									<?php echo form_input(array('name'=>'amount_tendered', 'id'=>'amount_tendered', 'class'=>'form-control input-sm giftcard-input', 'disabled' => true, 'value'=>to_currency_no_money($amount_due), 'size'=>'5', 'tabindex'=>++$tabindex)); ?>
								</td>
							</tr>
						</table>
					<?php echo form_close(); ?>

					<div class='btn btn-sm btn-success pull-right' id='add_payment_button' tabindex="<?php echo ++$tabindex; ?>"><span class="glyphicon glyphicon-credit-card">&nbsp</span><?php echo $this->lang->line('sales_add_payment'); ?></div>
				<?php
				}
				?>

				<?php
				// Only show this part if there is at least one payment entered.
				if(count($payments) > 0)
				{
				?>
					<table class="sales_table_100" id="register">
						<thead>
							<tr>
								<th style="width: 10%;"><?php echo $this->lang->line('common_delete'); ?></th>
								<th style="width: 60%;"><?php echo $this->lang->line('sales_payment_type'); ?></th>
								<th style="width: 20%;"><?php echo $this->lang->line('sales_payment_amount'); ?></th>
							</tr>
						</thead>

						<tbody id="payment_contents">
							<?php
							foreach($payments as $payment_id => $payment)
							{
							?>
								<tr>
									<td><span data-payment-id="<?php echo $payment_id; ?>" class="delete_payment_button"><span class="glyphicon glyphicon-trash"></span></span></td>
									<td><?php echo $payment['payment_type']; ?></td>
									<td style="text-align: right;"><?php echo to_currency($payment['payment_amount']); ?></td>
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				<?php
				}
				?>
			</div>

			<?php echo form_open($controller_name."/cancel", array('id'=>'buttons_form')); ?>
				<div class="form-group" id="buttons_sale">
					<div class='btn btn-sm btn-default pull-left' id='suspend_sale_button'><span class="glyphicon glyphicon-align-justify">&nbsp</span><?php echo $this->lang->line('sales_suspend_sale'); ?></div>
					<?php
					// Only show this part if the payment covers the total
					if(!$pos_mode && isset($customer))
					{
					?>
						<div class='btn btn-sm btn-success' id='finish_invoice_quote_button'><span class="glyphicon glyphicon-ok">&nbsp</span><?php echo $mode_label; ?></div>
					<?php
					}
					?>

					<div class='btn btn-sm btn-danger pull-right' id='cancel_sale_button'><span class="glyphicon glyphicon-remove">&nbsp</span><?php echo $this->lang->line('sales_cancel_sale'); ?></div>
				</div>
			<?php echo form_close(); ?>

			<?php
			// Only show this part if the payment cover the total
			if($payments_cover_total || !$pos_mode)
			{
			?>
				<div class="container-fluid">
					<div class="no-gutter row">
						<div class="form-group form-group-sm">
							<div class="col-xs-12">
								<?php echo form_label($this->lang->line('common_comments'), 'comments', array('class'=>'control-label', 'id'=>'comment_label', 'for'=>'comment')); ?>
								<?php echo form_textarea(array('name'=>'comment', 'id'=>'comment', 'class'=>'form-control input-sm', 'value'=>$comment, 'rows'=>'2')); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group form-group-sm">
							<div class="col-xs-6">
								<label for="sales_print_after_sale" class="control-label checkbox">
									<?php echo form_checkbox(array('name'=>'sales_print_after_sale', 'id'=>'sales_print_after_sale', 'value'=>1, 'checked'=>$print_after_sale)); ?>
									<?php echo $this->lang->line('sales_print_after_sale')?>
								</label>
							</div>

							<?php
							if(!empty($customer_email))
							{
							?>
								<div class="col-xs-6">
									<label for="email_receipt" class="control-label checkbox">
										<?php echo form_checkbox(array('name'=>'email_receipt', 'id'=>'email_receipt', 'value'=>1, 'checked'=>$email_receipt)); ?>
										<?php echo $this->lang->line('sales_email_receipt'); ?>
									</label>
								</div>
							<?php
							}
							?>
							<?php
							if($mode == 'sale_work_order')
							{
							?>
								<div class="col-xs-6">
									<label for="price_work_orders" class="control-label checkbox">
									<?php echo form_checkbox(array('name'=>'price_work_orders', 'id'=>'price_work_orders', 'value'=>1, 'checked'=>$price_work_orders)); ?>
									<?php echo $this->lang->line('sales_include_prices'); ?>
									</label>
								</div>
							<?php
							}
							?>
						</div>
					</div>
					<?php
					if(($mode == 'sale_invoice') && $this->config->item('invoice_enable') == TRUE)
					{
					?>
						<div class="row">
							<div class="form-group form-group-sm">
								<div class="col-xs-6">
									<label for="sales_invoice_number" class="control-label checkbox">
										<?php echo $this->lang->line('sales_invoice_enable'); ?>
									</label>
								</div>

								<div class="col-xs-6">
									<div class="input-group input-group-sm">
										<span class="input-group-addon input-sm">#</span>
										<?php echo form_input(array('name'=>'sales_invoice_number', 'id'=>'sales_invoice_number', 'class'=>'form-control input-sm', 'value'=>$invoice_number)); ?>
									</div>
								</div>
							</div>
						</div>
					<?php
					}
					?>
				</div>
			<?php
			}
			?>
		<?php
		}
		?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
	const redirect = function() {
		window.location.href = "<?php echo site_url('sales'); ?>";
	};

	$("#remove_customer_button").click(function()
	{
		$.post("<?php echo site_url('sales/remove_customer'); ?>", redirect);
	});

	$(".delete_item_button").click(function()
	{
		const item_id = $(this).data('item-id');
		$.post("<?php echo site_url('sales/delete_item/'); ?>" + item_id, redirect);
	});

	$(".delete_payment_button").click(function() {
		const item_id = $(this).data('payment-id');
		$.post("<?php echo site_url('sales/delete_payment/'); ?>" + item_id, redirect);
	});

	$("input[name='item_number']").change(function() {
		var item_id = $(this).parents('tr').find("input[name='item_id']").val();
		var item_number = $(this).val();
		$.ajax({
			url: "<?php echo site_url('sales/change_item_number'); ?>",
			method: 'post',
			data: {
				'item_id': item_id,
				'item_number': item_number,
			},
			dataType: 'json'
		});
	});

	$("input[name='name']").change(function() {
		var item_id = $(this).parents('tr').find("input[name='item_id']").val();
		var item_name = $(this).val();
		$.ajax({
			url: "<?php echo site_url('sales/change_item_name'); ?>",
			method: 'post',
			data: {
				'item_id': item_id,
				'item_name': item_name,
			},
			dataType: 'json'
		});
	});

	$("input[name='item_description']").change(function() {
		var item_id = $(this).parents('tr').find("input[name='item_id']").val();
		var item_description = $(this).val();
		$.ajax({
			url: "<?php echo site_url('sales/change_item_description'); ?>",
			method: 'post',
			data: {
				'item_id': item_id,
				'item_description': item_description,
			},
			dataType: 'json'
		});
	});

	$('#item').focus();

	$('#item').blur(function() {
		$(this).val("<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
	});

	$('#item').autocomplete( {
		source: "<?php echo site_url($controller_name . '/item_search'); ?>",
		minChars: 0,
		autoFocus: false,
		delay: 500,
		select: function (a, ui) {
			$(this).val(ui.item.value);
			$('#add_item_form').submit();
			return false;
		}
	});

	$('#item').keypress(function (e) {
		if(e.which == 13) {
			$('#add_item_form').submit();
			return false;
		}
	});

	var clear_fields = function() {
		if($(this).val().match("<?php echo $this->lang->line('sales_start_typing_item_name') . '|' . $this->lang->line('sales_start_typing_customer_name'); ?>"))
		{
			$(this).val('');
		}
	};

	$('#item, #customer').click(clear_fields).dblclick(function(event) {
		$(this).autocomplete('search');
	});

	$('#customer').blur(function() {
		$(this).val("<?php echo $this->lang->line('sales_start_typing_customer_name'); ?>");
	});

	$('#customer').autocomplete( {
		source: "<?php echo site_url('customers/suggest'); ?>",
		minChars: 0,
		delay: 10,
		select: function (a, ui) {
			$(this).val(ui.item.value);
			$('#select_customer_form').submit();
			return false;
		}
	});

	$('#customer').keypress(function (e) {
		if(e.which == 13) {
			$('#select_customer_form').submit();
			return false;
		}
	});

	$('.giftcard-input').autocomplete( {
		source: "<?php echo site_url('giftcards/suggest'); ?>",
		minChars: 0,
		delay: 10,
		select: function (a, ui) {
			$(this).val(ui.item.value);
			$('#add_payment_form').submit();
			return false;
		}
	});

	$('#comment').keyup(function() {
		$.post("<?php echo site_url($controller_name.'/set_comment'); ?>", {comment: $('#comment').val()});
	});

	<?php
	if($this->config->item('invoice_enable') == TRUE)
	{
	?>
		$('#sales_invoice_number').keyup(function() {
			$.post("<?php echo site_url($controller_name.'/set_invoice_number'); ?>", {sales_invoice_number: $('#sales_invoice_number').val()});
		});

	<?php
	}
	?>

	$('#sales_print_after_sale').change(function() {
		$.post("<?php echo site_url($controller_name.'/set_print_after_sale'); ?>", {sales_print_after_sale: $(this).is(':checked')});
	});

	$('#price_work_orders').change(function() {
		$.post("<?php echo site_url($controller_name.'/set_price_work_orders'); ?>", {price_work_orders: $(this).is(':checked')});
	});

	$('#email_receipt').change(function() {
		$.post("<?php echo site_url($controller_name.'/set_email_receipt'); ?>", {email_receipt: $(this).is(':checked')});
	});

	$('#finish_sale_button').click(function() {
		$('#buttons_form').attr('action', "<?php echo site_url($controller_name.'/complete'); ?>");
		$('#buttons_form').submit();
	});

	$('#finish_invoice_quote_button').click(function() {
		$('#buttons_form').attr('action', "<?php echo site_url($controller_name.'/complete'); ?>");
		$('#buttons_form').submit();
	});

	$('#suspend_sale_button').click(function() {
		$('#buttons_form').attr('action', "<?php echo site_url($controller_name.'/suspend'); ?>");
		$('#buttons_form').submit();
	});

	$('#cancel_sale_button').click(function() {
		if(confirm("<?php echo $this->lang->line('sales_confirm_cancel_sale'); ?>"))
		{
			$('#buttons_form').attr('action', "<?php echo site_url($controller_name.'/cancel'); ?>");
			$('#buttons_form').submit();
		}
	});

	$('#add_payment_button').click(function() {
		$('#add_payment_form').submit();
	});

	$('#payment_types').change(check_payment_type).ready(check_payment_type);

	$('#cart_contents input').keypress(function(event) {
		if(event.which == 13)
		{
			$(this).parents('tr').prevAll('form:first').submit();
		}
	});

	$('#amount_tendered').keypress(function(event) {
		if(event.which == 13)
		{
			$('#add_payment_form').submit();
		}
	});

	$('#finish_sale_button').keypress(function(event) {
		if(event.which == 13)
		{
			$('#finish_sale_form').submit();
		}
	});

	dialog_support.init('a.modal-dlg, button.modal-dlg');

	table_support.handle_submit = function(resource, response, stay_open) {
		$.notify( { message: response.message }, { type: response.success ? 'success' : 'danger'} )

		if(response.success)
		{
			if(resource.match(/customers$/))
			{
				$('#customer').val(response.id);
				$('#select_customer_form').submit();
			}
			else
			{
				var $stock_location = $("select[name='stock_location']").val();
				$('#item_location').val($stock_location);
				$('#item').val(response.id);
				if(stay_open)
				{
					$('#add_item_form').ajaxSubmit();
				}
				else
				{
					$('#add_item_form').submit();
				}
			}
		}
	}

	$('[name="price"],[name="quantity"],[name="discount"],[name="description"],[name="serialnumber"],[name="discounted_total"]').change(function() {
		$(this).parents('tr').prevAll('form:first').submit()
	});

	$('[name="discount_toggle"]').change(function() {
		var input = $('<input>').attr('type', 'hidden').attr('name', 'discount_type').val(($(this).prop('checked'))?1:0);
		$('#cart_'+ $(this).attr('data-line')).append($(input));
		$('#cart_'+ $(this).attr('data-line')).submit();
	});

	var selectState = 'category';
	var selectedCategory = null;
	var selectedSubcategory = null;

	function loadCategories() {
		$.getJSON('items/get_all_categories', function(data) {
			var $select = $('#cascading_select');
			$select.empty();
			$select.append('<option value="">Select Category</option>');
			$.each(data, function(id, name) {
				$select.append('<option value="cat_' + id + '">' + name + '</option>');
			});
			selectState = 'category';
			selectedCategory = null;
			selectedSubcategory = null;
			$select.val('');
		});
	}

	function loadSubcategoriesByCategory(category_id) {
		$.getJSON('items/get_subcategories_json/' + category_id, function(data) {
			var $select = $('#cascading_select');
			$select.empty();
			$select.append('<option value="back">← Back to Categories</option>');
			$.each(data, function(id, name) {
				$select.append('<option value="subcat_' + id + '">' + name + '</option>');
			});
			selectState = 'subcategory';
			selectedCategory = category_id;
			selectedSubcategory = null;
			$select.val('');
		});
	}

	function loadItemsBySubcategory(subcategory_id) {
		$.getJSON('items/get_items_by_subcategory/' + subcategory_id, function(data) {
			var $select = $('#cascading_select');
			$select.empty();
			$select.append('<option value="back">← Back to Subcategories</option>');
			$.each(data, function(id, name) {
				$select.append('<option value="item_' + id + '">' + name + '</option>');
			});
			selectState = 'item';
			selectedSubcategory = subcategory_id;
			$select.val('');
		});
	}

	function loadItemsByCategory(category_id) {
		$.getJSON('items/get_items_by_category/' + category_id, function(data) {
			var $select = $('#cascading_select');
			$select.empty();
			$select.append('<option value="back">← Back to Categories</option>');
			$.each(data, function(id, name) {
				$select.append('<option value="item_' + id + '">' + name + '</option>');
			});
			selectState = 'item';
			selectedCategory = category_id;
			selectedSubcategory = null;
			$select.val('');
		});
	}

	// Initial load
	loadCategories();

	$('#cascading_select').on('change', function() {
		var select = this;
		var val = $(this).val();
		if (val === 'back') {
			if (selectState === 'subcategory') {
				loadCategories();
			} else if (selectState === 'item') {
				if (selectedSubcategory) {
					loadSubcategoriesByCategory(selectedCategory);
				} else {
					loadCategories();
				}
			}
			setTimeout(function() { select.value = ''; }, 0);
			return;
		}

		if (selectState === 'category') {
			if (val && val.startsWith('cat_')) {
				var category_id = val.replace('cat_', '');
				// Check if category has subcategories, if so load subcategories, otherwise load items directly
				$.getJSON('items/get_subcategories_json/' + category_id, function(data) {
					if (Object.keys(data).length > 0) {
						// Category has subcategories, load them
						loadSubcategoriesByCategory(category_id);
					} else {
						// No subcategories, load items directly
						loadItemsByCategory(category_id);
					}
				});
			}
		} else if (selectState === 'subcategory') {
			if (val && val.startsWith('subcat_')) {
				var subcategory_id = val.replace('subcat_', '');
				loadItemsBySubcategory(subcategory_id);
			}
		} else if (selectState === 'item') {
			if (val && val.startsWith('item_')) {
				// Set the item input and submit the form
				var item_id = val.replace('item_', '');
				$('#item').val(item_id);
				$('#add_item_form').submit();
			}
		}
	});
});

function check_payment_type()
{
	var cash_mode = <?php echo json_encode($cash_mode); ?>;

	if($("#payment_types").val() == "<?php echo $this->lang->line('sales_giftcard'); ?>")
	{
		$("#sale_total").html("<?php echo to_currency($total); ?>");
		$("#sale_amount_due").html("<?php echo to_currency($amount_due); ?>");
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_giftcard_number'); ?>");
		$("#amount_tendered:enabled").val('').focus();
		$(".giftcard-input").attr('disabled', false);
		$(".non-giftcard-input").attr('disabled', true);
		$(".giftcard-input:enabled").val('').focus();
	}
	else if(($("#payment_types").val() == "<?php echo $this->lang->line('sales_cash'); ?>" && cash_mode == '1'))
	{
		$("#sale_total").html("<?php echo to_currency($non_cash_total); ?>");
		$("#sale_amount_due").html("<?php echo to_currency($cash_amount_due); ?>");
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_amount_tendered'); ?>");
		$("#amount_tendered:enabled").val("<?php echo to_currency_no_money($cash_amount_due); ?>");
		$(".giftcard-input").attr('disabled', true);
		$(".non-giftcard-input").attr('disabled', false);
	}
	else
	{
		$("#sale_total").html("<?php echo to_currency($non_cash_total); ?>");
		$("#sale_amount_due").html("<?php echo to_currency($amount_due); ?>");
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_amount_tendered'); ?>");
		$("#amount_tendered:enabled").val("<?php echo to_currency_no_money($amount_due); ?>");
		$(".giftcard-input").attr('disabled', true);
		$(".non-giftcard-input").attr('disabled', false);
	}
}

// Add Keyboard Shortcuts/Hotkeys to Sale Register
document.body.onkeyup = function(e)
{
	switch(event.altKey && event.keyCode) 
	{
        case 49: // Alt + 1 Items Seach
			$("#item").focus();
			$("#item").select();
            break;
        case 50: // Alt + 2 Customers Search
			$("#customer").focus();
			$("#customer").select();
            break;
		case 51: // Alt + 3 Suspend Current Sale
			$("#suspend_sale_button").click();
			break;
		case 52: // Alt + 4 Check Suspended
			$("#show_suspended_sales_button").click();
			break;
        case 53: // Alt + 5 Edit Amount Tendered Value
			$("#amount_tendered").focus();
			$("#amount_tendered").select();
            break;
		case 54: // Alt + 6 Add Payment
			$("#add_payment_button").click();
			break;	
		case 55: // Alt + 7 Add Payment and Complete Sales/Invoice
			$("#add_payment_button").click();
			window.location.href = "<?php echo site_url('sales/complete'); ?>";
			break; 
		case 56: // Alt + 8 Finish Quote/Invoice without payment
			$("#finish_invoice_quote_button").click();
			break;
		case 57: // Alt + 9 Open Shortcuts Help Modal
			$("#show_keyboard_help").click();
			break;
	}
	
	switch(event.keyCode) 
	{
		case 27: // ESC Cancel Current Sale
			$("#cancel_sale_button").click();
			break;		  
    }
}

</script>

<?php $this->load->view("partial/footer"); ?>
