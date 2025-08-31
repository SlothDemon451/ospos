<?php $this->load->view('partial/header'); ?>
<div class="container" style="max-width: 600px; margin-top: 40px;">
    <div class="card">
        <div class="card-header bg-success text-white" style="padding: 10px; border-radius: 10px;">
            <h4 class="mb-0" style="color: white;">Payment Allocation Result</h4>
            <h4 style="color: white;">Customer: <b style="color: white;"><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></b></h4>
        </div>
        <div class="card-body" style="margin-top: 10px;">
            <p class="mb-3">Payment Amount: <strong style="font-size: 1.2em;"><?php echo to_currency($amount); ?></strong> (<?php echo htmlspecialchars($payment_type); ?>)</p>

            <?php if (empty($allocations)): ?>
                <div class="alert alert-info">No outstanding dues were found for this customer. No payment was applied.</div>
            <?php else: ?>
                <h5>Payment Allocations:</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sale ID</th>
                                <th>Amount Paid</th>
                                <th>Due Before</th>
                                <th>Due After</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allocations as $allocation): ?>
                                <tr>
                                    <td><?php echo $allocation['sale_id']; ?></td>
                                    <td><?php echo to_currency($allocation['paid']); ?></td>
                                    <td><?php echo to_currency($allocation['due_before']); ?></td>
                                    <td><?php echo to_currency($allocation['due_after']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($remaining > 0): ?>
                    <div class="alert alert-warning">
                        <strong>Unused Payment Amount:</strong> <?php echo to_currency($remaining); ?>
                        <br><small>This amount was not applied as all outstanding dues have been paid.</small>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <div class="mt-4">
                <a href="<?php echo site_url('customers/sales/' . $customer->person_id); ?>" class="btn btn-primary">Back to Customer</a>
                <a href="<?php echo site_url('customers/apply_payment/' . $customer->person_id); ?>" class="btn btn-primary">Apply Another Payment</a>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('partial/footer'); ?> 