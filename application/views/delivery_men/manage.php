<?php
/**
 * @var array $delivery_men
 * @var string $controller_name
 */
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-piluku">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo $this->lang->line('delivery_men_title'); ?></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table id="table" class="table table-striped table-bordered">
								<thead>
									<tr>
										<!-- Headers will be loaded via AJAX -->
									</tr>
								</thead>
								<tbody>
									<!-- Data will be loaded via AJAX -->
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
	// enable sortable tables
	enable_sorting("<?php echo site_url("$controller_name/sort"); ?>");
	// enable search
	enable_search("<?php echo site_url("$controller_name/search"); ?>");
	// enable pagination
	enable_pagination("<?php echo site_url("$controller_name/pagination"); ?>");
	// enable delete
	enable_delete("<?php echo $this->lang->line('delivery_men_confirm_delete'); ?>", "<?php echo $this->lang->line('delivery_men_none_selected'); ?>");
});
</script>
