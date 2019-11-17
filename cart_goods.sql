-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 17 2019 г., 20:23
-- Версия сервера: 10.1.37-MariaDB
-- Версия PHP: 7.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cart_goods`
--

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `good_name` varchar(255) NOT NULL,
  `good_price` int(11) NOT NULL,
  `good_image` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `good_name`, `good_price`, `good_image`) VALUES
(1, 'Bounty', 200, 'https://avatars.mds.yandex.net/get-mpic/1673800/img_id5706890973547757802.png/9hq'),
(2, 'Fanta', 360, 'https://www.adileepizza.com/wp-content/uploads/2018/12/fanta-600x600.jpg'),
(3, 'Merci', 745, 'https://images.ua.prom.st/754580098_w640_h640_shokoladnye-konfety-merci.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `json_order` text NOT NULL,
  `date_create` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `json_order`, `date_create`) VALUES
(1, '[{\"id\":\"1\",\"good_count\":\"4\",\"good_sum\":\"1440\",\"good_name\":\"Fanta\",\"good_price\":\"360\"},{\"id\":\"2\",\"good_count\":\"3\",\"good_sum\":\"600\",\"good_name\":\"Bounty\",\"good_price\":\"200\"},{\"id\":\"3\",\"good_count\":\"10\",\"good_sum\":\"7450\",\"good_name\":\"Merci\",\"good_price\":\"745\"}]', '2019-11-17'),
(2, '[{\"id\":\"4\",\"good_count\":\"12\",\"good_sum\":\"8940\",\"good_name\":\"Merci\",\"good_price\":\"745\"}]', '2019-11-17'),
(3, '[{\"id\":\"5\",\"good_count\":\"4\",\"good_sum\":\"1440\",\"good_name\":\"Fanta\",\"good_price\":\"360\"},{\"id\":\"6\",\"good_count\":\"19\",\"good_sum\":\"3800\",\"good_name\":\"Bounty\",\"good_price\":\"200\"}]', '2019-11-17'),
(4, '[{\"id\":\"7\",\"good_count\":\"14\",\"good_sum\":\"2800\",\"good_name\":\"Bounty\",\"good_price\":\"200\"},{\"id\":\"8\",\"good_count\":\"5\",\"good_sum\":\"1800\",\"good_name\":\"Fanta\",\"good_price\":\"360\"},{\"id\":\"9\",\"good_count\":\"8\",\"good_sum\":\"5960\",\"good_name\":\"Merci\",\"good_price\":\"745\"}]', '2019-11-17'),
(5, '[{\"id\":\"10\",\"good_count\":\"2\",\"good_sum\":\"720\",\"good_name\":\"Fanta\",\"good_price\":\"360\"}]', '2019-11-17');

-- --------------------------------------------------------

--
-- Структура таблицы `order_goods`
--

CREATE TABLE `order_goods` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `good_id` int(11) NOT NULL,
  `good_count` int(11) NOT NULL,
  `good_sum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `order_goods`
--

INSERT INTO `order_goods` (`id`, `order_id`, `good_id`, `good_count`, `good_sum`) VALUES
(1, 1, 2, 4, 1440),
(2, 1, 1, 3, 600),
(3, 1, 3, 10, 7450),
(4, 2, 3, 12, 8940),
(5, 3, 2, 4, 1440),
(6, 3, 1, 19, 3800),
(7, 4, 1, 14, 2800),
(8, 4, 2, 5, 1800),
(9, 4, 3, 8, 5960),
(10, 5, 2, 2, 720);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_goods`
--
ALTER TABLE `order_goods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `order_goods`
--
ALTER TABLE `order_goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
