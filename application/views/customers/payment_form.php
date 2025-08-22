<?php $this->load->view('partial/header'); ?>
<div class="container" style="max-width: 500px; margin-top: 40px;">
    <div class="card">
        <div class="card-header bg-primary text-white" style="padding: 10px; border-radius: 10px; margin-bottom: 10px;">
            <h4 class="mb-0" style="color: white;">Apply Payment to Dues</h4>
            <h4 style="color: white;">Customer: <b style="color: white;"><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></b></h4>
        </div>
        <div class="card-body">
            <form method="post">
                <?php if (isset(
                    $this->security) && method_exists($this->security, 'get_csrf_token_name')): ?>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                <?php endif; ?>
                <div class="form-group mb-3">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control" required>
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
                    <button type="submit" class="btn btn-primary btn-block">Apply Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('partial/footer'); ?> 