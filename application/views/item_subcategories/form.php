<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('item_subcategories/save/'.(isset($subcategory_info->id) ? $subcategory_info->id : ''), array('id'=>'item_subcategory_edit_form', 'class'=>'form-horizontal')); ?>
	<fieldset id="item_subcategories">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('item_subcategories_category_id'), 'category_id', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_dropdown('category_id', array_column($categories, 'name', 'id'), isset($subcategory_info->category_id) ? $subcategory_info->category_id : '', 'class="form-control"'); ?>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('item_subcategories_name'), 'name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
					'name'=>'name',
					'id'=>'name',
					'class'=>'form-control input-sm',
					'value'=>isset($subcategory_info->name) ? $subcategory_info->name : ''
					));?>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('item_subcategories_description'), 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
					'name'=>'description',
					'id'=>'description',
					'class'=>'form-control input-sm',
					'value'=>isset($subcategory_info->description) ? $subcategory_info->description : ''
					));?>
			</div>
		</div>
	</fieldset>
<?php echo form_close(); ?>

<script type='text/javascript'>
$(document).ready(function()
{
	$('#item_subcategory_edit_form').validate($.extend({
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
			category_id: 'required',
			name: 'required'
		},
		messages:
		{
			category_id: "<?php echo $this->lang->line('item_subcategories_category_id').' '.$this->lang->line('common_required'); ?>",
			name: "<?php echo $this->lang->line('item_subcategories_name').' '.$this->lang->line('common_required'); ?>"
		}
	}, form_support.error));
});
</script> 