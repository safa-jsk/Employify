-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2024 at 06:51 PM
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
(1, 'Machine Learning Specialist', 'james_goslin', 1, '2025-01-31', 'AI', '2024-12-23', 'Need to train AI Data Models', 80000),
(2, 'Chatbot Engineer', 'evan_you', 1, '2025-02-28', 'NLP', '2024-12-23', 'Auto generated text from minimal prompt', 65000),
(3, 'Cybersecurity Specialist Application', 'andrew_colem', 1, '2024-02-15', 'Cybersecurity', '2023-12-15', 'Application for the position of Cybersecurity Specialist, ensuring robust data protection.', 110000),
(4, 'Renewable Energy Consultant Application', 'adam_barker', 1, '2023-11-15', 'Renewable Energy', '2023-10-01', 'Application for the position of Renewable Energy Consultant, focusing on sustainable energy solutions.', 100000),
(5, 'Pharma Research Analyst Application', 'eric_ali', 1, '2024-03-01', 'Healthcare', '2023-12-20', 'Application for the position of Pharma Research Analyst, driving innovation in healthcare solutions.', 105000),
(6, 'Logistics Manager Application', 'christopher_', 1, '2024-01-15', 'Logistics', '2023-12-10', 'Application for the position of Logistics Manager, optimizing supply chain operations.', 95000),
(7, 'Financial Advisor Application', 'rachel_powel', 1, '2023-11-30', 'Finance', '2023-10-15', 'Application for the position of Financial Advisor, providing tailored financial guidance.', 115000),
(8, 'Construction Engineer Application', 'preston_merr', 1, '2024-02-10', 'Construction', '2023-12-01', 'Application for the position of Construction Engineer, delivering innovative engineering solutions.', 98000),
(9, 'Food Scientist Application', 'chase_osborn', 1, '2024-01-25', 'Food Science', '2023-12-05', 'Application for the position of Food Scientist, enhancing organic food product development.', 90000),
(10, 'IT Infrastructure Specialist Application', 'daniel_kirk', 1, '2023-12-01', 'IT', '2023-11-01', 'Application for the position of IT Infrastructure Specialist, maintaining secure IT systems.', 110000),
(11, 'Eco-Friendly Product Developer', 'brian_ramire', 1, '2024-02-20', 'Product Development', '2023-12-10', 'Application for the position of Eco-Friendly Product Developer, creating sustainable products.', 97000),
(12, 'Healthcare Consultant Application', 'shelley_john', 1, '2024-03-05', 'Healthcare', '2023-12-15', 'Application for the position of Healthcare Consultant, improving medical services.', 102000),
(13, 'E-Learning Developer Application', 'madison_rich', 1, '2024-01-30', 'Education', '2023-12-10', 'Application for the position of E-Learning Developer, creating engaging educational content.', 88000),
(14, 'Interior Designer Application', 'travis_brown', 1, '2024-01-15', 'Interior Design', '2023-12-01', 'Application for the position of Interior Designer, crafting bespoke interiors.', 93000),
(15, 'Digital Marketing Specialist Application', 'mary_braun', 1, '2023-12-20', 'Marketing', '2023-11-15', 'Application for the position of Digital Marketing Specialist, promoting creative campaigns.', 97000),
(16, 'HR Manager Application', 'shannon_cart', 1, '2024-02-01', 'Human Resources', '2023-12-05', 'Application for the position of HR Manager, managing recruitment and HR functions.', 96000),
(17, 'Corporate Lawyer Application', 'elizabeth_mc', 1, '2024-03-10', 'Law', '2023-12-20', 'Application for the position of Corporate Lawyer, specializing in IP law.', 130000),
(18, 'Real Estate Agent Application', 'gail_phillip', 1, '2024-02-25', 'Real Estate', '2023-12-15', 'Application for the position of Real Estate Agent, managing residential and commercial sales.', 87000),
(19, 'Robotics Engineer Application', 'robert_ayers', 1, '2024-01-31', 'Engineering', '2023-12-10', 'Application for the position of Robotics Engineer, innovating industrial robotics.', 120000),
(20, 'Business Consultant Application', 'kim_peterson', 1, '2024-02-20', 'Business', '2023-12-05', 'Application for the position of Business Consultant, delivering strategic insights.', 99000),
(21, 'AI Specialist Application', 'joshua_carro', 1, '2023-12-01', 'AI', '2023-11-01', 'Application for the position of AI Specialist, developing cutting-edge algorithms.', 112000),
(22, 'Media Producer Application', 'michael_sloa', 1, '2024-02-15', 'Media', '2023-12-10', 'Application for the position of Media Producer, creating engaging digital content.', 87000),
(23, 'Green Tech Developer Application', 'loretta_card', 1, '2024-01-20', 'Green Technology', '2023-12-01', 'Application for the position of Green Tech Developer, designing sustainable technologies.', 91000),
(24, 'IT Consultant Application', 'philip_colli', 1, '2024-03-01', 'IT', '2023-12-15', 'Application for the position of IT Consultant, ensuring secure systems.', 118000),
(25, 'Water Management Specialist Application', 'douglas_hill', 1, '2024-02-05', 'Environment', '2023-12-05', 'Application for the position of Water Management Specialist, innovating water solutions.', 104000),
(26, 'Civil Engineer Application', 'margaret_rey', 1, '2024-03-10', 'Construction', '2023-12-20', 'Application for the position of Civil Engineer, ensuring quality construction.', 110000),
(27, 'Startup Consultant Application', 'david_fitzge', 1, '2023-12-01', 'Consulting', '2023-11-01', 'Application for the position of Startup Consultant, helping businesses grow.', 125000),
(28, 'Supply Chain Analyst Application', 'michael_flem', 1, '2024-02-15', 'Logistics', '2023-12-10', 'Application for the position of Supply Chain Analyst, streamlining supply chains.', 95000),
(29, 'Interior Decorator Application', 'anthony_youn', 1, '2024-01-31', 'Interior Design', '2023-12-05', 'Application for the position of Interior Decorator, crafting stylish spaces.', 87000),
(30, 'Data Analyst Application', 'leah_hawkins', 1, '2024-02-20', 'Data Science', '2023-12-15', 'Application for the position of Data Analyst, analyzing big data for insights.', 101000),
(31, 'Fashion Designer Application', 'stephen_thom', 1, '2024-03-01', 'Fashion', '2023-12-20', 'Application for the position of Fashion Designer, creating innovative apparel.', 92000),
(32, 'Medical Technologist Application', 'douglas_cart', 1, '2024-02-15', 'Healthcare', '2023-12-10', 'Application for the position of Medical Technologist, innovating medical devices.', 98000),
(33, 'Airline Operations Specialist', 'kelli_johnso', 1, '2024-02-25', 'Aviation', '2023-12-01', 'Application for the position of Airline Operations Specialist, optimizing airline operations.', 90000),
(34, 'Travel Coordinator Application', 'candice_hick', 1, '2023-12-15', 'Travel', '2023-11-15', 'Application for the position of Travel Coordinator, managing travel plans.', 83000),
(35, 'Software Developer Application', 'jason_morgan', 1, '2024-02-10', 'IT', '2023-12-05', 'Application for the position of Software Developer, creating innovative software solutions.', 110000),
(36, 'Agricultural Scientist Application', 'christopher_', 1, '2024-01-20', 'Agriculture', '2023-12-01', 'Application for the position of Agricultural Scientist, enhancing sustainable farming.', 94000),
(37, 'Transport Manager Application', 'john_hodges', 1, '2024-03-05', 'Transport', '2023-12-15', 'Application for the position of Transport Manager, ensuring efficient logistics.', 91000),
(38, 'Fashion Marketing Specialist', 'brooke_gonza', 1, '2024-01-25', 'Marketing', '2023-12-10', 'Application for the position of Fashion Marketing Specialist, promoting apparel.', 89000),
(39, 'IT Support Specialist Application', 'donald_david', 1, '2024-02-15', 'IT', '2023-12-01', 'Application for the position of IT Support Specialist, maintaining IT systems.', 93000),
(40, 'Networking Specialist Application', 'philip_alvar', 1, '2024-03-01', 'Networking', '2023-12-05', 'Application for the position of Networking Specialist, ensuring connectivity.', 97000),
(41, 'Media Designer Application', 'jason_west', 1, '2024-01-30', 'Media', '2023-12-10', 'Application for the position of Media Designer, creating engaging visuals.', 89000),
(42, 'Innovation Specialist Application', 'nicholas_mil', 1, '2024-03-10', 'Technology', '2023-12-15', 'Application for the position of Innovation Specialist, leading R&D efforts.', 114000),
(43, 'Sustainable Design Engineer', 'jordan_berge', 1, '2024-02-28', 'Design', '2023-12-01', 'Application for the position of Sustainable Design Engineer, focusing on eco-friendly design.', 102000),
(44, 'Cloud Solutions Architect Application', 'wendy_sanche', 1, '2024-01-20', 'Cloud Computing', '2023-12-05', 'Application for the position of Cloud Solutions Architect, innovating cloud solutions.', 125000),
(45, 'Cybersecurity Analyst Application', 'andrew_colem', 1, '2024-02-15', 'Cybersecurity', '2023-12-15', 'Application for the position of Cybersecurity Analyst, analyzing security risks.', 116000),
(46, 'Energy Systems Consultant Application', 'adam_barker', 1, '2023-12-01', 'Energy', '2023-11-01', 'Application for the position of Energy Systems Consultant, optimizing energy use.', 108000),
(47, 'Medical Research Assistant Application', 'eric_ali', 1, '2024-03-10', 'Healthcare', '2023-12-20', 'Application for the position of Medical Research Assistant, contributing to medical research.', 100000),
(48, 'Supply Chain Manager Application', 'christopher_', 1, '2024-02-15', 'Supply Chain', '2023-12-10', 'Application for the position of Supply Chain Manager, ensuring logistics efficiency.', 104000),
(49, 'Financial Analyst Application', 'rachel_powel', 1, '2023-12-31', 'Finance', '2023-11-30', 'Application for the position of Financial Analyst, providing financial insights.', 115000),
(50, 'Construction Project Manager Application', 'preston_merr', 1, '2024-02-10', 'Construction', '2023-12-01', 'Application for the position of Construction Project Manager, overseeing construction projects.', 120000),
(51, 'Organic Product Developer Application', 'chase_osborn', 1, '2024-01-25', 'Food Science', '2023-12-05', 'Application for the position of Organic Product Developer, innovating organic products.', 92000);

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
  `CName` varchar(30) DEFAULT NULL COMMENT 'Company Name',
  `CDescription` varchar(30) DEFAULT NULL COMMENT 'Company Description',
  `Contact` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruiter`
--

INSERT INTO `recruiter` (`R_id`, `FName`, `LName`, `Gender`, `Email`, `Password`, `CName`, `CDescription`, `Contact`) VALUES
('adam_barker', 'Adam', 'Barker', 0, 'angela51@rush.info', 'J52S4oEc&2', 'Optima Energy', 'A renewable energy provider of', 1678934211),
('andrew_colem', 'Andrew', 'Coleman', 0, 'sonya17@gmail.com', 'sS4MDdEy#z', 'BluePeak Systems', 'Developers of next-gen cyberse', 1824567891),
('anthony_youn', 'Anthony', 'Young', 1, 'hilljennifer@daniels-terry.inf', '#)PW3&Yx_9', 'ModernDesign', 'Creative and innovative interi', 1783529411),
('ares713', 'Ares', 'Pendragon', 1, 'ares.pendragon713@gmail.com', '$2y$10$kbYSRpU7QrB9w3Je1x0GLu8jCzF6nO4To834FrYsmk09Z0y2ovkY2', 'Bruh', '23vdsf', 1866942069),
('brian_ramire', 'Brian', 'Ramirez', 1, 'marvinjames@gmail.com', '(07Eadmrk*', 'EcoVision', 'Designers of cutting-edge eco-', 1678923456),
('brooke_gonza', 'Brooke', 'Gonzalez', 0, 'walter56@mann.com', 'aOB4E5Xqb)', 'StyleTrend', 'Innovative trends in clothing ', 1789341256),
('candice_hick', 'Candice', 'Hicks', 1, 'eric42@gmail.com', '*r29XTnb0h', 'BlueHaven Resorts', 'Luxury accommodations and trav', 1783940561),
('chase_osborn', 'Chase', 'Osborn', 1, 'cruzwendy@gmail.com', 'E#X8IDew$O', 'Harmony Foods', 'Producers of healthy, organic ', 1789342178),
('christopher_', 'Christopher', 'Miller', 1, 'william33@hotmail.com', ')!0PM+LjG@', 'EcoBloom', 'Innovative agriculture and gre', 1678239456),
('daniel_kirk', 'Daniel', 'Kirk', 0, 'fieldskristen@yahoo.com', '!_kkC1Agvr', 'TechNova', 'Leading the way in innovative ', 1823456789),
('david_fitzge', 'David', 'Fitzgerald', 1, 'william55@hotmail.com', ')2uCKuy3Br', 'Elite Advisory', 'Business advisory services for', 1698412345),
('donald_david', 'Donald', 'Davidson', 0, 'whitakervictoria@gilmore.com', 'KGQ7NQll(%', 'SureTech', 'Comprehensive IT and software ', 1689453412),
('douglas_cart', 'Douglas', 'Carter', 0, 'hoodtanya@hotmail.com', ')!3Myj54WK', 'NeoHealth', 'Bringing innovation to medical', 1820345791),
('douglas_hill', 'Douglas', 'Hill', 1, 'michael13@stevens-russell.com', '6JePLE+6^y', 'GreenFlow Inc.', 'Eco-conscious water management', 1789315678),
('elizabeth_mc', 'Elizabeth', 'Mcdonald', 1, 'balljamie@sanchez.com', '(rA4R)DlK%', 'Trinity Legal', 'Law firm specializing in corpo', 1678329456),
('eric_ali', 'Eric', 'Ali', 1, 'ashley97@hotmail.com', 'Fw)5IZKoTb', 'Vertex Pharma', 'A pharmaceutical company commi', 1789213478),
('evan_you', 'Evan', 'You', 0, 'you@vuejs.org', '12345', 'VoidZero Inc', 'Next Gen toolchain for JS', 1812345678),
('gail_phillip', 'Gail', 'Phillips', 1, 'daltoncarey@duncan.com', 'hHW_4qDmPU', 'Prime Realty', 'Real estate company offering r', 1823940156),
('jacob_moody', 'Jacob', 'Moody', 0, 'butlersusan@yahoo.com', '0a!+8HfcPK', 'InsightData', 'Big data analytics for smarter', 1687434521),
('james_goslin', 'James', 'Gosling', 0, 'james@java.com', '12345', 'Oracle Corporation', 'Computer Software Company', 1712345678),
('jason_morgan', 'Jason', 'Morgan', 0, 'ianmccullough@nguyen-williams.', '2@Gcl6EoTQ', 'PeakTech', 'Cutting-edge technology servic', 1789213489),
('jason_west', 'Jason', 'West', 0, 'leonardlori@flores-higgins.inf', 'cE@Og4%fAX', 'DreamVision', 'Creative solutions for media a', 1678324501),
('john_hodges', 'John', 'Hodges', 1, 'vmiller@yahoo.com', 'a3G2vrK&!2', 'SmartWay', 'Efficient transport and logist', 1783910457),
('jordan_berge', 'Jordan', 'Berger', 1, 'gregory82@medina.info', '*(UfryyG6O', 'Innovatech Ltd.', 'A leading tech solutions provi', 1789234112),
('joshua_carro', 'Joshua', 'Carroll', 1, 'tmcclain@yahoo.com', '8S8rJClzc%', 'FutureWorks AI', 'Artificial intelligence soluti', 1789243567),
('kelli_johnso', 'Kelli', 'Johnson', 1, 'mstewart@allison.org', '8kHggm6(!l', 'BrightSkies Airlines', 'Affordable and reliable air tr', 1678234589),
('kim_peterson', 'Kim', 'Peterson', 0, 'richardlynch@lopez.net', '3Op^7!Kp8T', 'TrueNorth Consulting', 'Business consultants deliverin', 1687423901),
('leah_hawkins', 'Leah', 'Hawkins', 1, 'cristian16@yahoo.com', 'u^T7VfAWCs', 'HealthPrime', 'Dedicated healthcare and welln', 1782394567),
('loretta_card', 'Loretta', 'Cardenas', 1, 'ashleyhines@yahoo.com', '@@OW7ee3r0', 'EcoFriendly Co.', 'Solutions for a sustainable en', 1789324501),
('madison_rich', 'Madison', 'Rich', 1, 'justin79@hotmail.com', 'T4Y&QYCd_&', 'NextStep Edu', 'An educational platform promot', 1834789214),
('margaret_rey', 'Margaret', 'Reynolds', 1, 'hodgesconnie@yahoo.com', 'b14aVN5t(3', 'SmartBuild', 'Engineering and construction m', 1824769234),
('mary_braun', 'Mary', 'Braun', 0, 'xmoore@kirk-morgan.com', 'Y5Qq(UXJ!8', 'NeoMark Media', 'An advertising agency deliveri', 1892341245),
('michael_flem', 'Michael', 'Fleming', 0, 'davismary@hotmail.com', 'jGiq0PgV*0', 'Swift Logistics', 'End-to-end supply chain manage', 1839427561),
('michael_sloa', 'Michael', 'Sloan', 1, 'dudleysamantha@jackson-buckley', 'UjGD+OVf^3', 'Harmony Solutions', 'Innovative technology services', 1829465310),
('nicholas_mil', 'Nicholas', 'Miller', 1, 'catherine79@gmail.com', '*PDK0Ylb3&', 'NextGen Innovations', 'Leading-edge innovations for t', 1789435120),
('philip_alvar', 'Philip', 'Alvarado', 1, 'kathleen66@gmail.com', '&104OBum(@', 'EasyNet', 'Simplifying networking and con', 1839562147),
('philip_colli', 'Philip', 'Collins', 0, 'andrewsemily@gmail.com', 'xkGq3Y$O3*', 'Primeware Technologies', 'IT consulting and software dev', 1689234576),
('preston_merr', 'Preston', 'Merritt', 1, 'browncaitlin@mckay-holland.com', 'y@401Gzr!o', 'Summit Construction', 'Building landmarks with innova', 1687432567),
('rachel_powel', 'Rachel', 'Powell', 1, 'youngrachel@pierce.com', '$0NXrOOhuZ', 'Ardent Finance', 'A firm offering tailored finan', 1789456723),
('robert_ayers', 'Robert', 'Ayers', 1, 'beckchad@macias-martinez.com', '+1Q2ZcPfyY', 'Apex Robotics', 'Innovating robotics for indust', 1789213456),
('safa_jsk', 'Jabir', 'Safa', 1, 'hadrian.peverell03@gmail.com', '$2y$10$.sK6/fHXI018whfaGtIkA.La7pSn0RzExH7hVM0O./VQkg1r3rx5W', 'Bruh', '', 1707476187),
('shannon_cart', 'Shannon', 'Carter', 0, 'nguyenmark@hotmail.com', '7#7!OHbl9%', 'Bright Minds HR', 'Providing recruitment and HR m', 1789234567),
('shelley_john', 'Shelley', 'Johnson', 1, 'ritahicks@hunter.org', '*KMI3WEr_#', 'OmniCare Health', 'Providing exceptional healthca', 1789342156),
('stephen_thom', 'Stephen', 'Thompson', 0, 'matthew87@hotmail.com', 'ij0PQ6kUN%', 'TrueStyle', 'Pioneers in fashion and appare', 1789345678),
('travis_brown', 'Travis', 'Brown', 0, 'calvin35@jenkins.info', 'Vj*1&AXAQ$', 'Luxe Interiors', 'Specializing in bespoke interi', 1678912456),
('wendy_sanche', 'Wendy', 'Sanchez', 0, 'robin87@rios-williams.com', 'DCGGnK$v&3', 'GreenFields Inc.', 'An eco-friendly company focusi', 1678329145);

-- --------------------------------------------------------

--
-- Table structure for table `recruiter_shortlist`
--

CREATE TABLE `recruiter_shortlist` (
  `R_id` varchar(255) NOT NULL,
  `A_id` int(255) NOT NULL,
  `S_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `Skills` text DEFAULT NULL,
  `Contact` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seeker`
--

INSERT INTO `seeker` (`S_id`, `FName`, `LName`, `Gender`, `Email`, `Password`, `DoB`, `Experience`, `Education`, `Skills`, `Contact`) VALUES
('james', 'James', 'Rodriguez', 0, 'james.rodriguez03713@gmail.com', '$2y$10$3W1O1eWhi9NdnSp6L5fRyu.5XLaT0gA1Ah35.5QdaSqxR6lYSCoxG', '2024-12-05', 0, '', '', NULL),
('safa_jsk', 'Jabir', 'Safa Khandoker', 0, 'jabirkhandoker03@gmail.com', '$2y$10$4cBSeurLy0UdKVQ0E5KpUerQrN8/Xq07N7t.ODYgQEMOLHMXQYL62', '2024-12-16', 0, '', '', '+8801707476187');

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
('safa_jsk', 2),
('safa_jsk', 8),
('safa_jsk', 10),
('safa_jsk', 17);

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
('safa_jsk', 1, '2024-12-27', NULL),
('safa_jsk', 2, '2024-12-27', NULL),
('safa_jsk', 3, '2024-12-27', NULL),
('safa_jsk', 4, '2024-12-27', NULL),
('safa_jsk', 8, '2024-12-30', NULL),
('safa_jsk', 10, '2024-12-29', NULL),
('safa_jsk', 17, '2024-12-30', NULL),
('safa_jsk', 25, '2024-12-30', NULL);

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
