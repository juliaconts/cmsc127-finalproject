-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 01:31 PM
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
-- Database: `cmsc127_finalproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `academicyear`
--

CREATE TABLE `academicyear` (
  `acadYear` varchar(9) NOT NULL,
  `semester` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academicyear`
--

INSERT INTO `academicyear` (`acadYear`, `semester`) VALUES
('2023-2024', 1),
('2023-2024', 2),
('2024-2025', 1),
('2024-2025', 2);

-- --------------------------------------------------------

--
-- Table structure for table `advises`
--

CREATE TABLE `advises` (
  `advisorID` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `acadYear` varchar(9) NOT NULL,
  `semester` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advises`
--

INSERT INTO `advises` (`advisorID`, `type`, `acadYear`, `semester`) VALUES
(1, 'Co-Advisor', '2024-2025', 1),
(1, 'Advisor', '2024-2025', 2),
(2, 'Co-Advisor', '2024-2025', 2),
(3, 'Advisor', '2023-2024', 1),
(3, 'Advisor', '2023-2024', 2),
(3, 'Advisor', '2024-2025', 1);

-- --------------------------------------------------------

--
-- Table structure for table `advisor`
--

CREATE TABLE `advisor` (
  `advisorID` int(11) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `middleInitial` varchar(1) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisor`
--

INSERT INTO `advisor` (`advisorID`, `firstName`, `middleInitial`, `lastName`) VALUES
(1, 'Joseph Victor', 'S', 'Sumbong'),
(2, 'Franz Angelo', 'U', 'Apoyon'),
(3, 'Jayvee', 'B', 'Castañeda');

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `alumniID` int(11) NOT NULL,
  `studentID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni`
--

INSERT INTO `alumni` (`alumniID`, `studentID`) VALUES
(1, 201799001),
(2, 201799018);

-- --------------------------------------------------------

--
-- Table structure for table `assigned`
--

CREATE TABLE `assigned` (
  `semester` int(11) NOT NULL,
  `acadYear` varchar(9) NOT NULL,
  `roleID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `status` varchar(30) DEFAULT NULL,
  `yearLevel` int(2) DEFAULT NULL,
  `contactNo` int(11) DEFAULT NULL,
  `presentAddress` varchar(100) DEFAULT NULL,
  `form5` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned`
--

INSERT INTO `assigned` (`semester`, `acadYear`, `roleID`, `studentID`, `status`, `yearLevel`, `contactNo`, `presentAddress`, `form5`) VALUES
(1, '2023-2024', 11, 201799001,'Regular', 4, '09999999901', 'Balay Miagos, UPV', 'https://google.com'),
(1, '2023-2024', 1, 201799018,'Regular', 4, '09189999918', 'Banwa, Miagao', 'https://google.com'),
(2, '2023-2024', 11, 201799001, 'Regular', 4, '09999999901', 'Balay Miagos, UPV', 'https://google.com'),
(2, '2023-2024', 1, 201799018, 'Regular', 4, '09189999918', 'Banwa, Miagao',  'https://google.com'),

(1, '2024-2025', 11, 201799001, 'Alumni', NULL, '09999999901', 'Yokohama, Japan', 'https://google.com'),
(1, '2024-2025', 3, 201799018, 'Alumni', NULL, '09189999918', 'Yokohama, Japan', 'https://google.com'),
(2, '2024-2025', 11, 201799001, 'Alumni', NULL, '09999999901', 'Yokohama, Japan', 'https://google.com'),
(2, '2024-2025', 3, 201799018, 'Alumni', NULL, '09189999918', 'Yokohama, Japan', 'https://google.com'),

(1, '2023-2024', 17, 202101829, 'Shiftee', 1, '09999999901', 'Iloilo', NULL),
(1, '2023-2024', 17, 202300102, 'Transferee', 1, '09686474839', 'Iloilo', NULL),
(1, '2023-2024', 8, 202309989, 'Regular', 1, '09999999901', NULL, NULL),
(2, '2023-2024', 17, 202101829, 'Shiftee', 1, '09999999901', 'Iloilo', NULL),
(2, '2023-2024', 1, 202300102, 'Transferee', 1, '09686474839', 'Iloilo', NULL),
(2, '2023-2024', 8, 202309989, 'Regular', 1, '09999999901', NULL, NULL),
(2, '2023-2024', 2, 202350056, 'Irregular', 1, '09999999901', NULL, NULL),

(1, '2023-2024', 2, 202350056, 'Irregular', 1, '09999999901', NULL, NULL),
(1, '2024-2025', 17, 202101829, 'Shiftee', 2, '09123456789', 'Balay Lampirong, UPV', 'https://google.com'),
(1, '2024-2025', 4, 202300102, 'Transferee', 2, '09999999901', 'Iloilo', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing'),
(1, '2024-2025', 7, 202309989, 'Regular', 2, '09999999901', NULL, NULL),
(1, '2024-2025', 3, 202350056, 'Irregular', 2, '09999999901', NULL, NULL),
(2, '2024-2025', 17, 202101829, 'Shiftee', 2, '09999999901', 'Iloilo', NULL),
(2, '2024-2025', 4, 202300102, 'Transferee', 2, '09686474839', NULL, NULL),
(2, '2024-2025', 7, 202309989, 'Regular', 2, '09999999901', NULL, 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing'),
(2, '2024-2025', 3, 202350056, 'Irregular', 2, '09999999901', 'Iloilo', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `studentID` int(11) NOT NULL,
  `firstName` varchar(30) DEFAULT NULL,
  `middleName` varchar(30) DEFAULT NULL,
  `lastName` varchar(30) DEFAULT NULL,
  `upMail` varchar(30) DEFAULT NULL,
  `university` varchar(50) DEFAULT NULL,
  `degreeProgram` varchar(50) DEFAULT NULL,
  `homeAddress` varchar(100) DEFAULT NULL,
  `birthday` varchar(30) DEFAULT NULL,
  `signature` varchar(500) DEFAULT NULL,
  `idPicture` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`studentID`, `firstName`, `middleName`, `lastName`, `upMail`, `university`, `degreeProgram`, `homeAddress`, `birthday`, `signature`, `idPicture`) VALUES
(201799001, 'Karen', 'Koyama', 'Aijo', 'kkaijo@up.edu.ph', 'University of the Philippines - Visayas', 'BS in Computer Science', 'Yokohama, Japan', '2001-09-27', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing'),
(201799018, 'Maya', 'Tomita', 'Tendo', 'mmtendo@up.edu.ph', 'University of the Philippines - Visayas', 'BS in Computer Science', 'Yokohama, Japan', '2001-07-24', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing'),
(202101829, 'Myra', 'Sumagaysay', 'Verde', 'msverde@up.edu.ph', 'University of the Philippines - Visayas', 'BS in Computer Science', 'Lambunao, Iloilo', '2003-02-27', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing'),
(202300102, 'Hansen Maeve', 'Calago', 'Quindao', 'hcquindao@up.edu.ph', 'University of the Philippines - Visayas', 'BS in Computer Science', 'Mandurriao, Iloilo', '2004-08-09', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing'),
(202309989, 'Nina Claudia', 'Escorpiso', 'Del Rosario', 'nedelrosario@up.edu.ph', 'University of the Philippines - Visayas', 'BS in Computer Science', 'Narra, Palawan', '2005-04-29', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing'),
(202350056, 'Julia Louise', 'Miclat', 'Contreras', 'jmcontreras3@up.edu.ph', 'University of the Philippines - Visayas', 'BS in Computer Science', 'Molo, Iloilo City', '2004-08-18', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing', 'https://docs.google.com/spreadsheets/d/1IbPbL6lmFFrFSHwCf96ecVwpHt6ef_rYOPfMHzVwB7M/edit?usp=sharing');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentID` int(11) NOT NULL,
  `payment` varchar(50) DEFAULT NULL,
  `amount` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentID`, `payment`, `amount`) VALUES
(1, 'Membership Fee', 100),
(2, 'AKWE 2024', 150),
(3, 'AKWE 2023', 100);

-- --------------------------------------------------------

--
-- Table structure for table `pays`
--

CREATE TABLE `pays` (
  `studentID` int(11) NOT NULL,
  `paymentID` int(11) NOT NULL,
  `acadYear` varchar(9) NOT NULL,
  `semester` int(11) NOT NULL,
  `isPaid` tinyint(1) DEFAULT NULL,
  `modeOfPayment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pays`
--

INSERT INTO `pays` (`studentID`, `paymentID`, `acadYear`, `semester`, `isPaid`, `modeOfPayment`) VALUES
(202101829, 1, '2024-2025', 1, 0, NULL),
(202101829, 2, '2024-2025', 2, 1, 'PayMaya'),
(202300102, 1, '2024-2025', 1, 0, NULL),
(202300102, 1, '2024-2025', 2, 1, 'Cash'),
(202309989, 2, '2024-2025', 1, 1, 'Cash'),
(202350056, 1, '2024-2025', 1, 1, 'Gcash'),
(202350056, 2, '2024-2025', 2, 1, 'Gcash');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `roleID` int(11) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleID`, `role`, `description`) VALUES
(1, 'Member', 'Is a person who has been officially enrolled in at least 3.0 units worth of Computer Science (CMSC) or equivalent courses. They shall have the rights to participate in all events held by the UPV Komsai.Org.'),
(2, 'President', 'Shall lead the organization, have the power to make high-level decisions and execute all policies of the organization, and act as the official representative of the UPV Komsai.Org.'),
(3, 'Vice President for Internal Affairs', 'Shall assist the President in their administrative functions, and shall focus on overseeing the internal happenings within UPV Komsai.Org. They shall automatically become the head of the Website Committee.'),
(4, 'Vice President for External Affairs', 'Shall assist the President in their administrative functions, and shall focus on overseeing the external activities within UPV. They shall automatically become the head of the Public Relations Committee.'),
(5, 'Secretary', 'Shall keep full record of the minutes of the meetings, handle the paperwork of the organization, and record and take charge of all documentations. They shall automatically become the head of the Documentation Committee.'),
(6, 'Treasurer', 'Shall receive, record and keep the financial assets of the organization and shall collect all financial dues from members. They shall automatically become the head of the Finance Committee.'),
(7, 'Auditor', 'Shall audit all finances and assets of the organization. They shall automatically be part of the Finance Committee.'),
(8, 'Business Manager', 'Shall take charge in all fundraising activities of the organization and be responsible for all marketing plans of the organization. They shall automatically become head of the Logistics Committee.'),
(9, 'PIO', 'Shall make press releases concerning the organization as directed by the\r\nPresident and/or the Executive Council. They shall automatically be part of the Website and Publications Committee'),
(10, 'Batch Representative', 'Shall act as the representative of his/her batch regarding their concerns in the organization. They shall be part of one or multiple committees under the organization.'),
(11, 'Documentation Committee Member', 'Shall oversee the documentation of all official organizational events through photographs and video recordings, ensuring the preservation of high-quality visual records for archival and promotional use.'),
(12, 'Finance Committee Member', 'Shall receive, record, and keep the financial records of the organization and shall collect all financial dues from the members.'),
(13, 'Logistics Committee Member', 'Shall oversee the planning and coordination of all organizational events and activities and collaborate with other committees to ensure smooth logistical operations for events and projects.'),
(14, 'Publications Committee Member', 'Divided into three main sections: Creatives, Editors, and Social Media Managers. They shall help the PIOs with the creation of publication materials, editing, and handling the social media accounts of the organization.'),
(15, 'Website Committee Member', 'Shall develop and maintain a strategic plan for the website that aligns with the organization’s mission and goals.'),
(16, 'Public Relations Committee Member', 'Shall seek partnerships and collaborations with external organizations, sponsors, and other institutions.'),
(17, 'Education and Research Committee Member', 'Shall be in charge of the overall educational development of the members of the organization and the formulation of the campaigns that the organization will soldier throughout the year.');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentID`) VALUES
(202101829),
(202300102),
(202309989),
(202350056);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academicyear`
--
ALTER TABLE `academicyear`
  ADD PRIMARY KEY (`acadYear`,`semester`);

--
-- Indexes for table `advises`
--
ALTER TABLE `advises`
  ADD PRIMARY KEY (`advisorID`,`acadYear`,`semester`),
  ADD KEY `acadYear` (`acadYear`,`semester`);

--
-- Indexes for table `advisor`
--
ALTER TABLE `advisor`
  ADD PRIMARY KEY (`advisorID`);

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`alumniID`),
  ADD KEY `studentID` (`studentID`);

--
-- Indexes for table `assigned`
--
ALTER TABLE `assigned`
  ADD PRIMARY KEY (`semester`,`acadYear`,`studentID`,`roleID`),
  ADD KEY `acadYear` (`acadYear`,`semester`),
  ADD KEY `studentID` (`studentID`),
  ADD KEY `roleID` (`roleID`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentID`);

--
-- Indexes for table `pays`
--
ALTER TABLE `pays`
  ADD PRIMARY KEY (`studentID`,`paymentID`,`acadYear`,`semester`),
  ADD KEY `paymentID` (`paymentID`),
  ADD KEY `acadYear` (`acadYear`,`semester`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roleID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advises`
--
ALTER TABLE `advises`
  ADD CONSTRAINT `advises_ibfk_1` FOREIGN KEY (`advisorID`) REFERENCES `advisor` (`advisorID`),
  ADD CONSTRAINT `advises_ibfk_2` FOREIGN KEY (`acadYear`) REFERENCES `academicyear` (`acadYear`);

--
-- Constraints for table `alumni`
--
ALTER TABLE `alumni`
  ADD CONSTRAINT `alumni_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `member` (`studentID`);

--
-- Constraints for table `assigned`
--
ALTER TABLE `assigned`
  ADD CONSTRAINT `assigned_ibfk_1` FOREIGN KEY (`acadYear`) REFERENCES `academicyear` (`acadYear`),
  ADD CONSTRAINT `assigned_ibfk_2` FOREIGN KEY (`studentID`) REFERENCES `member` (`studentID`),
  ADD CONSTRAINT `assigned_ibfk_3` FOREIGN KEY (`roleID`) REFERENCES `roles` (`roleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
