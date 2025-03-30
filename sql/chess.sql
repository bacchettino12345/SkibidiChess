-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 03:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `access_logs`
--

CREATE TABLE `access_logs` (
  `user_id` int(11) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_logs`
--

INSERT INTO `access_logs` (`user_id`, `ip`, `time`) VALUES
(6, '127.0.0.1', '2025-03-28 22:09:12'),
(6, '127.0.0.1', '2025-03-29 12:35:00'),
(3, '127.0.0.1', '2025-03-29 12:38:24'),
(63, '10.10.201.221', '2025-03-29 12:39:14'),
(62, '10.10.201.38', '2025-03-29 12:39:28'),
(64, '127.0.0.1', '2025-03-30 15:02:34'),
(64, '127.0.0.1', '2025-03-30 15:06:38'),
(64, '127.0.0.1', '2025-03-30 15:07:22'),
(64, '127.0.0.1', '2025-03-30 15:08:01'),
(64, '127.0.0.1', '2025-03-30 15:09:32'),
(64, '127.0.0.1', '2025-03-30 15:10:30'),
(64, '127.0.0.1', '2025-03-30 15:10:56'),
(64, '127.0.0.1', '2025-03-30 15:23:14'),
(64, '127.0.0.1', '2025-03-30 15:23:30'),
(64, '127.0.0.1', '2025-03-30 15:44:37'),
(64, '127.0.0.1', '2025-03-30 15:46:59'),
(64, '127.0.0.1', '2025-03-30 15:47:44'),
(64, '127.0.0.1', '2025-03-30 15:48:16'),
(64, '127.0.0.1', '2025-03-30 15:48:37'),
(64, '127.0.0.1', '2025-03-30 15:48:58'),
(64, '127.0.0.1', '2025-03-30 15:49:41'),
(64, '127.0.0.1', '2025-03-30 15:50:04');

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
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
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `firstname`, `lastname`, `email`, `password`, `active`, `admin`, `pts`, `pfp_path`) VALUES
(64, 'test', 'Account', 'TEST', 'daddyalberty@gmail.com', '$2y$10$AJeA6j96dOIHY6ZFPaqzweEf6LoWoPjDzrNZO/XMpnHgCGvc/YWn2', 1, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `active_games`
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
-- Dumping data for table `active_games`
--

INSERT INTO `active_games` (`id`, `creator_id`, `date`, `time`, `last_move_x`, `last_move_y`, `fen`) VALUES
(762128, 6, '2025-03-26 22:02:06', 1739020081, 0, 0, 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1'),
(861917, 6, '2025-03-26 22:02:12', 1739022054, 0, 0, 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');

-- --------------------------------------------------------

--
-- Table structure for table `games_history`
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `active_games`
--
ALTER TABLE `active_games`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
