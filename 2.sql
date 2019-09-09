-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 09 2019 г., 15:34
-- Версия сервера: 5.6.43-log
-- Версия PHP: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_samson`
--

-- --------------------------------------------------------

--
-- Структура таблицы `a_category`
--

CREATE TABLE `a_category` (
  `product_code` int(100) NOT NULL,
  `id` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `a_price`
--

CREATE TABLE `a_price` (
  `id` int(100) NOT NULL,
  `id_product` int(100) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `a_product`
--

CREATE TABLE `a_product` (
  `id` int(100) NOT NULL,
  `code` int(100) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `a_property`
--

CREATE TABLE `a_property` (
  `id` int(100) NOT NULL,
  `id_product` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `a_category`
--
ALTER TABLE `a_category`
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `product_code` (`product_code`),
  ADD KEY `id` (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `a_price`
--
ALTER TABLE `a_price`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_product` (`id_product`);

--
-- Индексы таблицы `a_product`
--
ALTER TABLE `a_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `code` (`code`);

--
-- Индексы таблицы `a_property`
--
ALTER TABLE `a_property`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_product` (`id_product`);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `a_category`
--
ALTER TABLE `a_category`
  ADD CONSTRAINT `a_category_ibfk_1` FOREIGN KEY (`product_code`) REFERENCES `a_product` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `a_category_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `a_category` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ограничения внешнего ключа таблицы `a_price`
--
ALTER TABLE `a_price`
  ADD CONSTRAINT `a_price_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `a_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `a_property`
--
ALTER TABLE `a_property`
  ADD CONSTRAINT `a_property_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `a_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
