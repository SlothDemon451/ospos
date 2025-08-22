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
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Sale ID</th>
                            <th>Paid</th>
                            <th>Due Before</th>
                            <th>Due After</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allocations as $alloc): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($alloc['sale_id']); ?></td>
                                <td><?php echo to_currency($alloc['paid']); ?></td>
                                <td><?php echo to_currency($alloc['due_before']); ?></td>
                                <td><?php echo to_currency($alloc['due_after']); ?></td>
                                <td>
                                    <a href="<?php echo site_url('sales/receipt/' . $alloc['sale_id']); ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <span class="glyphicon glyphicon-print"></span> Print Receipt
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if ($remaining > 0): ?>
                    <div class="alert alert-warning">Unallocated amount remaining: <?php echo to_currency($remaining); ?></div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="mt-4 d-grid gap-2">
                <a href="<?php echo site_url('customers'); ?>" class="btn btn-primary btn-block">Back to Customers</a>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('partial/footer'); ?> 