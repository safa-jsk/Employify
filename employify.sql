-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2025 at 02:44 AM
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
-- Database: `employify`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `A_id` int(12) NOT NULL,
  `Name` text NOT NULL,
  `R_id` varchar(12) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Active = 1\r\nInactive = 0',
  `Deadline` date NOT NULL,
  `Field` varchar(30) NOT NULL,
  `Posted_Date` date NOT NULL DEFAULT current_timestamp(),
  `Description` text NOT NULL,
  `Salary` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`A_id`, `Name`, `R_id`, `Status`, `Deadline`, `Field`, `Posted_Date`, `Description`, `Salary`) VALUES
(1, 'Software Developer', 'safa_jsk', 1, '2025-01-31', 'Software', '2025-01-06', 'Creating innovative software solutions. Expert in Java, Kotlin, C++, HTML, CSS ', 110000),
(2, 'Project Manager', 'safa_jsk', 1, '2025-02-18', 'Software', '2025-01-06', 'Overseeing software development projects. Expert in Java, JavaScript, PHP, HTML, CSS', 120000),
(3, 'Cybersecurity Specialist', 'safa_jsk', 1, '2025-01-18', 'Cybersecurity', '2025-01-06', 'Ensuring robust data protection, Proficient in Java, C, C++, JavaScript', 110000),
(4, 'Cloud Solutions Architect', 'safa_jsk', 1, '2025-01-31', 'Cloud Computing', '2025-01-06', 'Innovating cloud solutions. Expert in Python, Java, C#, JavaScript', 125000),
(5, 'AI Specialist', 'safa_jsk', 1, '2025-02-28', 'AI', '2025-01-06', 'Developing cutting-edge algorithms. Proficient in Python, AI, ML.', 150000),
(6, 'Blockchain Developer', 'saadat46', 1, '2025-01-30', 'Blockchain', '2025-01-06', 'Design, develop, and deploy smart contracts and blockchain solutions. Proficient in Rust, Python, Go, or JavaScript.', 110000),
(7, 'Cryptocurrency Analyst', 'saadat46', 1, '2025-01-31', 'Cryptocurrency', '2025-01-06', 'Analyze trends in cryptocurrency markets and predict future movements. Expert in Python, R and Excel', 105000),
(8, 'Decentralized Application (dApp) Developer', 'saadat46', 1, '2025-02-11', 'Cryptocurrency and Software', '2025-01-06', 'Integrate front-end interfaces with blockchain networks. Proficient in JavaScript, React and Solidity', 159998),
(9, 'Cloud Software Engineer', 'saadat46', 1, '2025-01-28', 'Cloud Computing', '2025-01-06', 'Build and maintain scalable applications on cloud platforms. Expert in Python, Java, Bash, PowerShell.', 130000),
(10, 'Cloud AI/ML Engineer', 'saadat46', 1, '2025-01-30', 'Cloud Computing, AI and ML', '2025-01-06', 'Deploy AI models in cloud ecosystems. Expert in Python, R, SQL.', 180000),
(11, 'Cloud Security Engineer', 'maliha022', 1, '2025-01-25', 'Cloud Computing and Cybersecur', '2025-01-06', 'Design secure architectures for cloud systems. Expert in Python, Go, Shell scripting.', 175000),
(12, 'Quantum Algorithm Developer', 'maliha022', 1, '2025-01-01', 'Quantum Computing', '2025-01-06', 'Develop quantum algorithms for solving optimization and cryptography problems. Requires Python, Assembly, Haskell and Q#.', 160000),
(13, 'Quantum Machine Learning Engineer', 'maliha022', 1, '2025-01-25', 'Quantum Computing and ML', '2025-01-06', 'Integrate quantum computing with machine learning models. Requires Python, C++, Assembly and Tensorflow', 200000),
(14, 'Quantum Research Scientist', 'maliha022', 1, '2025-02-20', 'Quantum Computing', '2025-01-06', 'Conduct research in quantum computing frameworks and architectures. Efficient in Python, MATLAB and Fortran.', 165000),
(15, 'DevOps Engineer (Cloud Focus)', 'maliha022', 1, '2025-01-30', 'Cloud Computing and Software', '2025-01-06', 'Optimize cloud infrastructure for high availability. Requires Python, Ruby, C# and C++.', 185000);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `msg_id` int(10) NOT NULL,
  `Name` text NOT NULL,
  `Email` text NOT NULL,
  `Subject` text DEFAULT NULL,
  `Message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`msg_id`, `Name`, `Email`, `Subject`, `Message`) VALUES
(1, 'Karen Smith', 'karen@gmail.com', 'No Acceptation', 'I always get rejected for every job. I never receive any Accepted notification. This portal doesn\'t work.');

-- --------------------------------------------------------

--
-- Table structure for table `recruiter`
--

CREATE TABLE `recruiter` (
  `R_id` varchar(12) NOT NULL,
  `FName` text NOT NULL,
  `LName` text NOT NULL,
  `Gender` tinyint(1) NOT NULL COMMENT '0 = Female\r\n1 = Male',
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `DoB` date NOT NULL DEFAULT current_timestamp(),
  `CName` varchar(30) DEFAULT NULL COMMENT 'Company Name',
  `CDescription` varchar(30) DEFAULT NULL COMMENT 'Company Description',
  `Contact` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruiter`
