-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Feb 2021 um 18:11
-- Server-Version: 10.4.13-MariaDB
-- PHP-Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `durak_app`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `loser` int(11) NOT NULL,
  `loser_2` int(11) NOT NULL,
  `players` varchar(255) NOT NULL,
  `session_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `games`
--

INSERT INTO `games` (`id`, `loser`, `loser_2`, `players`, `session_id`, `created`, `modified`) VALUES
(65, 2, 0, '1,2,3,4', 1, '2021-02-28 12:04:55', '2021-02-28 11:04:16'),
(66, 3, 0, '1,2,3,4', 1, '2021-02-28 12:05:03', '2021-02-28 11:04:16'),
(67, 1, 0, '1,2,3,4', 1, '2021-02-28 12:05:06', '2021-02-28 11:04:16'),
(68, 1, 0, '1,2,3,4', 1, '2021-02-28 12:05:08', '2021-02-28 11:04:16'),
(69, 1, 2, '1,2,3,4', 1, '2021-02-28 12:05:10', '2021-02-28 11:04:16'),
(70, 4, 0, '1,2,3,4', 1, '2021-02-28 12:05:15', '2021-02-28 11:04:16'),
(71, 1, 0, '1,2,3,4', 1, '2021-02-28 12:05:17', '2021-02-28 11:04:16');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `losses` int(11) NOT NULL,
  `draws` int(11) NOT NULL,
  `color` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `players`
--

INSERT INTO `players` (`id`, `name`, `losses`, `draws`, `color`, `created`, `modified`) VALUES
(19, 'Toni', 0, 0, '', '2021-02-28 12:05:23', '2021-02-28 11:02:27'),
(20, 'Gero', 0, 0, '', '2021-02-28 12:05:25', '2021-02-28 11:02:27'),
(21, 'Marco', 0, 0, '', '2021-02-28 12:05:27', '2021-02-28 11:02:27'),
(22, 'Flo', 0, 0, '', '2021-02-28 12:05:30', '2021-02-28 11:02:27'),
(23, 'Don', 0, 0, '', '2021-02-28 12:05:31', '2021-02-28 11:02:27');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT für Tabelle `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
