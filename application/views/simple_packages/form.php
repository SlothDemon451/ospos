<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('simple_packages/save/'.$package_info->package_id, array('id'=>'package_form', 'class'=>'form-horizontal')); ?>
	<fieldset id="package_basic_info">
        <div class="form-group form-group-sm">
            <?php echo form_label($this->lang->line('simple_packages_package_number'), 'package_number', array('class'=>'control-label col-xs-3')); ?>
            <div class='col-xs-8'>
                <div class="input-group">
                    <span class="input-group-addon input-sm"><span class="glyphicon glyphicon-barcode"></span></span>
                    <?php echo form_input(array(
                            'name'=>'package_number',
                            'id'=>'package_number',
                            'class'=>'form-control input-sm',
                            'value'=>$package_info->package_number)
                    );?>
                </div>
            </div>
        </div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('simple_packages_name'), 'name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'name',
						'id'=>'name',
						'class'=>'form-control input-sm',
						'value'=>$package_info->name)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('simple_packages_description'), 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
						'name'=>'description',
						'id'=>'description',
						'class'=>'form-control input-sm',
						'value'=>$package_info->description)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Active', 'active', array('class'=>'control-label col-xs-3')); ?>
			<div class="col-xs-8">
				<label class="checkbox-inline">
					<?php echo form_checkbox(array(
							'name'=>'active',
							'value'=>'1',
							'checked'=>$package_info->active == 1)
					); ?> Active
				</label>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Package Price', 'package_price', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-usd"></span></span>
					<?php echo form_input(array(
							'name'=>'package_price',
							'id'=>'package_price',
							'class'=>'form-control input-sm',
							'value'=>isset($package_info->package_price) ? $package_info->package_price : '')
					);?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('simple_packages_add_item'), 'item', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'item',
						'id'=>'item',
						'class'=>'form-control input-sm',
						'placeholder'=>'Type item name to search...')
						);?>
			</div>
		</div>
		
		<div class="form-group form-group-sm">
			<label class="control-label col-xs-3">Items Count:</label>
			<div class='col-xs-8'>
				<span id="item_count" class="form-control-static">0</span> <small class="text-muted">(Minimum 2 items required)</small>
			</div>
		</div>

		<table id="package_items" class="table table-striped table-hover">
			<thead>
				<tr>
								<th width="10%"><?php echo $this->lang->line('simple_packages_delete_item'); ?></th>
			<th width="60%"><?php echo $this->lang->line('simple_packages_item_name'); ?></th>
			<th width="15%"><?php echo $this->lang->line('simple_packages_quantity'); ?></th>
			<th width="15%"><?php echo $this->lang->line('simple_packages_unit_price'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($package_items as $package_item)
				{
				?>
					<tr>
						<td><a href='#' onclick='return delete_package_row(this);'><span class='glyphicon glyphicon-trash'></span></a></td>
						<td><?php echo $package_item['name']; ?></td>
						<td><input class='quantity form-control input-sm' id='item_qty_<?php echo $package_item['item_id'] ?>' name=package_qty[<?php echo $package_item['item_id'] ?>] value="<?php echo $package_item['quantity'] ?>"/></td>
						<td><?php echo $package_item['unit_price']; ?></td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>

		<div class="form-group form-group-sm">
			<label class="control-label col-xs-3">Calculated Total:</label>
			<div class='col-xs-8'>
				<strong id="calculated_total">$0.00</strong>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<label class="control-label col-xs-3">Final Price:</label>	
			<div class='col-xs-8'>
				<strong id="final_price">$0.00</strong>
			</div>
		</div>
	</fieldset>

<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	// Initialize item count
	updateItemCount();
	
	// Handle quantity changes for existing items
	$('.quantity').on('change', function() {
		updateItemCount();
		calculateTotals();
	});

	// Handle package price and discount changes
	$('#package_price, #discount').on('input', function() {
		calculateTotals();
	});
	$('#item').autocomplete({
		source: "<?php echo site_url('simple_packages/item_search'); ?>",
		minChars: 0,
		autoFocus: false,
		delay: 10,
		appendTo: '.modal-content',
		select: function(e, ui) {
			if($('#item_qty_' + ui.item.value).length == 1)
			{
				$('#item_qty_' + ui.item.value).val(parseFloat( $('#item_qty_' + ui.item.value).val()) + 1);
			}
			else
			{
				$('#package_items tbody').append('<tr>' +
					"<td><a href='#' onclick='return delete_package_row(this);'><span class='glyphicon glyphicon-trash'></span></a></td>" +
					'<td>' + ui.item.label + '</td>' +
					"<td><input class='quantity form-control input-sm' id='item_qty_" + ui.item.value + "' name=package_qty[" + ui.item.value + "] value='1'/></td>" +
					'<td>' + ui.item.unit_price + '</td>' +
					'</tr>');
			}
			$('#item').val('');
			updateItemCount();
			hideValidationError();
			return false;
		}
	});

	$('#package_form').validate($.extend({
		submitHandler: function(form) {
			// Check if at least 2 items are added
			var itemCount = $('#package_items tbody tr').length;
			if (itemCount < 2) {
				$('#error_message_box').html('<li>Please add at least 2 items to the package</li>');
				$('#error_message_box').show();
				return false;
			}
			
			$(form).ajaxSubmit({
				success: function(response)
				{
					dialog_support.hide();
					table_support.handle_submit("<?php echo site_url($controller_name); ?>", response);
				},
				dataType: 'json'
			});
		},

		errorLabelContainer: '#error_message_box',

		rules:
		{
			name: 'required'
		},

		messages:
		{
			name: "Package name is required"
		}
	}, form_support.error));
});

function delete_package_row(link)
{
	$(link).parent().parent().remove();
	updateItemCount();
	return false;
}

function updateItemCount()
{
	var count = $('#package_items tbody tr').length;
	$('#item_count').text(count);
	
	// Update validation message if needed
	if (count < 2) {
		$('#error_message_box').html('<li>Please add at least 2 items to the package</li>');
		$('#error_message_box').show();
	} else {
		hideValidationError();
	}
	
	// Calculate totals when item count changes
	calculateTotals();
}

function calculateTotals()
{
	var packagePrice = parseFloat($('#package_price').val()) || 0;
	var discount = parseFloat($('#discount').val()) || 0;
	
	// Calculate item total from table
	var itemTotal = 0;
	$('#package_items tbody tr').each(function() {
		var quantity = parseFloat($(this).find('.quantity').val()) || 0;
		var unitPrice = parseFloat($(this).find('td:last').text().replace(/[^0-9.-]+/g, '')) || 0;
		itemTotal += quantity * unitPrice;
	});
	
	// Display calculated total
	$('#calculated_total').text('$' + itemTotal.toFixed(2));
	
	// Calculate final price after discount
	var finalPrice = packagePrice > 0 ? packagePrice : itemTotal;
	var discountAmount = (finalPrice * discount) / 100;
	var finalPriceAfterDiscount = finalPrice - discountAmount;
	
	$('#final_price').text('$' + finalPriceAfterDiscount.toFixed(2));
}

function hideValidationError()
{
	$('#error_message_box').hide();
}
</script>
