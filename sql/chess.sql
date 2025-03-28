-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2025 at 10:11 PM
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
(6, '127.0.0.1', '2025-03-28 22:09:12');

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(8) NOT NULL,
  `user` varchar(24) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `psswd` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `pts` int(15) NOT NULL DEFAULT 0,
  `pfp_path` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `user`, `firstname`, `lastname`, `mail`, `psswd`, `active`, `admin`, `pts`, `pfp_path`) VALUES
(3, 'cinaconlaccentosullaa', '', '', 'admin@skibidichess.com', '4d553f905091a759234f54c8491cb64346dbf309b6798c7e5faa094a00a5325e', 1, 1, 30, ''),
(6, 'sbacs', 'Nigger', '', 'admin@skibidichess.com', '4d553f905091a759234f54c8491cb64346dbf309b6798c7e5faa094a00a5325e', 1, 1, 250, ''),
(58, 'nigger69', 'ciao', 'nigger 69', 'nigger@nigger.nigger', '120f6e5b4ea32f65bda68452fcfaaef06b0136e1d0e4a6f60bc3771fa0936dd6', 1, 0, 0, ''),
(60, 'Faggot', 'tommaso', 'luciani', 'ciao@ciao.com', 'b133a0c0e9bee3be20163d2ad31d6248db292aa6dcb1ee087a2aa50e0fc75ae2', 1, 0, 250, ''),
(61, 'CatInABox', 'Puss', 'Cat', 'CatInABox@riccardociaglia.it', '87d042492ed5d7159ca33186108d046699656b549ebfac091dd6c9eaeacc63dd', 1, 0, 0, '');

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
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
