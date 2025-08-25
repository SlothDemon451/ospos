<?php $this->load->view('partial/header'); ?>
<div class="container" style="max-width: 500px; margin-top: 40px;">
    <div class="card">
        <div class="card-header bg-primary text-white" style="padding: 10px; border-radius: 10px; margin-bottom: 10px;">
            <h4 class="mb-0" style="color: white;">Apply Payment to Dues</h4>
            <h4 style="color: white;">Customer: <b style="color: white;"><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></b></h4>
        </div>
        <div class="card-body">
            <?php if (isset($outstanding_balance) && $outstanding_balance > 0): ?>
                <div class="alert alert-info mb-3">
                    <strong>Outstanding Balance:</strong> <?php echo to_currency($outstanding_balance); ?>
                </div>
            <?php elseif (isset($outstanding_balance) && $outstanding_balance == 0): ?>
                <div class="alert alert-success mb-3">
                    <strong>No Outstanding Balance</strong><br>
                    This customer has no outstanding dues.
                </div>
            <?php endif; ?>
            
            <form method="post" id="paymentForm">
                <?php if (isset(
                    $this->security) && method_exists($this->security, 'get_csrf_token_name')): ?>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                <?php endif; ?>
                <div class="form-group mb-3">
                    <label for="amount">Payment Amount</label>
                    <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control" required 
                           <?php if (isset($outstanding_balance) && $outstanding_balance > 0): ?>
                           max="<?php echo $outstanding_balance; ?>"
                           placeholder="Max: <?php echo to_currency($outstanding_balance); ?>"
                           <?php endif; ?>>
                    <?php if (isset($outstanding_balance) && $outstanding_balance > 0): ?>
                        <small class="form-text text-muted">Maximum payment amount: <?php echo to_currency($outstanding_balance); ?></small>
                        <div id="amountFeedback" class="invalid-feedback"></div>
                    <?php endif; ?>
                </div>
                <div class="form-group mb-3">
                    <label for="payment_type">Payment Type</label>
                    <select name="payment_type" id="payment_type" class="form-control" required>
                        <option value="Cash">Cash</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Check">Check</option>
                        <option value="Due">Due</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label for="reference_code">Reference Code (optional)</label>
                    <input type="text" name="reference_code" id="reference_code" class="form-control">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block" 
                            <?php if (isset($outstanding_balance) && $outstanding_balance <= 0): ?>disabled<?php endif; ?>>
                        <?php if (isset($outstanding_balance) && $outstanding_balance > 0): ?>
                            Apply Payment
                        <?php else: ?>
                            No Outstanding Balance
                        <?php endif; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const amountFeedback = document.getElementById('amountFeedback');
    const submitButton = document.querySelector('button[type="submit"]');
    
    <?php if (isset($outstanding_balance) && $outstanding_balance > 0): ?>
    const maxAmount = <?php echo $outstanding_balance; ?>;
    
    amountInput.addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        
        if (amount > maxAmount) {
            this.classList.add('is-invalid');
            amountFeedback.textContent = 'Payment amount cannot exceed outstanding balance of ' + 
                '<?php echo to_currency($outstanding_balance); ?>';
            submitButton.disabled = true;
        } else if (amount <= 0) {
            this.classList.add('is-invalid');
            amountFeedback.textContent = 'Payment amount must be greater than 0';
            submitButton.disabled = true;
        } else {
            this.classList.remove('is-invalid');
            amountFeedback.textContent = '';
            submitButton.disabled = false;
        }
    });
    
    // Form submission validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const amount = parseFloat(amountInput.value) || 0;
        
        if (amount > maxAmount) {
            e.preventDefault();
            alert('Payment amount cannot exceed outstanding balance of <?php echo to_currency($outstanding_balance); ?>');
            return false;
        }
        
        if (amount <= 0) {
            e.preventDefault();
            alert('Payment amount must be greater than 0');
            return false;
        }
    });
    <?php endif; ?>
});
</script>

<?php $this->load->view('partial/footer'); ?> 