<?php

require 'config.php';

$action = $_POST['action'];

// начинаем заказ
if ($action == 'open_order') {

    $date_create = date('Y-m-d');
    mysqli_query($connection, "INSERT INTO orders (json_order, date_create) VALUES ('', '$date_create')");
    $order_id = mysqli_insert_id($connection);

    echo json_encode(['order_id' => $order_id]);
}

// заканчиваем заказ
if ($action == 'close_order') {
    $order_id = $_POST['order_id'];
    $query = mysqli_query($connection, "SELECT order_goods.id, order_goods.good_count, order_goods.good_sum, goods.good_name, goods.good_price, goods.good_name FROM order_goods INNER JOIN goods ON (order_goods.good_id = goods.id) WHERE order_goods.order_id = $order_id");

    $order_goods = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $order_goods[] = $row;
    }

    $json_order_goods = json_encode($order_goods);

    // сохраняем товары в json-формате
    mysqli_query($connection, "UPDATE orders SET json_order = '$json_order_goods' WHERE id = $order_id");

    $json_response = json_encode(['ordered_goods' => $json_order_goods]);

    echo $json_response;
}

// смотрим уже закрытый заказ
if ($action == 'view_order') {
    $order_id = $_POST['order_id'];
    // берем готовый json, который сохраняли при закрытии заказа
    $query = mysqli_query($connection, "SELECT * FROM orders WHERE id = $order_id");

    $order_goods = [];

    $row = mysqli_fetch_assoc($query);

    $json_response = json_encode(['ordered_goods' => $row['json_order']]);

    echo $json_response;
}