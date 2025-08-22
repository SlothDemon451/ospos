<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
$(document).ready(function()
{
	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e) {
		table_support.refresh();
	});
	
	// load the preset datarange picker
	<?php $this->load->view('partial/daterangepicker'); ?>

	$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
		table_support.refresh();
	});

	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	table_support.query_params = function()
	{
		return {
			start_date: start_date,
			end_date: end_date,
			filters: $("#filters").val() || [""]
		}
	};

	table_support.init({
		resource: '<?php echo site_url($controller_name);?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'sale_id',
		onLoadSuccess: function(response) {
			if($("#table tbody tr").length > 1) {
				$("#payment_summary").html(response.payment_summary);
				$("#table tbody tr:last td:first").html("");
			}
		},
		queryParams: function() {
			return $.extend(arguments[0], table_support.query_params());
		},
		columns: {
			'invoice': {
				align: 'center'
			}
		}
	});
});
</script>

<?php $this->load->view('partial/print_receipt', array('print_after_sale'=>false, 'selected_printer'=>'takings_printer')); ?>

<div id="title_bar" class="print_hide btn-toolbar">
	<button onclick="javascript:printdoc()" class='btn btn-info btn-sm pull-right'>
		<span class="glyphicon glyphicon-print">&nbsp</span><?php echo $this->lang->line('common_print'); ?>
	</button>
	<?php echo anchor("sales", '<span class="glyphicon glyphicon-shopping-cart">&nbsp</span>' . $this->lang->line('sales_register'), array('class'=>'btn btn-info btn-sm pull-right', 'id'=>'show_sales_button')); ?>
</div>