--

INSERT INTO `recruiter` (`R_id`, `FName`, `LName`, `Gender`, `Email`, `Password`, `DoB`, `CName`, `CDescription`, `Contact`) VALUES
('maliha022', 'Maliha', 'Rahman', 0, 'rmaliha025@gmail.com', '$2y$10$ZVN1nIgwSBLw3EDf00nM0OYH96YFRvamBKpiiF6wMZXAbxlzpo.IG', '2001-04-12', 'OpenAI', 'Leading Organization of AI dev', 1712179189),
('saadat46', 'Saadat', 'Rahman', 1, 'saadatahman@gmail.com', '$2y$10$Ue6Uw3DldUkcbadT1TqNfeVT7ckPhuRnOUK/QS031knMYW9ULPXC.', '2001-01-01', 'Bitcoin', 'Pioneering organization of Cry', 1830203080),
('safa_jsk', 'Jabir Safa', 'Khandoker', 1, 'jabirkhandoker03@gmail.com', '$2y$10$vwOiumlF2SmvhJa2SvWWD.okHEVcW4Q/aWdHk/nO/ZJQTEvOHyPOm', '2003-04-04', 'JetBrains Inc', 'Developing tools for Software ', 1707476187);

-- --------------------------------------------------------

--
-- Table structure for table `recruiter_shortlist`
--

