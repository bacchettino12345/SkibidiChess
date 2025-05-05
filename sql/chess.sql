-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 29, 2025 alle 09:26
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chess`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `access_logs`
--

CREATE TABLE `access_logs` (
  `user_id` int(11) NOT NULL,
  `ip` varchar(24) NOT NULL,
  `device` varchar(32) NOT NULL,
  `os` varchar(25) NOT NULL,
  `client` varchar(32) NOT NULL,
  `country` varchar(32) NOT NULL,
  `region` varchar(32) NOT NULL,
  `city` varchar(32) NOT NULL,
  `isp` varchar(32) NOT NULL,
  `as_` varchar(32) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `access_logs`
--

INSERT INTO `access_logs` (`user_id`, `ip`, `device`, `os`, `client`, `country`, `region`, `city`, `isp`, `as_`, `time`) VALUES
(64, '127.0.0.1', 'N/A', 'Windows 10', 'Firefox 136.0', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '2025-03-30 18:58:04'),
(64, '5.90.231.176', 'N/A', 'Android 15', 'Firefox Mobile 136.0', 'Italy', 'Lazio', 'Rome', 'Vodafone', 'AS30722 Vodafone Italia S.p.A.', '2025-03-30 19:00:22'),
(64, '5.90.231.176', 'N/A', 'Android 15', 'Firefox Mobile 136.0', 'Italy', 'Lazio', 'Rome', 'Vodafone', 'AS30722 Vodafone Italia S.p.A.', '2025-03-30 19:01:27'),
(64, '5.90.231.176', 'N/A', 'Android 15', 'Firefox Mobile 136.0', 'Italy', 'Lazio', 'Rome', 'Vodafone', 'AS30722 Vodafone Italia S.p.A.', '2025-03-30 19:02:19'),
(65, '127.0.0.1', 'N/A', 'Windows 10', 'Firefox 136.0', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '2025-03-30 19:14:54'),
(70, '10.10.201.40', 'N/A', 'Android 15', 'Firefox Mobile 137.0', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '2025-04-29 09:14:48'),
(70, '127.0.0.1', 'N/A', 'Windows 10', 'Firefox 137.0', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '2025-04-29 09:16:47'),
(72, '10.10.201.79', 'N/A', 'Windows 10', 'Firefox 137.0', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '2025-04-29 09:22:36');

-- --------------------------------------------------------

--
-- Struttura della tabella `accounts`
--

CREATE TABLE `accounts` (
  `id` int(8) NOT NULL,
  `username` varchar(24) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `pts` int(15) NOT NULL DEFAULT 0,
  `pfp_path` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `firstname`, `lastname`, `email`, `password`, `active`, `admin`, `pts`, `pfp_path`) VALUES
(70, 'cinag', 'Riccardo', 'Cinaglia', 'cinagliariccardo@gmail.com', '$2y$10$uUuDqsk8G007luTkI2/MouaBb5GJUjzoaffnAgGJ6QFvnsRGEt.ly', 1, 1, 0, ''),
(72, 'sbacs', 'TOMMASO', 'LUCIANI', 'dalcaldoalfreddodigiorno@gmail.com', '$2y$10$l08a8aDGmnWqpF9c1Qil5eDRESd8FgVPYROzpUMdbc8jusXTqj6pC', 1, 1, 500, '');

-- --------------------------------------------------------

--
-- Struttura della tabella `active_games`
--

CREATE TABLE `active_games` (
  `id` int(6) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `time` int(11) NOT NULL,
  `last_move_x` int(11) NOT NULL,
  `last_move_y` int(11) NOT NULL,
  `fen` varchar(125) NOT NULL DEFAULT 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `active_games`
--

INSERT INTO `active_games` (`id`, `creator_id`, `date`, `time`, `last_move_x`, `last_move_y`, `fen`) VALUES
(762128, 6, '2025-03-26 22:02:06', 1739020081, 0, 0, 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1'),
(861917, 6, '2025-03-26 22:02:12', 1739022054, 0, 0, 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');

-- --------------------------------------------------------

--
-- Struttura della tabella `games_history`
--

CREATE TABLE `games_history` (
  `game_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `joiner_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `time_start` int(11) NOT NULL,
  `time_end` int(11) NOT NULL,
  `winner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `verification_codes`
--

CREATE TABLE `verification_codes` (
  `code` varchar(6) NOT NULL,
  `email` varchar(128) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `expires` datetime NOT NULL DEFAULT (current_timestamp() + interval 10 minute),
  `verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `active_games`
--
ALTER TABLE `active_games`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

DELIMITER $$
--
-- Eventi
--
CREATE DEFINER=`root`@`localhost` EVENT `delete_expired_verification_codes` ON SCHEDULE EVERY 1 SECOND STARTS '2025-04-27 18:36:17' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Cancella i codici di verifica scaduti' DO DELETE FROM verification_codes 
    WHERE expires < NOW()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
