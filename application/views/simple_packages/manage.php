<?php $this->load->view('partial/header'); ?>

<script type="text/javascript">
$(document).ready(function()
{
	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	// Action formatter for edit column
	window.actionFormatter = function(value, row, index) {
		return '<a href="<?php echo site_url($controller_name); ?>/view/' + row.package_id + '" class="modal-dlg" data-btn-submit="<?php echo $this->lang->line('common_submit'); ?>" title="Update Package"><span class="glyphicon glyphicon-edit"></span></a>';
	};

	table_support.init({
		resource: '<?php echo site_url($controller_name);?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'package_id',
		enableActions: function()
		{
			var count = $("#table tbody tr.selected").length;
			if (count > 0) {
				$("#delete").prop("disabled", false);
			} else {
				$("#delete").prop("disabled", true);
			}
		}
	});
});
</script>

<div id="title_bar" class="btn-toolbar">
	<button class='btn btn-info btn-sm pull-right modal-dlg modal-dlg-lg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("$controller_name/view"); ?>'
			title='<?php echo $this->lang->line($controller_name . '_new'); ?>'>
		<span class="glyphicon glyphicon-plus">&nbsp;</span><?php echo $this->lang->line($controller_name. '_new'); ?>
	</button>
</div>

<div id="toolbar">
	<div class="pull-left form-inline" role="toolbar">
		<button id="delete" class="btn btn-default btn-sm" disabled="disabled">
			<span class="glyphicon glyphicon-trash">&nbsp;</span><?php echo $this->lang->line("common_delete"); ?>
		</button>
	</div>
</div>

<div id="table_holder">
	<table id="table"></table>
</div>

<?php $this->load->view('partial/footer'); ?>
