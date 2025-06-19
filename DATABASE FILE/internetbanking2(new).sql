-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 08:31 PM
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
-- Database: `internetbanking2`
--

-- --------------------------------------------------------

--
-- Table structure for table `ib_acc_types`
--

CREATE TABLE `ib_acc_types` (
  `acctype_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` longtext NOT NULL,
  `rate` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_acc_types`
--

INSERT INTO `ib_acc_types` (`acctype_id`, `name`, `description`, `rate`, `code`) VALUES
(1, 'Savings', '<p>Savings accounts&nbsp;are typically the first official bank account anybody opens. Children may open an account with a parent to begin a pattern of saving. Teenagers open accounts to stash cash earned&nbsp;from a first job&nbsp;or household chores.</p><p>Savings accounts are an excellent place to park&nbsp;emergency cash. Opening a savings account also marks the beginning of your relationship with a financial institution. For example, when joining a credit union, your &ldquo;share&rdquo; or savings account establishes your membership.</p>', '20', 'ACC-CAT-4EZFO'),
(2, ' Retirement', '<p>Retirement accounts&nbsp;offer&nbsp;tax advantages. In very general terms, you get to&nbsp;avoid paying income tax on interest&nbsp;you earn from a savings account or CD each year. But you may have to pay taxes on those earnings at a later date. Still, keeping your money sheltered from taxes may help you over the long term. Most banks offer IRAs (both&nbsp;Traditional IRAs&nbsp;and&nbsp;Roth IRAs), and they may also provide&nbsp;retirement accounts for small businesses</p>', '10', 'ACC-CAT-1QYDV'),
(4, 'Recurring deposit', '<p><strong>Recurring deposit account or RD account</strong> is opened by those who want to save certain amount of money regularly for a certain period of time and earn a higher interest rate.&nbsp;In RD&nbsp;account a&nbsp;fixed amount is deposited&nbsp;every month for a specified period and the total amount is repaid with interest at the end of the particular fixed period.&nbsp;</p><p>The period of deposit is minimum six months and maximum ten years.&nbsp;The interest rates vary&nbsp;for different plans based on the amount one saves and the period of time and also on banks. No withdrawals are allowed from the RD account. However, the bank may allow to close the account before the maturity period.</p><p>These accounts can be opened in single or joint names. Banks are also providing the Nomination facility to the RD account holders.&nbsp;</p>', '15', 'ACC-CAT-VBQLE'),
(5, 'Fixed Deposit Account', '<p>In <strong>Fixed Deposit Account</strong> (also known as <strong>FD Account</strong>), a particular sum of money is deposited in a bank for specific&nbsp;period of time. It&rsquo;s one time deposit and one time take away (withdraw) account.&nbsp;The money deposited in this account can not be withdrawn before the expiry of period.&nbsp;</p><p>However, in case of need,&nbsp; the depositor can ask for closing the fixed deposit prematurely by paying a penalty. The penalty amount varies with banks.</p><p>A high interest rate is paid on fixed deposits. The rate of interest paid for fixed deposit vary according to amount, period and also from bank to bank.</p>', '40', 'ACC-CAT-A86GO'),
(7, 'Current account', '<p><strong>Current account</strong> is mainly for business persons, firms, companies, public enterprises etc and are never used for the purpose of investment or savings.These deposits are the most liquid deposits and there are no limits for number of transactions or the amount of transactions in a day. While, there is no interest paid on amount held in the account, banks charges certain &nbsp;service charges, on such accounts. The current accounts do not have any fixed maturity as these are on continuous basis accounts.</p>', '20', 'ACC-CAT-4O8QW');

-- --------------------------------------------------------

--
-- Table structure for table `ib_admin`
--

CREATE TABLE `ib_admin` (
  `admin_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `number` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_admin`
--

INSERT INTO `ib_admin` (`admin_id`, `name`, `email`, `number`, `password`, `profile_pic`) VALUES
(1, 'admin', 'admin@gmail.com', '9876543210', '036d0ef7567a20b5a4ad24a354ea4a945ddab676', 'admin-icn.png');

-- --------------------------------------------------------

--
-- Table structure for table `ib_bankaccounts`
--

CREATE TABLE `ib_bankaccounts` (
  `account_id` int(20) NOT NULL,
  `acc_name` varchar(200) NOT NULL,
  `account_number` varchar(200) NOT NULL,
  `acc_type` varchar(200) NOT NULL,
  `acc_rates` varchar(200) NOT NULL,
  `acc_status` enum('Pending','Active','Rejected','Approved') NOT NULL DEFAULT 'Pending',
  `acc_amount` varchar(200) NOT NULL,
  `client_id` varchar(200) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `client_national_id` varchar(200) NOT NULL,
  `client_pan` varchar(10) NOT NULL,
  `client_aadhaar` varchar(12) NOT NULL,
  `client_phone` varchar(200) NOT NULL,
  `client_number` varchar(200) NOT NULL,
  `client_email` varchar(200) NOT NULL,
  `client_adr` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `client_father_name` varchar(100) DEFAULT NULL,
  `client_dob` date DEFAULT NULL,
  `client_gender` enum('Male','Female','Other') DEFAULT NULL,
  `client_marital_status` enum('Single','Married') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_bankaccounts`
--

INSERT INTO `ib_bankaccounts` (`account_id`, `acc_name`, `account_number`, `acc_type`, `acc_rates`, `acc_status`, `acc_amount`, `client_id`, `client_name`, `client_national_id`, `client_pan`, `client_aadhaar`, `client_phone`, `client_number`, `client_email`, `client_adr`, `created_at`, `client_father_name`, `client_dob`, `client_gender`, `client_marital_status`) VALUES
(5, 'Lily Rai', '634089175', 'Fixed Deposit Account ', '40', 'Pending', '0', '3', 'Lily Rai', '1102245862', 'CUIOP1834M', '123450021680', '1008791253', 'iBank-CLIENT-7260', 'lily@gmail.com', 'Delhi', '2025-04-21 17:50:44.400621', 'Pinal Rai', '1999-12-16', 'Female', 'Married'),
(22, 'Raj Dhar', '4031657829', 'Recurring deposit', '15', 'Approved', '0', '1', 'Raj Dhar', '45012697001', 'RUIOP1234G', '016556780078', '09362342177', 'iBank-CLIENT-2365', 'raj@gmail.com', 'Pune', '2025-04-26 08:57:29.068518', 'Ravi Dhar', '2000-02-10', 'Male', 'Single'),
(23, 'Saikat Nath', '2754890361', 'Savings', '20', 'Approved', '0', '4', 'Saikat Nath', '554785698', 'ABCDE1234F', '123456789012', '9863310997', 'iBank-CLIENT-9617', 'nathsaikat2731@gmail.com', 'Guwahati', '2025-04-26 08:49:01.140076', 'Sisir Nath', '2003-10-27', 'Male', 'Single'),
(26, 'Saikat Nath2', '786340192', 'Recurring deposit', '15', 'Approved', '0', '4', 'Saikat Nath', '554785698', 'ABCDE1234F', '123456789012', '9863310997', 'iBank-CLIENT-9617', 'nathsaikat2731@gmail.com', 'Guwahati', '2025-05-07 16:32:43.802018', 'Sisir Nath', '2000-06-02', 'Male', 'Single');

-- --------------------------------------------------------

--
-- Table structure for table `ib_clients`
--

CREATE TABLE `ib_clients` (
  `client_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `national_id` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL,
  `client_number` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_clients`
--

INSERT INTO `ib_clients` (`client_id`, `name`, `national_id`, `phone`, `address`, `email`, `password`, `profile_pic`, `client_number`) VALUES
(1, 'Raj Dhar', '45012697001', '09362342177', 'Pune', 'raj@gmail.com', '$2y$10$YEHPYpZ/jRPBVFoty5QLEel7seldlssQsDHq7GcHaJ3eziVcnHmTW', '', 'iBank-CLIENT-2365'),
(2, 'Ajay Sharma', '200852147', '09374342100', 'Guwahati', 'ajay@gmail.com', 'f5025bc17f16c6438e8c87b9274e4f91752ae1bb', '', 'iBank-CLIENT-9458'),
(3, 'Lily Rai', '1102245862', '1008791253', 'Delhi', 'lily@gmail.com', '$2y$10$ra3RbozMhjU83fbypCayC.5YNpepPF5W8BvyZuum0xo5FMPKiQ2Fa', '', 'iBank-CLIENT-7260'),
(4, 'Saikat Nath', '554785698', '9863310997', 'Guwahati', 'nathsaikat2731@gmail.com', '$2y$10$3.1mEjjBcHCRlQ5XtQ8NQeAsvqifS9q5Q0Vdt.Bp/4RcN7fF.8D4i', '1673964674925.jpg', 'iBank-CLIENT-9617'),
(6, 'Ayush Das', '8854475621', '2217854126', 'Guwahati', 'a@gmail.com', '$2y$10$eDl0AJrmkRdH1FFjWxuOK.AGrorA6YR/AfmQNLRx2JSr0/EKKW4b2', '', 'iBank-CLIENT-0953'),
(7, 'Piu Bora', '2288745693', '9862245667', 'Delhi', 'piu@gmail.com', '$2y$10$AGcmPjf75qc2RYah7.aoEuaQQt09Szuj/cuhMNbSendTYjvDYCnHS', '', 'iBank-CLIENT-3489');

-- --------------------------------------------------------

--
-- Table structure for table `ib_notifications`
--

CREATE TABLE `ib_notifications` (
  `notification_id` int(20) NOT NULL,
  `notification_for` varchar(20) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `notification_details` text NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_notifications`
--

INSERT INTO `ib_notifications` (`notification_id`, `notification_for`, `client_id`, `notification_details`, `created_at`) VALUES
(1, 'admin', 4, 'Saikat Nath deposited Rs. 1000 to Account 2754890361 (Handled by Manoj Sankar)', '2025-05-06 16:57:12.000000'),
(2, 'staff', 4, 'Saikat Nath deposited Rs. 1000 to Account 2754890361 (Handled by Manoj Sankar)', '2025-05-06 16:57:12.000000'),
(3, 'client', 4, 'The amount Rs. 1000 has been credited to your iBank Account 2754890361. Thank you for banking with us!', '2025-05-06 16:57:12.000000'),
(4, 'admin', 4, 'Saikat Nath Has Deposited Rs. 500 To Bank Account 2754890361 Handled by Admin.', '2025-05-06 20:28:06.579521'),
(5, 'staff', 4, 'Saikat Nath Has Deposited Rs. 500 To Bank Account 2754890361 Handled by Admin.', '2025-05-06 20:28:06.581000'),
(6, 'client', 4, 'The amount Rs. 500 has been credited to your iBank Account 2754890361. Thank you for banking with us!', '2025-05-06 20:28:06.583325'),
(7, 'admin', 4, 'Saikat Nath has withdrawn Rs. 300 from Bank Account 2754890361 (Handled By: Manoj Sankar)', '2025-05-06 20:29:12.332144'),
(8, 'staff', 4, 'Saikat Nath has withdrawn Rs. 300 from Bank Account 2754890361 (Handled By: Manoj Sankar)', '2025-05-06 20:29:12.334678'),
(9, 'client', 4, 'Rs. 300 has been debited from your iBank Account 2754890361.', '2025-05-06 20:29:12.335813'),
(10, 'admin', 4, 'Saikat Nath has withdrawn Rs. 100 from Bank Account 2754890361 Handled by Admin.', '2025-05-06 20:30:04.895560'),
(11, 'staff', 4, 'Saikat Nath has withdrawn Rs. 100 from Bank Account 2754890361 Handled by Admin.', '2025-05-06 20:30:04.898681'),
(12, 'client', 4, 'Rs. 100 has been debited from your iBank Account 2754890361.', '2025-05-06 20:30:04.900134'),
(13, 'client', 1, 'An amount of Rs. 150 has just been credited to your iBank account by Saikat Nath, A/C: 2754890361 Handled by Admin.', '2025-05-06 20:35:05.559276'),
(14, 'admin', 4, 'Saikat Nath has transferred Rs. 150 from Account No. 2754890361 to Account No. 4031657829 ( Raj Dhar ) Handled by Admin.', '2025-05-06 20:35:05.561611'),
(15, 'staff', 4, 'Saikat Nath has transferred Rs. 150 from Account No. 2754890361 to Account No. 4031657829 ( Raj Dhar ) Handled by Admin.', '2025-05-06 20:35:05.562811'),
(16, 'client', 4, 'Rs. 150 has been transferred from your iBank Account 2754890361 to Account 4031657829 - Raj Dhar.', '2025-05-06 20:35:05.563983'),
(17, 'admin', 4, 'Saikat Nath has transferred Rs. 100 from Account No. 2754890361 to Account No. 4031657829 ( Raj Dhar )', '2025-05-06 20:39:08.318812'),
(18, 'staff', 4, 'Saikat Nath has transferred Rs. 100 from Account No. 2754890361 to Account No. 4031657829 ( Raj Dhar )', '2025-05-06 20:39:08.320102'),
(19, 'Client', 4, 'Rs. 100 has been successfully transferred from your iBank Account to account no. 4031657829 ( Raj Dhar ).', '2025-05-06 20:39:08.322446'),
(20, 'Client', 1, 'An amount of Rs. 100 has just been credited to your iBank account by Saikat Nath, A/C: 2754890361.', '2025-05-06 20:39:08.323965'),
(21, 'client', 1, 'An amount of Rs. 10 has just been credited to your iBank account by Saikat Nath, A/C: 2754890361.', '2025-05-06 20:39:55.035901'),
(22, 'admin', 4, 'Saikat Nath has transferred Rs. 10 from Account No. 2754890361 to Account No. 4031657829 ( Raj Dhar ), handled by - Manoj Sankar.', '2025-05-06 20:39:55.038298'),
(23, 'staff', 4, 'Saikat Nath has transferred Rs. 10 from Account No. 2754890361 to Account No. 4031657829 ( Raj Dhar ), handled by - Manoj Sankar.', '2025-05-06 20:39:55.039648'),
(24, 'client', 4, 'Rs. 10 has been transferred from your iBank Account 2754890361 to Account 4031657829 - Raj Dhar.', '2025-05-06 20:39:55.040645'),
(25, 'client', 1, 'An amount of Rs. 40 has just been credited to your iBank account by Saikat Nath, A/C: 2754890361 Handled by Admin.', '2025-05-07 15:34:18.464600'),
(26, 'admin', 4, 'Saikat Nath has transferred Rs. 40 from Account No. 2754890361 to Account No. 4031657829 ( Raj Dhar ) Handled by Admin.', '2025-05-07 15:34:18.466883'),
(27, 'staff', 4, 'Saikat Nath has transferred Rs. 40 from Account No. 2754890361 to Account No. 4031657829 ( Raj Dhar ) Handled by Admin.', '2025-05-07 15:34:18.467964'),
(28, 'client', 4, 'Rs. 40 has been transferred from your iBank Account 2754890361 to Account 4031657829 - Raj Dhar.', '2025-05-07 15:34:18.469167'),
(29, 'client', 4, 'Your iBank account request has been <strong>Approved</strong>.', '2025-05-07 16:32:43.804980'),
(30, 'staff', 4, 'Saikat Nath2 iBank account request has been Approved.', '2025-05-07 16:32:43.806673'),
(34, 'Client', NULL, 'An amount of Rs. 1 has just been credited to your iBank account by Saikat Nath, A/C: 2754890361.', '2025-05-07 17:34:10.650266'),
(35, 'admin', 4, 'Saikat Nath has transferred Rs. 100 from Account No. 2754890361 to Account No. 4031657829 ( Raj Dhar )', '2025-05-07 18:30:49.526343'),
(36, 'staff', 4, 'Saikat Nath has transferred Rs. 100 from Account No. 2754890361 to Account No. 4031657829 ( Raj Dhar )', '2025-05-07 18:30:49.527543'),
(37, 'Client', 4, 'Rs. 100 has been successfully transferred from your iBank Account to account no. 4031657829 ( Raj Dhar ).', '2025-05-07 18:30:49.528856'),
(38, 'Client', 1, 'An amount of Rs. 100 has just been credited to your iBank account by Saikat Nath, A/C: 2754890361.', '2025-05-07 18:30:49.530814');

-- --------------------------------------------------------

--
-- Table structure for table `ib_staff`
--

CREATE TABLE `ib_staff` (
  `staff_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `staff_number` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `sex` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_staff`
--

INSERT INTO `ib_staff` (`staff_id`, `name`, `staff_number`, `phone`, `email`, `password`, `sex`, `profile_pic`) VALUES
(2, 'Manoj Sankar', 'iBank-STAFF-1543', '9860010997', 'manoj@gmail.com', '9211f4f9eada4e4c6661f3ae0acc8bb829596610', 'Male', 'M-logo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ib_systemsettings`
--

CREATE TABLE `ib_systemsettings` (
  `id` int(20) NOT NULL,
  `sys_name` longtext NOT NULL,
  `sys_tagline` longtext NOT NULL,
  `sys_logo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_systemsettings`
--

INSERT INTO `ib_systemsettings` (`id`, `sys_name`, `sys_tagline`, `sys_logo`) VALUES
(1, 'Internet Banking', 'Financial success at every service we offer.', 'ibankinglg.png');

-- --------------------------------------------------------

--
-- Table structure for table `ib_transactions`
--

CREATE TABLE `ib_transactions` (
  `tr_id` int(20) NOT NULL,
  `tr_code` varchar(200) NOT NULL,
  `account_id` varchar(200) NOT NULL,
  `acc_name` varchar(200) NOT NULL,
  `account_number` varchar(200) NOT NULL,
  `acc_type` varchar(200) NOT NULL,
  `acc_amount` varchar(200) NOT NULL,
  `tr_type` varchar(200) NOT NULL,
  `tr_status` varchar(200) NOT NULL,
  `client_id` varchar(200) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `client_national_id` varchar(200) NOT NULL,
  `transaction_amt` varchar(200) NOT NULL,
  `client_phone` varchar(200) NOT NULL,
  `receiving_acc_no` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `receiving_acc_name` varchar(200) NOT NULL,
  `receiving_acc_holder` varchar(200) NOT NULL,
  `staff_id` varchar(100) DEFAULT NULL,
  `staff_name` varchar(200) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_transactions`
--

INSERT INTO `ib_transactions` (`tr_id`, `tr_code`, `account_id`, `acc_name`, `account_number`, `acc_type`, `acc_amount`, `tr_type`, `tr_status`, `client_id`, `client_name`, `client_national_id`, `transaction_amt`, `client_phone`, `receiving_acc_no`, `created_at`, `receiving_acc_name`, `receiving_acc_holder`, `staff_id`, `staff_name`, `admin_id`) VALUES
(1, 'rkdt2CmDPfJHUeGAQlaw', '23', 'Saikat Nath', '2754890361', 'Savings', '', 'Deposit', 'Success ', '4', 'Saikat Nath', '554785698', '1000', '9863310997', '', '2025-05-06 20:27:12.812522', '', '', '2', 'Manoj Sankar', NULL),
(2, 'xE7e6tzaXmYKZ8dwc1kQ', '23', 'Saikat Nath', '2754890361', 'Savings', '', 'Deposit', 'Success ', '4', 'Saikat Nath', '554785698', '500', '9863310997', '', '2025-05-06 20:28:06.577274', '', '', NULL, NULL, 1),
(3, 'wos2Xx9ZF7tmQhVq5MJG', '23', 'Saikat Nath', '2754890361', 'Savings', '', 'Withdrawal', 'Success ', '4', 'Saikat Nath', '554785698', '300', '9863310997', '', '2025-05-06 20:29:12.329225', '', '', '2', 'Manoj Sankar', NULL),
(4, 'qLIRdngaNb4V1XUHZoQT', '23', 'Saikat Nath', '2754890361', 'Savings', '', 'Withdrawal', 'Success ', '4', 'Saikat Nath', '554785698', '100', '9863310997', '', '2025-05-06 20:30:04.894132', '', '', NULL, NULL, 1),
(5, 'NZFX7SG5R60L', '23', 'Saikat Nath', '2754890361', 'Savings', '', 'Transfer', 'Success', '4', 'Saikat Nath', '554785698', '150', '9863310997', '4031657829', '2025-05-06 20:35:05.552985', 'Raj Dhar', 'Raj Dhar', NULL, NULL, 1),
(6, 'NZFX7SG5R60L', '22', 'Raj Dhar', '4031657829', 'Recurring deposit', '', 'Deposit', 'Success', '1', 'Raj Dhar', '45012697001', '150', '09362342177', '', '2025-05-06 20:35:05.557113', '', '', NULL, NULL, 1),
(7, 'wfY501J9onibKNH62CsG', '23', 'Saikat Nath', '2754890361', 'Savings', '', 'Transfer', 'Success ', '4', 'Saikat Nath', '554785698', '100', '9863310997', '4031657829', '2025-05-06 20:39:08.315908', 'Raj Dhar', 'Raj Dhar', NULL, NULL, NULL),
(8, 'wfY501J9onibKNH62CsG', '22', 'Raj Dhar', '4031657829', 'Recurring deposit', '', 'Deposit', 'Success ', '1', 'Raj Dhar', '45012697001', '100', '09362342177', '2754890361', '2025-05-06 20:39:08.317627', 'Saikat Nath', 'Saikat Nath', NULL, NULL, NULL),
(9, 'CZW0EH8XUS25', '23', 'Saikat Nath', '2754890361', 'Savings', '', 'Transfer', 'Success', '4', 'Saikat Nath', '554785698', '10', '9863310997', '4031657829', '2025-05-06 20:39:55.031824', 'Raj Dhar', 'Raj Dhar', '2', 'Manoj Sankar', NULL),
(10, 'CZW0EH8XUS25', '22', 'Raj Dhar', '4031657829', 'Recurring deposit', '', 'Deposit', 'Success', '1', 'Raj Dhar', '45012697001', '10', '09362342177', '', '2025-05-06 20:39:55.034578', '', '', '2', 'Manoj Sankar', NULL),
(11, 'VE6Y5793WOBU', '23', 'Saikat Nath', '2754890361', 'Savings', '', 'Transfer', 'Success', '4', 'Saikat Nath', '554785698', '40', '9863310997', '4031657829', '2025-05-07 15:34:18.452379', 'Raj Dhar', 'Raj Dhar', NULL, NULL, 1),
(12, 'VE6Y5793WOBU', '22', 'Raj Dhar', '4031657829', 'Recurring deposit', '', 'Deposit', 'Success', '1', 'Raj Dhar', '45012697001', '40', '09362342177', '', '2025-05-07 15:34:18.462736', '', '', NULL, NULL, 1),
(14, 'V2P0cBwNWaDzQtfrgYke', '23', 'Saikat Nath', '2754890361', 'Savings', '', 'Transfer', 'Success ', '4', 'Saikat Nath', '554785698', '100', '9863310997', '4031657829', '2025-05-07 18:30:49.520687', 'Raj Dhar', 'Raj Dhar', NULL, NULL, NULL),
(15, 'V2P0cBwNWaDzQtfrgYke', '22', 'Raj Dhar', '4031657829', 'Recurring deposit', '', 'Deposit', 'Success ', '1', 'Raj Dhar', '45012697001', '100', '09362342177', '2754890361', '2025-05-07 18:30:49.524920', 'Saikat Nath', 'Saikat Nath', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ib_acc_types`
--
ALTER TABLE `ib_acc_types`
  ADD PRIMARY KEY (`acctype_id`);

--
-- Indexes for table `ib_admin`
--
ALTER TABLE `ib_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `ib_bankaccounts`
--
ALTER TABLE `ib_bankaccounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `ib_clients`
--
ALTER TABLE `ib_clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `ib_notifications`
--
ALTER TABLE `ib_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `ib_staff`
--
ALTER TABLE `ib_staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `ib_systemsettings`
--
ALTER TABLE `ib_systemsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ib_transactions`
--
ALTER TABLE `ib_transactions`
  ADD PRIMARY KEY (`tr_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ib_acc_types`
--
ALTER TABLE `ib_acc_types`
  MODIFY `acctype_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ib_admin`
--
ALTER TABLE `ib_admin`
  MODIFY `admin_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ib_bankaccounts`
--
ALTER TABLE `ib_bankaccounts`
  MODIFY `account_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `ib_clients`
--
ALTER TABLE `ib_clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ib_notifications`
--
ALTER TABLE `ib_notifications`
  MODIFY `notification_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `ib_staff`
--
ALTER TABLE `ib_staff`
  MODIFY `staff_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ib_systemsettings`
--
ALTER TABLE `ib_systemsettings`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ib_transactions`
--
ALTER TABLE `ib_transactions`
  MODIFY `tr_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