CREATE TABLE `recruiter_shortlist` (
  `R_id` varchar(255) NOT NULL,
  `A_id` int(255) NOT NULL,
  `S_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruiter_shortlist`
--

INSERT INTO `recruiter_shortlist` (`R_id`, `A_id`, `S_id`) VALUES
('safa_jsk', 1, 'ares713'),
('safa_jsk', 2, 'james03'),
('safa_jsk', 5, 'ares713');

-- --------------------------------------------------------

--
-- Table structure for table `seeker`
--

CREATE TABLE `seeker` (
  `S_id` varchar(12) NOT NULL,
  `FName` text NOT NULL,
  `LName` text NOT NULL,
  `Gender` tinyint(1) NOT NULL COMMENT '0 = Female\r\n1 = Male',
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `DoB` date NOT NULL,
  `Experience` int(2) DEFAULT NULL COMMENT 'In years',
  `Education` text DEFAULT NULL COMMENT 'Highest Education',
  `Skills` text NOT NULL DEFAULT '',
  `Contact` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seeker`
--

INSERT INTO `seeker` (`S_id`, `FName`, `LName`, `Gender`, `Email`, `Password`, `DoB`, `Experience`, `Education`, `Skills`, `Contact`) VALUES
('ares713', 'Ares', 'Pendragon', 1, 'ares.pendragon713@gmail.com', '$2y$10$AmSjlBPrwBUvXLKqY1N/z.XXdt8E3CkHtINOnqRmq48ZnRMUchFo6', '2001-03-01', 10, 'SSC, HSC, Bachelors, Masters, PhD', 'AI, ML, Python, SQL', '01866942069'),
('crystal046', 'Crystal', 'Cirilla', 0, 'crystal@gmail.com', '$2y$10$VZJjDuZtJnIyL48i7yIyxu2TOSaGCGFtWDwN9Mz4vdKmRwWtks0Bu', '2001-08-01', 8, 'SSC, HSC, Bachelors, Masters', 'Blockchain, IoT, C++, C', '01648613287'),
('gwen_st', 'Gwen', 'Stacy', 0, 'gwen.stacy@gmail.com', '$2y$10$7W4NYNhQ5hQTmX1cOslCJuRU.R9sD2rWR76dOWUplR6Vsvy/cmc4W', '2000-03-28', 3, 'SSC, HSC, Bachelors, Masters', 'Quantum Computing, Image Processing, Computer Vision', '01764268425'),
('hadrianp', 'Hadrian', 'Peverell', 1, 'hadrian.peverell03@gmail.com', '$2y$10$8hljGIE45.xARGsWHdXyruirnvmn2J3a3JDIQ7fsyDMdAGy/5eYT2', '2000-05-14', 7, 'SSC, HSC', 'Crypto, Python, AI, Photoshop, JavaScript', '01974362158'),
('james03', 'James', 'Rodriguez', 1, 'james.rodriguez03713@gmail.com', '$2y$10$c3ljvxPgUGNHWO.cgvX9/.3/cD9QRCHiDTv7LhwAnMWJdXgQTeW3q', '2000-10-29', 5, 'SSC, HSC, Bachelors', 'Java, Kotlin, HTML, CSS, Firebase', '01234567895');

-- --------------------------------------------------------

--
-- Table structure for table `seeker_bookmarks`
--

CREATE TABLE `seeker_bookmarks` (
  `S_id` varchar(12) NOT NULL,
  `A_id` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seeker_bookmarks`
--

INSERT INTO `seeker_bookmarks` (`S_id`, `A_id`) VALUES
('crystal046', 4),
('crystal046', 14),
('gwen_st', 2),
('gwen_st', 5),
('gwen_st', 14),
('james03', 1),
('james03', 2),
('james03', 4),
('james03', 5);

-- --------------------------------------------------------

--
-- Table structure for table `seeker_seeks`
--

CREATE TABLE `seeker_seeks` (
  `S_id` varchar(12) NOT NULL,
  `A_id` int(12) NOT NULL,
  `Applied_Date` date NOT NULL DEFAULT current_timestamp(),
  `Status` tinyint(1) DEFAULT NULL COMMENT 'NULL = On Hold\r\n0 = Rejected\r\n1 = Accepted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seeker_seeks`
--

INSERT INTO `seeker_seeks` (`S_id`, `A_id`, `Applied_Date`, `Status`) VALUES
('ares713', 1, '2025-01-06', NULL),
('ares713', 3, '2025-01-06', NULL),
('ares713', 5, '2025-01-06', NULL),
('crystal046', 6, '2025-01-06', NULL),
('crystal046', 10, '2025-01-06', NULL),
('gwen_st', 3, '2025-01-06', NULL),
('james03', 2, '2025-01-06', NULL),
('james03', 4, '2025-01-06', NULL),
('james03', 6, '2025-01-06', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`A_id`),
  ADD KEY `R_id` (`R_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `recruiter`
--
ALTER TABLE `recruiter`
  ADD PRIMARY KEY (`R_id`);

--
-- Indexes for table `recruiter_shortlist`
--
ALTER TABLE `recruiter_shortlist`
  ADD PRIMARY KEY (`R_id`,`A_id`,`S_id`),
  ADD KEY `FK_Aid_Shortlist` (`A_id`),
  ADD KEY `FK_Sid_Shortlist` (`S_id`),
  ADD KEY `FK_Rid_Shortlist` (`R_id`) USING BTREE;

--
-- Indexes for table `seeker`
--
ALTER TABLE `seeker`
  ADD PRIMARY KEY (`S_id`);

--
-- Indexes for table `seeker_bookmarks`
--
ALTER TABLE `seeker_bookmarks`
  ADD PRIMARY KEY (`S_id`,`A_id`) USING BTREE,
  ADD KEY `S_id` (`S_id`),
  ADD KEY `A_id` (`A_id`);

--
-- Indexes for table `seeker_seeks`
--
ALTER TABLE `seeker_seeks`
  ADD PRIMARY KEY (`S_id`,`A_id`),
  ADD KEY `S_id` (`S_id`),
  ADD KEY `A_id` (`A_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `msg_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `FK_Rid_Application` FOREIGN KEY (`R_id`) REFERENCES `recruiter` (`R_id`) ON UPDATE CASCADE;

--
-- Constraints for table `recruiter_shortlist`
--
ALTER TABLE `recruiter_shortlist`
  ADD CONSTRAINT `FK_Aid_Shortlist` FOREIGN KEY (`A_id`) REFERENCES `applications` (`A_id`),
  ADD CONSTRAINT `FK_Rid_Shortlist` FOREIGN KEY (`R_id`) REFERENCES `recruiter` (`R_id`),
  ADD CONSTRAINT `FK_Sid_Shortlist` FOREIGN KEY (`S_id`) REFERENCES `seeker` (`S_id`);

--
-- Constraints for table `seeker_bookmarks`
--
ALTER TABLE `seeker_bookmarks`
  ADD CONSTRAINT `FK_Aid_Bookmark` FOREIGN KEY (`A_id`) REFERENCES `applications` (`A_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Sid_Bookmark` FOREIGN KEY (`S_id`) REFERENCES `seeker` (`S_id`) ON UPDATE CASCADE;

--
-- Constraints for table `seeker_seeks`
--
ALTER TABLE `seeker_seeks`
  ADD CONSTRAINT `FK_Aid_Seeks` FOREIGN KEY (`A_id`) REFERENCES `applications` (`A_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Sid_Seeks` FOREIGN KEY (`S_id`) REFERENCES `seeker` (`S_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
