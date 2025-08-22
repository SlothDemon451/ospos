<table class="table table-striped">
    <thead>
        <tr>
            <!-- Add other customer column headers as needed -->
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Sales</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($customers as $customer): ?>
        <tr>
            <!-- Add other customer columns as needed -->
            <td><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></td>
            <td><?php echo htmlspecialchars($customer->email); ?></td>
            <td><?php echo htmlspecialchars($customer->phone_number); ?></td>
            <td>
                <a href="<?php echo site_url('customers/sales/' . $customer->person_id); ?>" class="btn btn-info btn-xs" title="View Sales">
                    <span class="glyphicon glyphicon-list-alt"></span> Sales
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table> 