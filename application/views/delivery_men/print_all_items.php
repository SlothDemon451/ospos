<?php $this->load->view("partial/header"); ?>

<style>
@media print {
  .no-print { display: none !important; }
  body { font-size: 12px; }
  .container { width: 100%; max-width: none; }
}
.delivery-items-header {
  text-align: center;
  margin-bottom: 20px;
  border-bottom: 2px solid #333;
  padding-bottom: 10px;
}
.delivery-items-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}
.delivery-items-table th,
.delivery-items-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}
.delivery-items-table th {
  background-color: #f5f5f5;
  font-weight: bold;
}
.delivery-items-table tr:nth-child(even) {
  background-color: #f9f9f9;
}
.summary-section {
  margin-top: 20px;
  padding: 15px;
  border: 1px solid #ddd;
  background-color: #f9f9f9;
}
.product-summary {
  margin-top: 10px;
  padding: 10px;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
}
.product-item {
  display: inline-block;
  margin-right: 15px;
  margin-bottom: 5px;
  padding: 5px 10px;
  background-color: #e9ecef;
  border-radius: 3px;
}
.print-button {
  margin: 20px 0;
  text-align: center;
}
</style>

<div class="container-fluid">
  <div class="delivery-items-header">
    <h2><?php echo $this->lang->line('delivery_men_items_summary'); ?></h2>
    <h3><?php echo htmlspecialchars($delivery_man_info->first_name . ' ' . $delivery_man_info->last_name); ?></h3>
    <p><strong><?php echo $this->lang->line('delivery_men_date_range'); ?>:</strong> <?php echo htmlspecialchars($start_date) . ' to ' . htmlspecialchars($end_date); ?></p>
    <p><strong><?php echo $this->lang->line('delivery_men_total_sales'); ?>:</strong> <?php echo $total_sales; ?> | <strong><?php echo $this->lang->line('delivery_men_total_quantity'); ?>:</strong> <?php echo $total_items; ?></p>
  </div>

  <?php if (empty($consolidated_items)): ?>
    <div class="alert alert-info">
      <?php echo $this->lang->line('delivery_men_no_items_found'); ?>
    </div>
  <?php else: ?>
    
    <!-- Simple Items Table -->
    <h3><?php echo $this->lang->line('delivery_men_all_items'); ?></h3>
    <table class="delivery-items-table">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Quantity</th>
          <th>Customer</th>
          <th>Sale ID</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($consolidated_items as $item): ?>
          <?php foreach ($item['sales'] as $sale): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['name']); ?></td>
              <td><?php echo $sale['quantity']; ?></td>
              <td><?php echo htmlspecialchars($sale['customer_name']); ?></td>
              <td><?php echo $sale['sale_id']; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
      <h4><?php echo $this->lang->line('common_summary'); ?></h4>
      <div class="row">
        <div class="col-md-6">
          <p><strong><?php echo $this->lang->line('delivery_men_total_unique_items'); ?>:</strong> <?php 
            // Count unique products by name, not by consolidated keys
            $unique_products = array();
            foreach ($consolidated_items as $item) {
              $unique_products[$item['name']] = true;
            }
            echo count($unique_products);
          ?></p>
          <p><strong><?php echo $this->lang->line('delivery_men_total_quantity'); ?>:</strong> <?php echo $total_items; ?></p>
        </div>
        <div class="col-md-6">
          <p><strong><?php echo $this->lang->line('delivery_men_total_sales'); ?>:</strong> <?php echo $total_sales; ?></p>
          <p><strong><?php echo $this->lang->line('delivery_men_date_range'); ?>:</strong> <?php echo htmlspecialchars($start_date) . ' to ' . htmlspecialchars($end_date); ?></p>
        </div>
      </div>
      
      <!-- Product Quantity Summary -->
      <h5><?php echo $this->lang->line('delivery_men_product_summary'); ?></h5>
      <div class="product-summary">
        <?php 
        $product_totals = array();
        foreach ($consolidated_items as $item) {
          $item_name = $item['name'];
          if (!isset($product_totals[$item_name])) {
            $product_totals[$item_name] = 0;
          }
          foreach ($item['sales'] as $sale) {
            $product_totals[$item_name] += $sale['quantity'];
          }
        }
        
        foreach ($product_totals as $product_name => $total_qty): ?>
          <span class="product-item">
            <strong><?php echo htmlspecialchars($product_name); ?></strong>: <?php echo $total_qty; ?>
          </span>
          <?php if (next($product_totals) !== false): ?>, <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>

  <?php endif; ?>

  <div class="print-button no-print">
    <button onclick="window.print()" class="btn btn-primary btn-sm">
      <span class="glyphicon glyphicon-print"></span> <?php echo $this->lang->line('delivery_men_print_all_items'); ?>
    </button>
    <a href="<?php echo site_url('delivery_men/sales/' . $delivery_man_info->person_id . '?start_date=' . $start_date . '&end_date=' . $end_date); ?>" class="btn btn-default btn-sm">
      <span class="glyphicon glyphicon-arrow-left"></span> <?php echo $this->lang->line('common_back'); ?>
    </a>
  </div>
</div>

<?php $this->load->view("partial/footer"); ?>