<div id="toolbar">
<?php if(isset($filters)): ?>
	<div class="pull-left form-inline" role="toolbar">
		<button id="delete" class="btn btn-default btn-sm print_hide">
			<span class="glyphicon glyphicon-trash">&nbsp</span><?php echo $this->lang->line("common_delete");?>
		</button>

		<?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
		<?php echo form_multiselect('filters[]', $filters, '', array('id'=>'filters', 'data-none-selected-text'=>$this->lang->line('common_none_selected_text'), 'class'=>'selectpicker show-menu-arrow', 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
	</div>
<?php endif; ?>
</div>

<?php if((isset($customer_info) || isset($delivery_man_info)) && isset($sales)): ?>
    <h3><?php echo $title; ?></h3>
    
    <?php if(isset($customer_info)): ?>
    <a href="<?php echo site_url('customers/apply_payment/' . $customer_info->person_id); ?>"
       class="btn btn-success btn-sm modal-dlg"
       data-btn-submit="Apply Payment"
       title="Apply Payment to Dues"
       style="margin-bottom:10px;">
        <span class="glyphicon glyphicon-credit-card"></span> Apply Payment to Dues
    </a>
    <?php endif; ?>
    <form method="get" class="form-inline" style="margin-bottom:10px;" id="filter_form">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" class="form-control input-sm" value="<?php echo isset($start_date) ? htmlspecialchars($start_date) : (isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : date('Y-m-d')); ?>">
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" class="form-control input-sm" value="<?php echo isset($end_date) ? htmlspecialchars($end_date) : (isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : date('Y-m-d')); ?>">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <button type="button" class="btn btn-default btn-sm" id="clear_filters_btn">Clear Filter</button>
        <?php if(isset($delivery_man_info)) : ?>
        <a class="btn btn-primary btn-sm" style="margin-left:10px;" href="<?php echo site_url('delivery_men/print_receipts/'.$delivery_man_info->person_id.'?start_date='.(isset($start_date)?$start_date:date('Y-m-d')).'&end_date='.(isset($end_date)?$end_date:date('Y-m-d'))); ?>" target="_blank">
            <span class="glyphicon glyphicon-print"></span> Print All Receipts
        </a>
        <?php endif; ?>
    </form>
    <!-- Search bar only -->
    <div class="row" style="margin-bottom:10px;">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-search"></span>
                </span>
                <input type="text" id="sales_search" class="form-control" placeholder="Search sales...">
            </div>
        </div>
    </div>
    
    <div id="table_holder">
        <table class="table table-bordered table-hover" id="customer_sales_table">
            <thead>
                <tr>
                    <?php 
                    $headers = json_decode($table_headers, true);
                    foreach($headers as $header) {
                        // Remove select and any extra/empty columns
                        if (strtolower($header['title']) == 'select' || empty(trim($header['title']))) continue;
                        echo '<th>' . $header['title'] . '</th>';
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sales)): ?>
                    <tr><td colspan="<?php echo count($headers); ?>" class="text-center">No sales found.</td></tr>
                <?php else: ?>
                    <?php 
                    $sum = array();
                    $filtered_headers = array();
                    foreach($headers as $header) {
                        if (strtolower($header['title']) == 'select' || empty(trim($header['title']))) continue;
                        $sum[$header['field']] = 0;
                        $filtered_headers[] = $header;
                    }
                    $row_data = array();
                    foreach($sales as $sale):
                        $sale = $this->Sale->recalculate_fixed_tax_totals($sale);
                        $row = get_sale_data_row($sale, 'sales');
                        foreach($filtered_headers as $header) {
                            $field = $header['field'];
                            if (in_array($field, ['subtotal','tax','total','amount_tendered','change_due'])) {
                                $sum[$field] += floatval(str_replace([',', '€', '$', '£'], '', strip_tags(isset($sale->$field) ? $sale->$field : 0)));
                            }
                        }
                        $row_data[] = $row;
                    endforeach;
                    ?>
                    <!-- Render all rows -->
                    <?php foreach($row_data as $i => $row): ?>
                        <tr>
                            <?php foreach($filtered_headers as $header): ?>
                                <td><?php echo isset($row[$header['field']]) ? $row[$header['field']] : ''; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    <!-- Stats row at the bottom -->
                    <tr style="font-weight:bold; background:#f5f5f5;">
                        <?php foreach($filtered_headers as $i => $header): ?>
                            <?php if($i == 0): ?><td>-</td>
                            <?php elseif($i == 1): ?><td><?php echo $this->lang->line('sales_total'); ?></td>
                            <?php elseif(in_array($header['field'], ['subtotal','tax','total','amount_tendered','change_due'])): ?>
                                <td><?php echo to_currency($sum[$header['field']]); ?></td>
                            <?php else: ?><td></td><?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="row">
            <div class="col-md-6">
                <div id="entries_summary" class="dataTables_info" role="status" aria-live="polite">
                    Showing all entries
                </div>
            </div>
        </div>
    </div>
    <script>
    // Simple search functionality for customer sales table
    function updateTableDisplay() {
        var filter = document.getElementById('sales_search').value.toLowerCase();
        var rows = document.querySelectorAll('#customer_sales_table tbody tr');
        var dataRows = [];
        var totalRows = 0;
        
        // Count actual data rows (exclude stats row and any empty rows)
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            // Skip the stats row (has background color) and any empty rows
            if (!row.style.backgroundColor && !row.style.background && 
                row.textContent.trim() !== '' && 
                !row.textContent.includes('No sales found')) {
                dataRows.push(row);
                totalRows++;
            }
        }
        
        var visible = 0;
        
        dataRows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            if (filter === '' || text.includes(filter)) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update summary
        var summaryText = '';
        if (totalRows === 0) {
            summaryText = 'No entries found';
        } else if (filter === '') {
            summaryText = 'Showing all ' + totalRows + ' entries';
        } else if (visible === 0) {
            summaryText = 'No entries found matching "' + filter + '"';
        } else {
            summaryText = 'Showing ' + visible + ' of ' + totalRows + ' entries';
        }
        
        document.getElementById('entries_summary').textContent = summaryText;
    }
    
    // Initialize table display
    document.addEventListener('DOMContentLoaded', function() {
        updateTableDisplay();
        
        // Add event listener for search
        document.getElementById('sales_search').addEventListener('input', updateTableDisplay);

        // Clear filter resets date inputs to today and submits the form
        var clearBtn = document.getElementById('clear_filters_btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                var today = new Date().toISOString().slice(0,10);
                var sd = document.getElementById('start_date');
                var ed = document.getElementById('end_date');
                if (sd) sd.value = today;
                if (ed) ed.value = today;
                document.getElementById('filter_form').submit();
            });
        }
    });
    </script>
<?php else: ?>
<div id="table_holder">
	<table id="table"></table>
</div>
<div id="payment_summary">
</div>
<?php endif; ?>

<?php $this->load->view("partial/footer"); ?>

