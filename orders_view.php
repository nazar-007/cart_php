<!DOCTYPE html>
<html lang="en">
<head>
    <title>Orders view</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>


<div class="container">

    <div class="row">
        <div class="col-sm-6">
            <h3>Последние 20 заказов</h3>
        </div>
        <div class="col-sm-6">
            <a href="index.php">К списку товаров</a>
        </div>
    </div>

    <table class="table table-striped">

        <thead>
        <tr>
            <th>#</th>
            <th>View</th>
        </tr>
        </thead>

        <tbody>

        <?php

        require 'config.php';

        $query = mysqli_query($connection, "SELECT * FROM orders ORDER BY id DESC LIMIT 20");

        $orders = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $orders[] = $row;
        }

        foreach ($orders as $order): ?>

        <tr>
            <td>Заказ №<?php echo $order['id'];?></td>
            <td>
                <button type="button" data-toggle="modal" data-target="#viewOrderModal" class="btn btn-info" data-order_id="<?php echo $order['id']?>" onclick="viewOrder(this)">
                    <span class="glyphicon glyphicon-asterisk"></span>
                </button>

            </td>
        </tr>

        <?php endforeach; ?>
        </tbody>

    </table>
</div>

<div class="modal fade" id="viewOrderModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр заказа</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Название товара</th>
                        <th>Кол-во</th>
                        <th>Сумма</th>
                    </tr>
                    </thead>
                    <tbody id="showOrderedGoods"></tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            Итого:
                            <span id="common_sum_table"></span>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<script>

    function viewOrder(context) {
        var order_id = context.getAttribute('data-order_id');

        if (!order_id) {
            alert("Не найден номер заказа!");
            return false;
        }

        $.ajax({
            method: "POST",
            url: "order.php",
            data: {action: 'view_order', order_id: order_id},
            dataType: "JSON"
        }).done(function (data) {

            var ordered_goods = JSON.parse(data.ordered_goods);
            var html = '';
            var common_sum = 0;

            for (var i = 0; i < ordered_goods.length; i++) {

                common_sum = common_sum + parseInt(ordered_goods[i].good_sum);

                html = html +
                    "<tr>" +
                    "<td>" + ordered_goods[i].good_name + "</td>" +
                    "<td>" + ordered_goods[i].good_count + "</td>" +
                    "<td>" + ordered_goods[i].good_sum + "</td>" +
                    "</tr>";
            }

            $("#showOrderedGoods").html(html);
            $("#common_sum_table").html(common_sum);

        });
    }

</script>

</body>
</html>