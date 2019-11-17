<?php

require 'config.php';

$order_id = $_POST['order_id'];
$action = $_POST['action'];
$good_id = $_POST['good_id'];
$good_price = $_POST['good_price'];

$query = mysqli_query($connection, "SELECT * FROM order_goods WHERE order_id = $order_id AND good_id = $good_id");

$num_rows = mysqli_num_rows($query);

// прибавляем товар
if ($action == 'plus') {
    if ($num_rows > 0) {
        $row = mysqli_fetch_assoc($query);
        $order_good_id = $row['id'];

        // если товар под определнным заказом в корзине уже существует, прибавляем к нему кол-во и сумму
        mysqli_query($connection, "UPDATE order_goods SET good_count = good_count + 1, good_sum = good_sum + $good_price WHERE id = $order_good_id");
    } else {
        // если товар еще не добавлен в корзину, просто добавляем новый товар
        mysqli_query($connection, "INSERT INTO order_goods (order_id, good_id, good_count, good_sum) VALUES ($order_id, $good_id, 1, $good_price)");

        $order_good_id = mysqli_insert_id($connection);
    }

}

// отнимаем товар
if ($action == 'minus') {
    if ($num_rows > 0) {
        $row = mysqli_fetch_assoc($query);
        $order_good_id = $row['id'];

        $order_good_count = $row['good_count'];
        $order_good_sum = $row['good_sum'];

        // отнимаем товар только в том случае, если кол-во больше 1 и сумма больше или равна 0
        if ($order_good_count > 0 && $order_good_sum >= 0) {
            mysqli_query($connection, "UPDATE order_goods SET good_count = good_count - 1, good_sum = good_sum - $good_price WHERE id = $order_good_id");
        }
    }

}

// отображаем обновленные данные товара, на который мы нажали
$order_good_query = mysqli_query($connection, "SELECT * FROM order_goods WHERE id = $order_good_id");
$order_good_row = mysqli_fetch_assoc($order_good_query);
$json_response = json_encode($order_good_row);
echo $json_response;