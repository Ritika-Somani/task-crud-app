-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2025 at 08:28 AM
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
-- Database: `task_crud_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_task`
--

CREATE TABLE `tbl_task` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_task`
--

INSERT INTO `tbl_task` (`task_id`, `user_id`, `title`, `description`, `status`, `created_at`) VALUES
(1, 1, 'Buy groceries', 'Purchase vegetables, key holder and bread from the supermarket', 'Pending', '2025-07-13 13:27:30'),
(2, 1, 'Call electrician', 'Fix the kitchen light and bathroom geyser', 'Completed', '2025-07-13 13:27:30'),
(3, 2, 'Morning workout', '30-minute cardio and stretching exercises', 'Completed', '2025-07-13 13:27:30'),
(4, 2, 'Pay utility bills', 'Electricity and water bill payment via online banking', 'Pending', '2025-07-13 13:27:30'),
(5, 1, 'Books Purchase ', 'Purchase newly released novel', 'Pending', '2025-07-13 16:29:20'),
(6, 1, 'Tutorials', 'Complete Basic Concepts Tutorials', 'Pending', '2025-07-13 18:29:34'),
(8, 5, 'Submission', 'Theory assignment submission to mayur sir--A common form of Lorem ipsum reads:', 'Pending', '2025-07-13 20:34:03'),
(9, 5, 'lorem', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Pending', '2025-07-14 10:36:22'),
(10, 5, 'temp', 'temp', 'Completed', '2025-07-14 10:48:33'),
(12, 5, 'lorem 1', 'lorem', 'Pending', '2025-07-14 10:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `email`, `password`, `reset_token`, `token_expiry`) VALUES
(1, 'admin@example.com', '$2y$10$iq4.kljaoIAz/x9aP7QXv.uNTUYK.aWDV8q/4Sh/7Akjgz3kRkpbe', NULL, NULL),
(2, 'user@example.com', '$2y$10$uOw8ijOAC1uvRW2JlyUeKOFe3oTmc5nBSni1HxKxo47xGm4ypYy/y', NULL, NULL),
(3, 'ritika@gmail.com', '$2y$10$7PlhQ/IiFyfR/AkICJwqdO4F0NmcAo63WaX4CWCDqxVN8.ySU1lDe', NULL, NULL),
(5, 'nishtha@gmail.com', '$2y$10$b/zH8p7HMbiQSy0Bcpskv.drK3VYDi8LWAev4rJjncq9c5jMkLtSi', NULL, NULL),
(6, 'email@email.com', '$2y$10$HVi6JVcibek4tbG0x2nKc.f9gtXczPuvJb.gutwsHttQpzuj8VpjW', NULL, NULL),
(7, 'tempuser@example.com', '$2y$10$LTKDddm1tv3nqjplABQM5ee6y4S3w3C0d7Qvsr8UwsJVRjwAgXo6K', NULL, NULL),
(8, 'zimit@gmail.com', '$2y$10$AMa9xAZHdsCZUg3SEY7BKugpNeSgYveyidq0lF1KJT8mmTdBnDm0S', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_task`
--
ALTER TABLE `tbl_task`
  ADD PRIMARY KEY (`task_id`),
  ADD UNIQUE KEY `unique_user_task_title` (`user_id`,`title`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_task`
--
ALTER TABLE `tbl_task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_task`
--
ALTER TABLE `tbl_task`
  ADD CONSTRAINT `tbl_task_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
