-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 04, 2026 at 06:07 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chronicle`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_info`
--

DROP TABLE IF EXISTS `admin_user_info`;
CREATE TABLE IF NOT EXISTS `admin_user_info` (
  `user_id` int UNSIGNED NOT NULL,
  `info` text COLLATE utf8mb4_unicode_ci,
  `updated_by` int UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_user_info`
--

INSERT INTO `admin_user_info` (`user_id`, `info`, `updated_by`, `updated_at`) VALUES
(9, 'lufuebujb3e', 7, '2026-06-04 18:01:39');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `Id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `User_id` int UNSIGNED NOT NULL,
  `Title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('published','draft') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `User_id` (`User_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`Id`, `User_id`, `Title`, `Content`, `status`, `CreatedAt`) VALUES
(1, 8, 'first article', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s Body Type sheets. It has survived not only many decades, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these sheets and more recently with desktop publishing software including versions of Lorem Ipsum.\r\n\r\n', 'published', '2026-05-31 16:08:11'),
(2, 8, 'second article', 'ffvbejmkgfvbdnwmr4gyewhgfrvbdn', 'draft', '2026-05-31 18:22:42'),
(3, 9, 'tech', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.\r\n\r\nThe standard chunk of Lorem Ipsum used since 1966 is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 'published', '2026-06-01 15:37:14'),
(4, 9, 'dasign', 'This page shares my best articles to read on topics like health, happiness, creativity, productivity and more. The central question that drives my work is, “How can we live better?” To answer that question, I like to write about science-based ways to solve practical problems.\r\n\r\nYou’ll find interesting articles to read on topics like how to stop procrastinating as well as personal recommendations like my list of the best books to read and my minimalist travel guide. Ready to dive in? You can use the categories below to browse my best articles.', 'draft', '2026-06-01 15:38:29'),
(5, 8, 'tutorial', 'recommendations like my list of the best books to read and my minimalist travel guide. Ready to dive in? You can use the categories below to browse my best articles.', 'published', '2026-06-01 15:40:41'),
(6, 10, 'design of AI', 'I don’t particularly like ‘Artificial Intelligence’ (AI) — for all the obvious reasons. The results of AI design tools tend to be derivative, unimaginative and unethical. AI is not going to save us from global warming, AI is not going to eradicate poverty and AI is most certainly not going to make us smarter.\r\n\r\nBut generative AI is here — and I believe as an academic institution it is our obligation to experiment with new technologies and explore and evaluate its possibilities. Designers tend to be sceptical adopters. New technologies are always interesting — but the important question is how they can be applied to problem solving or to aesthetic expression.', 'published', '2026-06-04 16:49:35'),
(7, 10, 'random', 'In the summer semester 2024, I gave a class in the Interface Design programme that I aptly called ‘Design Against the Machine’. Instead of banning AI, I made it a requirement. For better or worse, my students had to use AI tools for every part of the design process: ideation, sketching, storytelling, prototyping, image creation, visual design, typography and coding. But I also told them not to take the results for granted. Instead, they should question, evaluate, modify and even destroy the AI ouput.', 'published', '2026-06-04 16:50:05');

-- --------------------------------------------------------

--
-- Table structure for table `article_categories`
--

DROP TABLE IF EXISTS `article_categories`;
CREATE TABLE IF NOT EXISTS `article_categories` (
  `article_Id` int UNSIGNED NOT NULL,
  `category_Id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`article_Id`,`category_Id`),
  KEY `CategoryId` (`category_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `article_categories`
--

INSERT INTO `article_categories` (`article_Id`, `category_Id`) VALUES
(2, 1),
(3, 1),
(6, 1),
(1, 2),
(2, 2),
(4, 2),
(6, 2),
(5, 3),
(7, 3),
(1, 4),
(3, 4),
(4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `Id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`Id`, `Name`) VALUES
(2, 'design'),
(4, 'lifestyle'),
(1, 'technology'),
(3, 'tutorial');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `Id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_Id` int UNSIGNED NOT NULL,
  `article_id` int UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `user_Id` (`user_Id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`Id`, `user_Id`, `article_id`, `content`) VALUES
(1, 9, 1, 'gfbhdmk'),
(2, 9, 1, 'vgfbchd'),
(3, 9, 1, 'grfedwsefrghngtrfe'),
(4, 8, 3, 'hello'),
(5, 9, 5, 'sxcrtvbyunmj');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `user_id` int UNSIGNED NOT NULL,
  `article_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`article_id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`user_id`, `article_id`) VALUES
(9, 1),
(8, 3),
(9, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `Id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `UserName` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UserName` (`UserName`),
  UNIQUE KEY `UserName_2` (`UserName`,`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `UserName`, `Email`, `Password`, `Role`) VALUES
(7, 'tiko', 'tiko@gmail.com', '$2y$10$wtigPmidXSYrVHxqcn87J.txWQSdRkWbkOdHzb29Ag9HII8FGM56G', 'admin'),
(8, 'mari', 'mari@gmail.com', '$2y$10$jnK.UnU60YNGo8JIAsCMrePgJnLnaVDfTSxRwvwz5s5YECvloEzQ6', 'user'),
(9, 'anano', 'anano@gmail.com', '$2y$10$qHAYmd7mVKWXoHA8XC8uXuKU7ZBwbaPWvtWnImAmxY31Sm8s8DF72', 'user'),
(10, 'nika1234', 'nika1234@gmail.com', '$2y$10$7DUlCIhVwwdDxXnjoR9K1..EYoQ2RLadrv788Ynzl0IY8AhE3nRWi', 'user');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_user_info`
--
ALTER TABLE `admin_user_info`
  ADD CONSTRAINT `admin_user_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `admin_user_info_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`Id`);

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `users` (`Id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `article_categories`
--
ALTER TABLE `article_categories`
  ADD CONSTRAINT `article_categories_ibfk_1` FOREIGN KEY (`article_Id`) REFERENCES `articles` (`Id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `article_categories_ibfk_2` FOREIGN KEY (`category_Id`) REFERENCES `categories` (`Id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_Id`) REFERENCES `users` (`Id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`Id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`Id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
