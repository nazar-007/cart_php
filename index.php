<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cart</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">

    <div class="row" style="margin-bottom: 30px;">
        <div class="col-sm-4">
            <a href="orders_view.php" class="btn btn-info">История заказов</a>
        </div>

        <div id="order" class="col-sm-4">
            <button class="btn btn-success" onclick="openOrder()">Начать заказ</button>
        </div>

        <div class="col-sm-4">
            Общая сумма заказанных товаров:
            <h3 id="common_sum" style="color: blue;">0</h3>
            <button id="close_order" type="button" data-toggle="modal" data-target="#closeOrderModal" class="hidden btn btn-success" onclick="closeOrder()">Завершить заказ</button>
        </div>
    </div>

    <div class="row">
        <?php
        require 'config.php';

        $query = mysqli_query($connection, "SELECT * FROM goods");

        $goods = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $goods[] = $row;
        }

        // выводим все товары
        foreach ($goods as $good):

        ?>

        <div class="col-sm-4 text-center">
            <div>
                <img style="width: 200px;" src="<?php echo $good['good_image'] ?>" />
            </div>
            <h3><?php echo $good['good_name'];?></h3>
            <div data-good_id="<?php echo $good['id'];?>" data-good_price="<?php echo $good['good_price'];?>">
                <button data-action="minus" class="btn btn-danger minus_good" onclick="sendToCart(this)">-</button>
                <span class="good-price"><?php echo $good['good_price'];?></span>
                <button data-action="plus" class="btn btn-success plus_good" onclick="sendToCart(this)">+</button>
                <p>
                    Кол-во:
                    <span id="good_count_<?php echo $good['id'];?>">0</span>
                </p>
                <p>
                    Итого:
                    <span class="good_sum" id="good_sum_<?php echo $good['id'];?>">0</span>
                </p>
            </div>
        </div>

        <?php endforeach; ?>


    </div>
</div>

<div class="modal fade" id="closeOrderModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Заказанные товары</h4>
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

    // функция добавления или отнимания товаров
    function sendToCart(context) {
        var order_id = document.getElementById('order_id');

        if (!order_id) {
            alert("Чтобы добавлять товары в корзину, нажмите на \"Начать заказ\"!");
            return false;
        }

        order_id = order_id.innerHTML;

        // action - либо минус, либо плюс (в зависимости от action идет прибавление или отнимание)
        var action = context.getAttribute('data-action');
        var good_id = context.parentElement.getAttribute('data-good_id');
        var good_price = context.parentElement.getAttribute('data-good_price');

        // в параметре data отправляются order_id и good_id для проверки на существование товара в корзине
        $.ajax({
            method: "POST",
            url: "cart.php",
            data: {order_id: order_id, action: action, good_id: good_id, good_price: good_price},
            dataType: "JSON"
        }).done(function (data) {

            if (data.good_count && data.good_sum) {
                $("#good_count_" + good_id).html(data.good_count);
                $("#good_sum_" + good_id).html(data.good_sum);
            }

            // подсчет итоговой суммы после того, как нажали на кнопку плюс или минус
            commonSum();
        });

    }

    // функция, создающая новый заказ для добавления товаров
    function openOrder() {
        $.ajax({
            method: "POST",
            url: "order.php",
            data: {action: 'open_order'},
            dataType: "JSON"
        }).done(function (data) {
            $("#order").html("Заказ № <span id='order_id'>" + data.order_id + "</span>");
        });

    }

    // функция для закрытия заказа
    function closeOrder() {
        var order_id = document.getElementById('order_id');

        if (!order_id) {
            alert("Не найден номер заказа!");
            return false;
        }

        order_id = order_id.innerHTML;


        $.ajax({
            method: "POST",
            url: "order.php",
            data: {action: 'close_order', order_id: order_id},
            dataType: "JSON"
        }).done(function (data) {

            var ordered_goods = JSON.parse(data.ordered_goods);
            var html = '';
            var common_sum = 0;

            // создаем столбцы для заказанных товаров
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

    // подсчет общей суммы заказанных товаров
    function commonSum() {

        var good_sum = document.getElementsByClassName('good_sum');
        var common_sum = 0;

        for (var i = 0; i < good_sum.length; i++) {
            common_sum = common_sum + parseInt(good_sum[i].innerHTML);
        }

        if (common_sum > 0) {
            document.getElementById('close_order').classList.remove('hidden');
        } else {
            document.getElementById('close_order').classList.add('hidden');
        }

        document.getElementById('common_sum').innerHTML = common_sum;

    }

    // при закрытии модалки после закрытия заказа страница обновляется
    $('#closeOrderModal').on('hide.bs.modal', function(){
        location.reload();
    });


</script>


</body>
</html>
