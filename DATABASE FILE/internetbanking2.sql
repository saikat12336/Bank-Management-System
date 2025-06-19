-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 03:25 PM
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
  `acc_status` varchar(200) NOT NULL,
  `acc_amount` varchar(200) NOT NULL,
  `client_id` varchar(200) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `client_national_id` varchar(200) NOT NULL,
  `client_phone` varchar(200) NOT NULL,
  `client_number` varchar(200) NOT NULL,
  `client_email` varchar(200) NOT NULL,
  `client_adr` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_bankaccounts`
--

INSERT INTO `ib_bankaccounts` (`account_id`, `acc_name`, `account_number`, `acc_type`, `acc_rates`, `acc_status`, `acc_amount`, `client_id`, `client_name`, `client_national_id`, `client_phone`, `client_number`, `client_email`, `client_adr`, `created_at`) VALUES
(1, 'Rajdeep Dhar', '185249637', 'Savings ', '20', 'Active', '0', '1', 'Raj Dhar', '45012697001', '09362342177', 'iBank-CLIENT-2365', 'raj@gmail.com', 'Pune', '2025-03-28 07:03:03.188427'),
(2, 'Ajay Sharma', '742893065', 'Current account ', '20', 'Active', '0', '2', 'Ajay Sharma', '200852147', '09374342100', 'iBank-CLIENT-9458', 'ajay@gmail.com', 'Guwahati', '2025-03-28 07:04:55.955100'),
(3, 'Lily M. Rai', '872564319', 'Fixed Deposit Account ', '40', 'Active', '0', '3', 'Lily Rai', '1102245862', '1008791253', 'iBank-CLIENT-7260', 'lily@gmail.com', 'Delhi', '2025-03-28 07:10:51.549789'),
(4, 'Ayush Das', '198035264', 'Fixed Deposit Account ', '40', 'Active', '0', '6', 'Ayush Das', '8854475621', '2217854126', 'iBank-CLIENT-0953', 'a@gmail.com', 'Guwahati', '2025-03-28 09:47:44.587846');

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
(1, 'Raj Dhar', '45012697001', '09362342177', 'Pune', 'raj@gmail.com', '9fbad39fd8d6671428b005fc3922d66a2429e183', '', 'iBank-CLIENT-2365'),
(2, 'Ajay Sharma', '200852147', '09374342100', 'Guwahati', 'ajay@gmail.com', 'f5025bc17f16c6438e8c87b9274e4f91752ae1bb', '133869625160315660.jpg', 'iBank-CLIENT-9458'),
(3, 'Lily Rai', '1102245862', '1008791253', 'Delhi', 'lily@gmail.com', '$2y$10$ra3RbozMhjU83fbypCayC.5YNpepPF5W8BvyZuum0xo5FMPKiQ2Fa', '', 'iBank-CLIENT-7260'),
(4, 'Saikat Nath', '554785698', '09362342177', 'Guwahati', 's@gmail.com', '$2y$10$3.1mEjjBcHCRlQ5XtQ8NQeAsvqifS9q5Q0Vdt.Bp/4RcN7fF.8D4i', '', 'iBank-CLIENT-9617'),
(6, 'Ayush Das', '8854475621', '2217854126', 'Guwahati', 'a@gmail.com', '$2y$10$eDl0AJrmkRdH1FFjWxuOK.AGrorA6YR/AfmQNLRx2JSr0/EKKW4b2', '', 'iBank-CLIENT-0953');

-- --------------------------------------------------------

--
-- Table structure for table `ib_notifications`
--

CREATE TABLE `ib_notifications` (
  `notification_id` int(20) NOT NULL,
  `notification_details` text NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_notifications`
--

