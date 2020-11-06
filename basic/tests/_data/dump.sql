-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 06 2020 г., 17:42
-- Версия сервера: 8.0.21
-- Версия PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `bpt-ais`
--

-- --------------------------------------------------------

--
-- Структура таблицы `direct`
--

CREATE TABLE `direct` (
  `id` int NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` set('СПО','НПО') NOT NULL DEFAULT 'СПО',
  `forApplicant` int DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `direct`
--

INSERT INTO `direct` (`id`, `code`, `name`, `type`, `forApplicant`, `deleted`) VALUES
(1, '08.01.07', 'Мастер общестроительных работ', 'НПО', 0, 0),
(2, '09.02.07', 'Информационные системы и программирование', 'СПО', 0, 0),
(3, '13.01.10', 'Электромонтер по ремонту и обслуживанию электрооборудования (по отраслям)', 'НПО', 0, 0),
(4, '15.02.12', 'Монтаж, техническое обслуживание и ремонт промышленного оборудования (по отраслям)', 'СПО', 0, 0),
(5, '23.02.07', 'Техническое обслуживание и ремонт двигателей, систем и агрегатов автомобилей', 'СПО', 0, 0),
(6, '29.02.01', 'Конструирование, моделирование и технология изделий из кожи', 'СПО', 0, 0),
(7, '29.02.02', 'Технология кожи и меха', 'СПО', 0, 0),
(8, '29.02.04', 'Конструирование, моделирование, и технология швейных изделий', 'СПО', 0, 0),
(9, '38.02.01', 'Экономика и бухгалтерский учет (по отраслям)', 'СПО', 0, 0),
(10, '54.01.20', 'Графический дизайнер', 'НПО', 0, 0),
(11, '19601', 'Швея', 'НПО', 0, 0),
(12, '19727', 'Штукатур', 'НПО', 0, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `direct`
--
ALTER TABLE `direct`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `direct`
--
ALTER TABLE `direct`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
