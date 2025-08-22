<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open($controller_name . '/save/' . $person_info->person_id, array('id'=>'delivery_man_form', 'class'=>'form-horizontal')); ?>
	<fieldset>
		<div class="form-group">
			<?php echo form_label($this->lang->line('common_first_name'), 'first_name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'first_name',
						'id'=>'first_name',
						'class'=>'form-control input-sm',
						'value'=>$person_info->first_name)
						);?>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_last_name'), 'last_name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'last_name',
						'id'=>'last_name',
						'class'=>'form-control input-sm',
						'value'=>$person_info->last_name)
						);?>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('delivery_men_username'), 'username', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'username',
						'id'=>'username',
						'class'=>'form-control input-sm',
						'value'=>$person_info->username)
						);?>
			</div>
		</div>

		<!-- Hidden password fields with default values -->
		<?php echo form_hidden('password', '12345678'); ?>
		<?php echo form_hidden('repeat_password', '12345678'); ?>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_gender'), 'gender', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<label class="radio-inline">
					<?php echo form_radio(array(
							'name'=>'gender',
							'value'=>'M',
							'checked'=>($person_info->gender == 'M'))
							); ?> <?php echo $this->lang->line('common_gender_male'); ?>
				</label>
				<label class="radio-inline">
					<?php echo form_radio(array(
							'name'=>'gender',
							'value'=>'F',
							'checked'=>($person_info->gender == 'F'))
							); ?> <?php echo $this->lang->line('common_gender_female'); ?>
				</label>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_email'), 'email', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'email',
						'id'=>'email',
						'class'=>'form-control input-sm',
						'value'=>$person_info->email)
						);?>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_phone_number'), 'phone_number', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'phone_number',
						'id'=>'phone_number',
						'class'=>'form-control input-sm',
						'value'=>$person_info->phone_number)
						);?>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_address_1'), 'address_1', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'address_1',
						'id'=>'address_1',
						'class'=>'form-control input-sm',
						'value'=>$person_info->address_1)
						);?>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_address_2'), 'address_2', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'address_2',
						'id'=>'address_2',
						'class'=>'form-control input-sm',
						'value'=>$person_info->address_2)
						);?>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_city'), 'city', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'city',
						'id'=>'city',
						'class'=>'form-control input-sm',
						'value'=>$person_info->city)
						);?>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_state'), 'state', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'state',
						'id'=>'state',
						'class'=>'form-control input-sm',
						'value'=>$person_info->state)
						);?>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_zip'), 'zip', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'zip',
						'id'=>'zip',
						'class'=>'form-control input-sm',
						'value'=>$person_info->zip)
						);?>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_country'), 'country', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'country',
						'id'=>'country',
						'class'=>'form-control input-sm',
						'value'=>$person_info->country)
						);?>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_label($this->lang->line('common_comments'), 'comments', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
						'name'=>'comments',
						'id'=>'comments',
						'class'=>'form-control input-sm',
						'value'=>$person_info->comments,
						'rows'=>'3')
						);?>
			</div>
		</div>
	</fieldset>


<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	// Add hidden field for delivery_man_id to track if it's new or existing
	$('#delivery_man_form').append('<input type="hidden" id="delivery_man_id" value="<?php echo $delivery_man_id; ?>">');

	// Set default password values when form loads
	// These lines are no longer needed as password fields are not hidden
	// $('#password').val('12345678');
	// $('#repeat_password').val('12345678');

	$('#delivery_man_form').validate({
		submitHandler: function(form) {
			// Debug: Log form data before submission
			var formData = $(form).serialize();
			console.log('Form data being submitted:', formData);
			console.log('Password field value:', $('#password').val());
			console.log('Repeat password field value:', $('#repeat_password').val());
			
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
			first_name: 'required',
			last_name: 'required',
			username: 'required',
			email: 'email'
		},

		messages:
		{
			first_name: "<?php echo $this->lang->line('common_first_name_required'); ?>",
			last_name: "<?php echo $this->lang->line('common_last_name_required'); ?>",
			username: "<?php echo $this->lang->line('delivery_men_username_required'); ?>",
			email: "<?php echo $this->lang->line('common_email_invalid_format'); ?>"
		},

		errorPlacement: function(error, element) {
			error.insertAfter(element);
		}
	});
});
</script>