INSERT INTO `ib_notifications` (`notification_id`, `notification_details`, `created_at`) VALUES
(1, 'Raj Dhar Has Deposited Rs. 30000 To Bank Account 508643712', '2025-03-28 07:41:35.885536'),
(2, 'Raj Dhar Has Withdrawn Rs. 1000 From Bank Account 508643712', '2025-03-28 07:41:50.387963'),
(3, 'Ajay Sharma Has Deposited Rs. 1000 To Bank Account 742893065', '2025-03-28 07:42:01.241587'),
(4, 'Ajay Sharma Has Withdrawn Rs. 600 From Bank Account 742893065', '2025-03-28 07:42:13.515008'),
(5, 'Ajay Sharma Has Transfered Rs. 200 From Bank Account 742893065 To Bank Account 185249637', '2025-03-28 07:42:26.064986'),
(6, 'Lily Rai Has Deposited Rs. 2000 To Bank Account 872564319', '2025-03-28 07:42:38.596525'),
(7, 'Lily Rai Has Withdrawn Rs. 300 From Bank Account 872564319', '2025-03-28 07:42:50.932318'),
(8, 'Lily Rai Has Transfered Rs. 900 From Bank Account 872564319 To Bank Account 742893065', '2025-03-28 07:43:02.550451'),
(9, 'Ajay Sharma Has Withdrawn Rs. 900 From Bank Account 742893065', '2025-03-28 07:43:16.375143'),
(10, 'Ajay Sharma Has Deposited Rs. 900 To Bank Account 742893065', '2025-03-28 07:31:17.257132'),
(11, 'Ayush Das Has Deposited Rs. 300000 To Bank Account 198035264', '2025-03-28 09:48:04.341148'),
(12, 'Ayush Das Has Withdrawn $ 5000 From Bank Account 198035264', '2025-03-28 09:49:21.567607'),
(13, 'Ayush Das Has Transfered Rs. 1200 From Bank Account 198035264 To Bank Account 742893065', '2025-03-28 10:01:05.215210'),
(14, 'Lily Rai Has Withdrawn $ 500 From Bank Account 872564319', '2025-03-28 10:48:11.426736'),
(15, 'Lily Rai Has Withdrawn $ 400 From Bank Account 872564319', '2025-03-28 10:48:38.745831'),
(16, 'Lily Rai Has Deposited Rs. 200 To Bank Account 872564319', '2025-03-28 10:54:46.370781'),
(17, 'Lily Rai Has Withdrawn $ 100 From Bank Account 872564319', '2025-03-28 11:53:15.324351'),
(18, 'Lily Rai Has Deposited Rs. 200 To Bank Account 872564319', '2025-03-28 11:53:44.439940'),
(19, 'Lily Rai Has Deposited Rs. 500 To Bank Account 872564319', '2025-03-29 05:41:54.540244');

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
  `receiving_acc_holder` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_transactions`
--

INSERT INTO `ib_transactions` (`tr_id`, `tr_code`, `account_id`, `acc_name`, `account_number`, `acc_type`, `acc_amount`, `tr_type`, `tr_status`, `client_id`, `client_name`, `client_national_id`, `transaction_amt`, `client_phone`, `receiving_acc_no`, `created_at`, `receiving_acc_name`, `receiving_acc_holder`) VALUES
(1, 'cVOuH9l4gPYW1XiKp53D', '1', 'Rajdeep Dhar', '508643712', 'Select Any iBank Account types', '', 'Deposit', 'Success ', '1', 'Raj Dhar', '45012697001', '30000', '09362342177', '', '2025-03-28 06:57:43.764857', '', ''),
(2, 'i9fnxzkSD0acVIlXqUCv', '1', 'Rajdeep Dhar', '508643712', 'Select Any iBank Account types', '', 'Withdrawal', 'Success ', '1', 'Raj Dhar', '45012697001', '1000', '09362342177', '', '2025-03-28 06:57:58.223428', '', ''),
(3, 'tLlO6TVcNkISMguBexJE', '2', 'Ajay Sharma', '742893065', 'Current account ', '', 'Deposit', 'Success ', '2', 'Ajay Sharma', '200852147', '1000', '09374342100', '', '2025-03-28 07:05:16.419138', '', ''),
(4, 'TbVJryNo5UxWvMainFlD', '2', 'Ajay Sharma', '742893065', 'Current account ', '', 'Withdrawal', 'Success ', '2', 'Ajay Sharma', '200852147', '600', '09374342100', '', '2025-03-28 07:05:29.924188', '', ''),
(5, 'VChRr1AXplE9yemJaL0q', '2', 'Ajay Sharma', '742893065', 'Current account ', '', 'Transfer', 'Success ', '2', 'Ajay Sharma', '200852147', '200', '09374342100', '185249637', '2025-03-28 07:07:02.731307', 'Rajdeep Dhar', 'Rajdeep Dhar'),
(6, 'zpxU6bq7sAoMYmZ8PKcW', '3', 'Lily M. Rai', '872564319', 'Fixed Deposit Account ', '', 'Deposit', 'Success ', '3', 'Lily Rai', '1102245862', '2000', '1008791253', '', '2025-03-28 07:11:12.686554', '', ''),
(7, 'prKWRyTGNZeAL76HJvsi', '3', 'Lily M. Rai', '872564319', 'Fixed Deposit Account ', '', 'Withdrawal', 'Success ', '3', 'Lily Rai', '1102245862', '300', '1008791253', '', '2025-03-28 07:11:29.856564', '', ''),
(8, 'ZNdwgE26sTt5F7p1U4zi', '3', 'Lily M. Rai', '872564319', 'Fixed Deposit Account ', '', 'Transfer', 'Success ', '3', 'Lily Rai', '1102245862', '900', '1008791253', '742893065', '2025-03-28 07:13:30.071478', 'Ajay Sharma', 'Ajay Sharma'),
(9, 'mYJj7z9yxo2AMqsDB538', '2', 'Ajay Sharma', '742893065', 'Current account ', '', 'Withdrawal', 'Success ', '2', 'Ajay Sharma', '200852147', '900', '09374342100', '', '2025-03-28 07:30:44.617618', '', ''),
(10, 'X8y9tngCVNKlsMDfic37', '2', 'Ajay Sharma', '742893065', 'Current account ', '', 'Deposit', 'Success ', '2', 'Ajay Sharma', '200852147', '900', '09374342100', '', '2025-03-28 07:31:17.247399', '', ''),
(11, 'aGosqu4Y0p6S5HrNQB9e', '4', 'Ayush Das', '198035264', 'Fixed Deposit Account ', '', 'Deposit', 'Success ', '6', 'Ayush Das', '8854475621', '300000', '2217854126', '', '2025-03-28 09:48:04.338332', '', ''),
(12, 'kciYOKFvqpQ9C0syH6bx', '4', 'Ayush Das', '198035264', 'Fixed Deposit Account ', '', 'Withdrawal', 'Success ', '6', 'Ayush Das', '8854475621', '5000', '2217854126', '', '2025-03-28 09:49:21.560874', '', ''),
(13, 'ph98klGdMwUHoQPgVCtm', '4', 'Ayush Das', '198035264', 'Fixed Deposit Account ', '', 'Transfer', 'Success ', '6', 'Ayush Das', '8854475621', '1200', '2217854126', '742893065', '2025-03-28 10:01:05.211666', 'Ajay Sharma', 'Ajay Sharma'),
(14, 'jZOwmBkQplqP7y1vNbnK', '3', 'Lily M. Rai', '872564319', 'Fixed Deposit Account ', '', 'Withdrawal', 'Success ', '3', 'Lily Rai', '1102245862', '500', '1008791253', '', '2025-03-28 10:48:11.422413', '', ''),
(15, 'jU5YtvBh2dGMDoZ3ucRg', '3', 'Lily M. Rai', '872564319', 'Fixed Deposit Account ', '', 'Withdrawal', 'Success ', '3', 'Lily Rai', '1102245862', '400', '1008791253', '', '2025-03-28 10:48:38.741694', '', ''),
(16, 'SteyTA1c0laMn7jYoPND', '3', 'Lily M. Rai', '872564319', 'Fixed Deposit Account ', '', 'Deposit', 'Success ', '3', 'Lily Rai', '1102245862', '200', '1008791253', '', '2025-03-28 10:54:46.365165', '', ''),
(17, 'KDwXlFrB1xEj76vOtsbh', '3', 'Lily M. Rai', '872564319', 'Fixed Deposit Account ', '', 'Withdrawal', 'Success ', '3', 'Lily Rai', '1102245862', '100', '1008791253', '', '2025-03-28 11:53:15.318329', '', ''),
(18, 'v5oBd4sHPTEYrew8bahz', '3', 'Lily M. Rai', '872564319', 'Fixed Deposit Account ', '', 'Deposit', 'Success ', '3', 'Lily Rai', '1102245862', '200', '1008791253', '', '2025-03-28 11:53:44.435894', '', ''),
(19, '8s61lBdTypfv7SeVgNkI', '3', 'Lily M. Rai', '872564319', 'Fixed Deposit Account ', '', 'Deposit', 'Success ', '3', 'Lily Rai', '1102245862', '500', '1008791253', '', '2025-03-29 05:41:54.533908', '', '');

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
  MODIFY `admin_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ib_bankaccounts`
--
ALTER TABLE `ib_bankaccounts`
  MODIFY `account_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ib_clients`
--
ALTER TABLE `ib_clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ib_notifications`
--
ALTER TABLE `ib_notifications`
  MODIFY `notification_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ib_staff`
--
ALTER TABLE `ib_staff`
  MODIFY `staff_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ib_systemsettings`
--
ALTER TABLE `ib_systemsettings`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ib_transactions`
--
ALTER TABLE `ib_transactions`
  MODIFY `tr_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
