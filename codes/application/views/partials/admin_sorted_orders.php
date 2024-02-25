<thead>
    <tr>
        <th>Order ID #</th>
        <th>Order Date</th>
        <th>Receiver</th>
        <th>Total Amount</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>
<?php
    foreach($orders as $order)
    {
?>
    <tr>
        <td>
            <span><a href="#"><?= $order['id'] ?></a></span>
        </td>
        <td><span><?= $order['created_at'] ?></span></td>
        <td>
            <span><?= $order['receiver'] ?><span><?= $order['shipping_address'] ?></span></span>
        </td>
        <td><span>$ <?= number_format($order['total_amount'], 2) ?></span></td>
        <td>
            <form class="status_update_form" action="/admins/update_status" method="post">
                <select class="selectpicker" name="status" >
<?php
    foreach($statuses as $status)
    {
        if($status['status_id'] == $order['status_id'])
        {
?>

                    <option value="<?= $status['status_id'] ?>" selected><?= $status['status'] ?></option>
<?php
        }
        else
        {
?>
                    <option value="<?= $status['status_id'] ?>"><?= $status['status'] ?></option>
<?php
        }
    }
?>
                </select>
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            </form>
        </td>
    </tr>
<?php
    }
?>
</tbody>
