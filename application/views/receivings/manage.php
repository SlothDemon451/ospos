<?php $this->load->view('partial/header'); ?>

<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Manage Receivings</h3>
    </div>
    <div class="panel-body">
      <form method="get" class="form-inline" style="margin-bottom: 20px;">
        <div class="form-group">
          <label for="start_date">Start Date:</label>
          <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo $start_date; ?>">
        </div>
        <div class="form-group" style="margin-left: 10px;">
          <label for="end_date">End Date:</label>
          <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $end_date; ?>">
        </div>
        <div class="form-group" style="margin-left: 10px;">
          <label>
            <input type="checkbox" name="due_only" value="1" <?php echo $due_only ? 'checked' : ''; ?>>
            Show Due Only
          </label>
        </div>
        <button type="submit" class="btn btn-primary btn-sm" style="margin-left:10px;">Filter</button>
      </form>
    </div>
    <div class="panel-body">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Supplier</th>
            <th>Employee</th>
            <th class="text-right">Total</th>
            <th class="text-right">Paid</th>
            <th class="text-right">Amount Due</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if(empty($receivings)): ?>
          <tr><td colspan="8" class="text-center">No receivings found</td></tr>
        <?php else: ?>
          <?php foreach($receivings as $r): ?>
          <tr>
            <td><?php echo (int)$r->receiving_id; ?></td>
            <td><?php echo to_datetime(strtotime($r->receiving_time)); ?></td>
            <td><?php echo htmlspecialchars($r->supplier_name); ?></td>
            <td><?php echo htmlspecialchars($r->employee_name); ?></td>
            <td class="text-right"><?php echo to_currency($r->total); ?></td>
            <td class="text-right"><?php echo to_currency($r->payments_total); ?></td>
            <td class="text-right"><?php echo to_currency($r->amount_due); ?></td>
            <td>
              <a href="<?php echo site_url('receivings/receipt/'.(int)$r->receiving_id); ?>" target="_blank" class="btn btn-default btn-xs">Receipt</a>
              <button class="btn btn-success btn-xs apply-payment-btn" data-id="<?php echo (int)$r->receiving_id; ?>" data-due="<?php echo (float)$r->amount_due; ?>">Apply Payment</button>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="paymentModalLabel">Apply Payment</h4>
      </div>
      <div class="modal-body">
        <form id="paymentForm">
          <div class="alert alert-info">
            <strong>Outstanding Amount:</strong> <span id="outstanding_amount_display"></span>
          </div>
          <div class="form-group">
            <label for="payment_amount">Payment Amount</label>
            <div class="input-group">
              <span class="input-group-addon"><?php echo $this->config->item('currency_symbol'); ?></span>
              <input type="number" step="0.01" min="0.01" class="form-control" id="payment_amount" name="amount" required>
            </div>
            <small class="help-block">Enter the amount you want to pay</small>
          </div>
          <div class="form-group">
            <label for="payment_type">Payment Method</label>
            <select class="form-control" id="payment_type" name="payment_type" required>
              <option value="">Select Payment Method</option>
              <?php foreach($payment_options as $key => $value): ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
              <?php endforeach; ?>
              <!-- Additional business-specific payment methods for receivings -->
              <option value="Bank Transfer">Bank Transfer</option>
              <option value="Purchase Order">Purchase Order</option>
              <option value="Wire Transfer">Wire Transfer</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitPayment">Apply Payment</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  var currentReceivingId = null;
  var currentOutstandingAmount = 0;
  
  $('.apply-payment-btn').click(function(){
    currentReceivingId = $(this).data('id');
    currentOutstandingAmount = parseFloat($(this).data('due')) || 0;
    
    if(currentOutstandingAmount <= 0) {
      alert('This receiving has no outstanding amount to pay.');
      return;
    }
    
    // Set the outstanding amount in the modal
    $('#outstanding_amount_display').text('<?php echo $this->config->item('currency_symbol'); ?>' + currentOutstandingAmount.toFixed(2));
    
    // Set default payment amount to outstanding amount
    $('#payment_amount').val(currentOutstandingAmount.toFixed(2));
    
    // Reset form
    $('#paymentForm')[0].reset();
    $('#payment_amount').val(currentOutstandingAmount.toFixed(2));
    
    // Show modal
    $('#paymentModal').modal('show');
  });
  
  $('#submitPayment').click(function(){
    var amount = parseFloat($('#payment_amount').val());
    var paymentType = $('#payment_type').val();
    
    // Validation
    if(!amount || amount <= 0) {
      alert('Please enter a valid payment amount.');
      return;
    }
    
    if(!paymentType) {
      alert('Please select a payment method.');
      return;
    }
    
    if(amount > currentOutstandingAmount) {
      alert('Payment amount cannot exceed the outstanding amount of ' + currentOutstandingAmount.toFixed(2));
      return;
    }
    
    // Show loading state
    $('#submitPayment').prop('disabled', true).text('Processing...');
    
    // Submit payment
    $.ajax({
      url: '<?php echo site_url('receivings/apply_payment/'); ?>' + currentReceivingId,
      type: 'POST',
      data: {
        amount: amount,
        payment_type: paymentType
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          alert('Payment of ' + response.amount_paid + ' applied successfully. Outstanding amount: ' + response.outstanding_amount);
          $('#paymentModal').modal('hide');
          location.reload();
        } else {
          alert('Payment failed: ' + (response.message || 'Unknown error'));
        }
      },
      error: function(xhr, status, error) {
        console.error('Payment error:', error);
        alert('Payment failed. Please try again.');
      },
      complete: function() {
        // Reset button state
        $('#submitPayment').prop('disabled', false).text('Apply Payment');
      }
    });
  });
});
</script>

<?php $this->load->view('partial/footer'); ?>


