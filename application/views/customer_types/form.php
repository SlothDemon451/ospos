<?php
/**
 * Customer Type form view
 */
?>

<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open($controller_name . '/save/' . $customer_type_info->customer_type_id, array('id'=>'customer_type_form', 'class'=>'form-horizontal')); ?>
	<fieldset>
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('customer_types_name'), 'name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-tag"></span></span>
					<?php echo form_input(array(
							'name'=>'name',
							'id'=>'name',
							'class'=>'form-control input-sm',
							'value'=>$customer_type_info->name)
							);?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('customer_types_description'), 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-align-left"></span></span>
					<?php echo form_textarea(array(
							'name'=>'description',
							'id'=>'description',
							'class'=>'form-control input-sm',
							'rows'=>'3',
							'value'=>$customer_type_info->description)
							);?>
				</div>
			</div>
		</div>
	</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	$('#customer_type_form').validate({
		submitHandler: function(form) {
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
			name: "<?php echo $this->lang->line('customer_types_name_required'); ?>"
		}
	});
});
</script>
