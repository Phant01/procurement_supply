-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2026 at 10:09 AM
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
-- Database: `supply_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `ics`
--

CREATE TABLE `ics` (
  `ics_id` int(10) UNSIGNED NOT NULL,
  `ris_item_id` int(10) UNSIGNED NOT NULL,
  `personnel_id` int(10) UNSIGNED NOT NULL,
  `ics_number` varchar(50) NOT NULL,
  `ics_date` date NOT NULL,
  `quantity` decimal(15,4) NOT NULL DEFAULT 1.0000,
  `unit_cost` decimal(15,2) NOT NULL,
  `total_cost` decimal(15,2) GENERATED ALWAYS AS (`quantity` * `unit_cost`) STORED,
  `estimated_life` varchar(50) DEFAULT NULL COMMENT 'Useful life, e.g. 5 years',
  `property_no` varchar(100) DEFAULT NULL COMMENT 'Assigned property / inventory number',
  `location` varchar(200) DEFAULT NULL,
  `status` enum('active','returned','written_off') NOT NULL DEFAULT 'active',
  `returned_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `item_code` varchar(50) DEFAULT NULL,
  `item_name` varchar(300) NOT NULL,
  `description` text DEFAULT NULL,
  `unit_of_measure` varchar(30) NOT NULL COMMENT 'e.g. piece, ream, box, bottle, unit',
  `uacs_code` varchar(30) DEFAULT NULL COMMENT 'Unified Accounts Code Structure',
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `reorder_point` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Minimum stock level before re-ordering',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `category_id`, `item_code`, `item_name`, `description`, `unit_of_measure`, `uacs_code`, `unit_cost`, `reorder_point`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 8, '0001', 'desktop', 'Desktop all in one', 'unit', '', 60000.00, 0.00, 1, '2026-04-22 03:14:09', '2026-04-22 03:14:09'),
(3, 5, '0002', 'Laptop', 'Laptop for Server', 'unit', '', 50000.00, 0.00, 1, '2026-04-23 02:14:48', '2026-04-29 00:31:12'),
(4, 1, '0003', 'Copy Paper A4', 'A4 Paper', 'box', '', 2000.00, 0.00, 1, '2026-04-28 23:45:18', '2026-04-28 23:45:18'),
(5, 1, '0004', 'Box', 'Box with lid', 'pc', '', 1000.00, 0.00, 1, '2026-04-29 08:01:45', '2026-04-29 08:01:45'),
(6, 1, '0005', 'Ballpen', 'Black Ballpen .5', 'box', '', 100.00, 0.00, 1, '2026-04-30 06:16:01', '2026-04-30 06:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `item_type` enum('consumable','semi_expendable','equipment') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`category_id`, `category_name`, `item_type`, `description`, `created_at`) VALUES
(1, 'Office Supplies', 'consumable', 'Bond paper, pens, folders, staple wire, etc.', '2026-04-20 23:31:40'),
(2, 'Janitorial & Sanitation', 'consumable', 'Cleaning materials, disinfectants, trash bags', '2026-04-20 23:31:40'),
(3, 'Medical & First Aid Supplies', 'consumable', 'Consumable medical and first aid items', '2026-04-20 23:31:40'),
(4, 'Fuel & Lubricants', 'consumable', 'Gasoline, diesel, oil, lubricants', '2026-04-20 23:31:40'),
(5, 'Semi-Expendable IT Items', 'semi_expendable', 'IT peripherals below capitalization threshold', '2026-04-20 23:31:40'),
(6, 'Semi-Expendable Furniture', 'semi_expendable', 'Furniture & fixtures below capitalization threshold', '2026-04-20 23:31:40'),
(7, 'Semi-Expendable Equipment', 'semi_expendable', 'Other equipment below capitalization threshold', '2026-04-20 23:31:40'),
(8, 'IT Equipment (PPE)', 'equipment', 'Computers, servers, printers above threshold', '2026-04-20 23:31:40'),
(9, 'Office Furniture (PPE)', 'equipment', 'Furniture & fixtures above capitalization threshold', '2026-04-20 23:31:40'),
(10, 'Machinery & Equipment (PPE)', 'equipment', 'Heavy or specialized equipment above threshold', '2026-04-20 23:31:40');

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

CREATE TABLE `offices` (
  `office_id` int(10) UNSIGNED NOT NULL,
  `office_code` varchar(20) NOT NULL,
  `office_name` varchar(200) NOT NULL,
  `department` varchar(200) DEFAULT NULL,
  `head_of_office` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offices`
--

INSERT INTO `offices` (`office_id`, `office_code`, `office_name`, `department`, `head_of_office`, `is_active`, `created_at`) VALUES
(1, '001', 'FAO', 'Human Resource Unit', 'Lance Angelo B. Layugan', 1, '2026-04-21 08:40:50'),
(3, '002', 'FAO', 'Finance Unit', 'Emma Grace F. Sabarino', 1, '2026-04-22 00:55:21'),
(4, '003', 'FAO', 'Procurement and Supply Unit', 'Jerlyne S. Binay-an', 1, '2026-04-22 00:56:02'),
(5, '004', 'FAO', 'Records Unit', 'Shanen Marie B. Doroy', 1, '2026-04-22 00:56:54'),
(6, '005', 'Office of the Administrator', 'Executive and Administrative Services Section', 'Swenchell M. Borason', 1, '2026-04-22 00:59:17'),
(7, '006', 'Office of the Administrator', 'Legal Services Section', 'Liberato O. Ramos, Jr.', 1, '2026-04-22 01:00:11'),
(8, '007', 'Planning Office', 'Planning', 'Marcial Aloda', 1, '2026-04-22 01:06:35'),
(9, '008', 'Operations Office', 'Operations', 'Honasan P. Ollasic', 1, '2026-04-22 01:07:21');

-- --------------------------------------------------------

--
-- Table structure for table `par`
--

CREATE TABLE `par` (
  `par_id` int(10) UNSIGNED NOT NULL,
  `ris_item_id` int(10) UNSIGNED NOT NULL,
  `personnel_id` int(10) UNSIGNED NOT NULL,
  `par_number` varchar(50) NOT NULL,
  `par_date` date NOT NULL,
  `quantity` decimal(15,4) NOT NULL DEFAULT 1.0000,
  `unit_cost` decimal(15,2) NOT NULL,
  `total_cost` decimal(15,2) GENERATED ALWAYS AS (`quantity` * `unit_cost`) STORED,
  `property_no` varchar(100) DEFAULT NULL COMMENT 'Assigned property number',
  `serial_no` varchar(100) DEFAULT NULL COMMENT 'Manufacturer serial number',
  `brand_model` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `status` enum('active','transferred','returned','disposed') NOT NULL DEFAULT 'active',
  `transfer_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `par`
--

INSERT INTO `par` (`par_id`, `ris_item_id`, `personnel_id`, `par_number`, `par_date`, `quantity`, `unit_cost`, `property_no`, `serial_no`, `brand_model`, `location`, `status`, `transfer_date`, `remarks`, `created_at`) VALUES
(2, 6, 5, 'PAR-20260428-00006', '2026-04-28', 1.0000, 0.00, 'PAR-PROP-6', '', '', '', 'active', NULL, NULL, '2026-04-28 07:08:15'),
(3, 7, 5, 'PAR-20260429-00007', '2026-04-29', 1.0000, 60000.00, 'PAR-PROP-7', '', '', '', 'active', NULL, NULL, '2026-04-28 23:38:59');

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `personnel_id` int(10) UNSIGNED NOT NULL,
  `office_id` int(10) UNSIGNED NOT NULL,
  `employee_no` varchar(30) DEFAULT NULL,
  `full_name` varchar(200) NOT NULL,
  `position` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`personnel_id`, `office_id`, `employee_no`, `full_name`, `position`, `is_active`, `created_at`) VALUES
(2, 6, '01', 'Maria Monica C. Costales', 'ASEC', 1, '2026-04-22 01:12:53'),
(3, 7, '02', 'Liberato O. Ramos, Jr.', 'Attorney III', 1, '2026-04-22 01:14:00'),
(4, 6, '03', 'Swenchell M. Borason', 'Executive Assistant', 1, '2026-04-22 01:15:55'),
(5, 3, '04', 'Carlo T. Palpal', 'Supervising Administrative Officer', 1, '2026-04-22 01:17:53'),
(6, 8, '05', 'Marcial Aloda', 'Planning Officer IV', 1, '2026-04-22 01:20:40'),
(7, 9, '06', 'Honasan P. Ollasic', 'Project Development Officer IV', 1, '2026-04-22 01:22:09'),
(8, 1, '07', 'Lance Angelo B. Layugan', 'HRMO V', 1, '2026-04-29 08:02:41');

-- --------------------------------------------------------

--
-- Table structure for table `po_items`
--

CREATE TABLE `po_items` (
  `po_item_id` int(10) UNSIGNED NOT NULL,
  `po_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `unit_of_measure` varchar(30) NOT NULL,
  `qty_ordered` decimal(15,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) GENERATED ALWAYS AS (`qty_ordered` * `unit_price`) STORED,
  `qty_received` decimal(15,4) NOT NULL DEFAULT 0.0000 COMMENT 'Cumulative qty received across all deliveries',
  `remarks` varchar(300) DEFAULT NULL
) ;

--
-- Dumping data for table `po_items`
--

INSERT INTO `po_items` (`po_item_id`, `po_id`, `item_id`, `unit_of_measure`, `qty_ordered`, `unit_price`, `qty_received`, `remarks`) VALUES
(7, 8, 2, 'unit', 1.0000, 60000.00, 1.0000, NULL),
(8, 9, 2, 'unit', 1.0000, 60000.00, 1.0000, NULL),
(9, 9, 3, 'unit', 1.0000, 49000.00, 1.0000, NULL),
(10, 10, 4, 'box', 1.0000, 2000.00, 1.0000, NULL),
(11, 11, 3, 'unit', 5.0000, 50000.00, 5.0000, NULL),
(12, 12, 5, 'pc', 10.0000, 1000.00, 10.0000, NULL),
(13, 14, 6, 'box', 1.0000, 100.00, 1.0000, NULL),
(14, 15, 6, 'box', 1.0000, 100.00, 1.0000, NULL),
(15, 16, 6, 'box', 10.0000, 100.00, 10.0000, NULL),
(16, 17, 6, 'box', 21.0000, 100.00, 0.0000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `po_receipts`
--

CREATE TABLE `po_receipts` (
  `receipt_id` int(10) UNSIGNED NOT NULL,
  `po_id` int(10) UNSIGNED NOT NULL,
  `iar_number` varchar(50) NOT NULL COMMENT 'Inspection and Acceptance Report number',
  `receipt_date` date NOT NULL,
  `delivery_ref` varchar(100) DEFAULT NULL COMMENT 'Supplier Delivery Receipt or Invoice number',
  `received_by` varchar(150) DEFAULT NULL,
  `inspected_by` varchar(150) DEFAULT NULL,
  `approved_by` varchar(150) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `po_receipts`
--

INSERT INTO `po_receipts` (`receipt_id`, `po_id`, `iar_number`, `receipt_date`, `delivery_ref`, `received_by`, `inspected_by`, `approved_by`, `remarks`, `created_at`) VALUES
(12, 8, 'IAR1', '2026-04-28', '3437377', 'Jaime', 'Inspection', 'Jerlyn', 'good', '2026-04-28 06:43:15'),
(13, 9, 'IAR2', '2026-04-29', '343736', 'Jaime', 'Inspection', 'Jerlyn', 'Good', '2026-04-28 23:33:53'),
(14, 10, 'IAR3', '2026-04-29', '234323344', 'Jaime', 'Inspection', 'Jerlyn', 'goods', '2026-04-28 23:58:00'),
(15, 11, 'IAR4', '2026-04-29', '324321', 'Jaime', 'Inspection', 'Jerlyn', 'Nicce nice', '2026-04-29 00:31:12'),
(16, 12, 'IAR5', '2026-04-29', '454332', 'Jaime', 'Inspection', 'Jerlyn', 'goods', '2026-04-29 08:05:11'),
(17, 14, 'IAR6', '2026-04-30', '54654646', 'Jaime', 'Inspection', 'Jerlyn', 'good', '2026-04-30 06:18:44'),
(18, 15, 'IAR7', '2026-05-04', '56757567', 'Jaime', 'Inspection', 'Jerlyn', 'jghgh', '2026-05-04 03:47:20'),
(19, 16, 'IAR8', '2026-05-04', '34535334', 'Jaime', 'Inspection', 'Jerlyn', 'asdga', '2026-05-04 06:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `po_receipt_items`
--

CREATE TABLE `po_receipt_items` (
  `receipt_item_id` int(10) UNSIGNED NOT NULL,
  `receipt_id` int(10) UNSIGNED NOT NULL,
  `po_item_id` int(10) UNSIGNED NOT NULL,
  `qty_received` decimal(15,4) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL,
  `remarks` varchar(300) DEFAULT NULL
) ;

--
-- Dumping data for table `po_receipt_items`
--

INSERT INTO `po_receipt_items` (`receipt_item_id`, `receipt_id`, `po_item_id`, `qty_received`, `unit_cost`, `remarks`) VALUES
(9, 12, 7, 1.0000, 60000.00, NULL),
(10, 13, 8, 1.0000, 60000.00, NULL),
(11, 13, 9, 1.0000, 49000.00, NULL),
(12, 14, 10, 1.0000, 2000.00, NULL),
(13, 15, 11, 5.0000, 50000.00, NULL),
(14, 16, 12, 10.0000, 1000.00, NULL),
(15, 17, 13, 1.0000, 100.00, NULL),
(16, 18, 14, 1.0000, 100.00, NULL),
(17, 19, 15, 10.0000, 100.00, NULL);

--
-- Triggers `po_receipt_items`
--
DELIMITER $$
CREATE TRIGGER `trg_after_recv_item_insert` AFTER INSERT ON `po_receipt_items` FOR EACH ROW BEGIN
    DECLARE v_item_id      INT UNSIGNED;
    DECLARE v_sc_id        INT UNSIGNED;
    DECLARE v_new_balance  DECIMAL(15,4);
    DECLARE v_iar_number   VARCHAR(50);
    DECLARE v_receipt_date DATE;

    -- Resolve item_id
    SELECT pi.item_id INTO v_item_id
    FROM po_items pi
    WHERE pi.po_item_id = NEW.po_item_id;

    -- Auto-create stock card if missing
    IF NOT EXISTS (
        SELECT 1 FROM stock_cards WHERE item_id = v_item_id
    ) THEN
        INSERT INTO stock_cards (item_id, balance_qty)
        VALUES (v_item_id, 0);
    END IF;

    -- Get stock card id
    SELECT sc.stock_card_id INTO v_sc_id
    FROM stock_cards sc
    WHERE sc.item_id = v_item_id;

    -- Get IAR info
    SELECT pr.iar_number, pr.receipt_date
    INTO v_iar_number, v_receipt_date
    FROM po_receipts pr
    WHERE pr.receipt_id = NEW.receipt_id;

    -- New balance
    SET v_new_balance = (
        SELECT balance_qty FROM stock_cards
        WHERE stock_card_id = v_sc_id
    ) + NEW.qty_received;

    -- Write stock card entry
    INSERT INTO stock_card_entries
        (stock_card_id, txn_date, ref_type, ref_id,
         ref_number, qty_in, qty_out, unit_cost, balance)
    VALUES
        (v_sc_id, v_receipt_date, 'RECEIPT', NEW.receipt_item_id,
         v_iar_number, NEW.qty_received, 0, NEW.unit_cost, v_new_balance);

    -- Update balance
    UPDATE stock_cards
    SET balance_qty = v_new_balance
    WHERE stock_card_id = v_sc_id;

    -- Update cumulative received on PO line
    UPDATE po_items
    SET qty_received = qty_received + NEW.qty_received
    WHERE po_item_id = NEW.po_item_id;

    -- Update item unit cost
    UPDATE items
    SET unit_cost = NEW.unit_cost
    WHERE item_id = v_item_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `po_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `office_id` int(10) UNSIGNED NOT NULL COMMENT 'End-user / requesting office',
  `po_number` varchar(50) NOT NULL,
  `po_date` date NOT NULL,
  `delivery_date` date DEFAULT NULL COMMENT 'Expected delivery date',
  `place_of_delivery` varchar(300) DEFAULT NULL,
  `fund_source` varchar(100) DEFAULT NULL COMMENT 'e.g. GAA, SEF, MOOE, Capital Outlay',
  `mode_of_procurement` varchar(100) DEFAULT NULL COMMENT 'e.g. Shopping, Small Value Procurement',
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','approved','partially_received','fully_received','cancelled') NOT NULL DEFAULT 'draft',
  `approved_by` varchar(150) DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `cancelled_reason` varchar(300) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`po_id`, `supplier_id`, `office_id`, `po_number`, `po_date`, `delivery_date`, `place_of_delivery`, `fund_source`, `mode_of_procurement`, `total_amount`, `status`, `approved_by`, `approved_date`, `cancelled_reason`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(8, 2, 1, 'PO-1', '2026-04-26', '2026-04-28', '3rd Floor Gestdan', 'MOOE', 'Small Value Procurement', 60000.00, 'fully_received', 'PS-DBM', '2026-04-27', NULL, NULL, 'Administrator', '2026-04-28 06:17:44', '2026-04-28 06:43:15'),
(9, 2, 1, 'PO-2', '2026-04-29', '2026-04-29', '3rd Floor Gestdan', 'MOOE', 'Small Value Procurement', 109000.00, 'fully_received', 'PS-DBM', '2026-04-29', NULL, NULL, 'Administrator', '2026-04-28 23:18:55', '2026-04-28 23:33:53'),
(10, 2, 9, 'PO-3', '2026-04-29', '2026-04-29', '3rd Floor Gestdan', 'MOOE', 'Small Value Procurement', 2000.00, 'fully_received', 'PS-DBM', '2026-04-29', NULL, NULL, 'Administrator', '2026-04-28 23:57:22', '2026-04-28 23:58:00'),
(11, 2, 8, 'PO-4', '2026-04-29', '2026-04-29', '3rd Floor Gestdan', 'MOOE', 'Small Value Procurement', 250000.00, 'fully_received', 'PS-DBM', '2026-04-29', NULL, NULL, 'Administrator', '2026-04-29 00:10:20', '2026-04-29 00:31:12'),
(12, 3, 1, 'PO-5', '2026-04-29', '2026-04-29', '3rd Floor Gestdan', 'MOOE', 'Small Value Procurement', 10000.00, 'fully_received', 'Janjan', '2026-04-29', NULL, NULL, 'Administrator', '2026-04-29 08:04:24', '2026-04-29 08:05:11'),
(14, 4, 1, 'PO-6', '2026-04-30', '2026-04-30', '3rd Floor Gestdan', 'MOOE', 'Small Value Procurement', 100.00, 'fully_received', 'TSS', '2026-04-30', NULL, NULL, 'Administrator', '2026-04-30 06:17:43', '2026-04-30 06:18:44'),
(15, 4, 3, 'PO-7', '2026-05-04', '2026-05-04', '3rd Floor Gestdan', 'MOOE', 'Small Value Procurement', 100.00, 'fully_received', 'Tiongsan', '2026-05-04', NULL, NULL, 'Administrator', '2026-05-04 02:42:15', '2026-05-04 03:47:20'),
(16, 4, 3, 'PO-8', '2026-05-04', '2026-05-04', '3rd Floor Gestdan', 'MOOE', 'Small Value Procurement', 1000.00, 'fully_received', 'TSS', '2026-05-04', NULL, NULL, 'Administrator', '2026-05-04 03:48:40', '2026-05-04 06:28:32'),
(17, 3, 3, 'PO-9', '2026-05-04', '2026-05-04', '3rd Floor Gestdan', 'MOOE', 'Small Value Procurement', 2100.00, 'approved', 'TSS', '2026-05-04', NULL, NULL, 'Administrator', '2026-05-04 06:29:56', '2026-05-04 06:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `ris`
--

CREATE TABLE `ris` (
  `ris_id` int(10) UNSIGNED NOT NULL,
  `office_id` int(10) UNSIGNED NOT NULL,
  `ris_number` varchar(50) NOT NULL,
  `ris_date` date NOT NULL,
  `purpose` varchar(500) DEFAULT NULL,
  `requested_by` varchar(150) DEFAULT NULL,
  `approved_by` varchar(150) DEFAULT NULL,
  `issued_by` varchar(150) DEFAULT NULL,
  `received_by` varchar(150) DEFAULT NULL,
  `status` enum('pending','approved','issued','cancelled') NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ris`
--

INSERT INTO `ris` (`ris_id`, `office_id`, `ris_number`, `ris_date`, `purpose`, `requested_by`, `approved_by`, `issued_by`, `received_by`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(6, 1, 'RIS-01', '2026-04-28', 'Office Use', 'Jamon', 'Jerlyn', 'Jerlyn', 'Jejemon', 'issued', NULL, '2026-04-28 06:44:11', '2026-04-28 07:08:15'),
(7, 1, 'RIS-02', '2026-04-29', 'Office Use', 'Jadem', 'Jerlyn', 'Jerlyn', 'Jadem', 'issued', NULL, '2026-04-28 23:37:11', '2026-04-28 23:38:59'),
(8, 9, 'RIS-03', '2026-04-29', 'Office Use', 'Hon', 'Jerlyn', 'Jerlyn', 'Hon', 'issued', NULL, '2026-04-28 23:58:46', '2026-04-29 00:00:24'),
(9, 1, 'RIS-04', '2026-04-30', 'Office Use', 'Lance', 'Jerlyn', 'Jerlyn', 'Lance', 'issued', NULL, '2026-04-29 23:29:29', '2026-04-29 23:30:17');

-- --------------------------------------------------------

--
-- Table structure for table `ris_items`
--

CREATE TABLE `ris_items` (
  `ris_item_id` int(10) UNSIGNED NOT NULL,
  `ris_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `qty_requested` decimal(15,4) NOT NULL,
  `qty_issued` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remarks` varchar(300) DEFAULT NULL
) ;

--
-- Dumping data for table `ris_items`
--

INSERT INTO `ris_items` (`ris_item_id`, `ris_id`, `item_id`, `qty_requested`, `qty_issued`, `unit_cost`, `remarks`) VALUES
(6, 6, 2, 1.0000, 1.0000, 0.00, NULL),
(7, 7, 2, 1.0000, 1.0000, 60000.00, NULL),
(8, 8, 4, 1.0000, 1.0000, 2000.00, NULL),
(9, 9, 5, 9.0000, 9.0000, 1000.00, NULL);

--
-- Triggers `ris_items`
--
DELIMITER $$
CREATE TRIGGER `trg_after_ris_item_update` AFTER UPDATE ON `ris_items` FOR EACH ROW BEGIN
    DECLARE v_sc_id       INT UNSIGNED;
    DECLARE v_new_balance DECIMAL(15,4);
    DECLARE v_ris_number  VARCHAR(50);
    DECLARE v_ris_date    DATE;
    DECLARE v_office_id   INT UNSIGNED;

    -- Only act when qty_issued changes from 0 to a positive value
    IF NEW.qty_issued > 0 AND OLD.qty_issued = 0 THEN

        SELECT sc.stock_card_id
          INTO v_sc_id
          FROM stock_cards sc
         WHERE sc.item_id = NEW.item_id;

        SELECT r.ris_number, r.ris_date, r.office_id
          INTO v_ris_number, v_ris_date, v_office_id
          FROM ris r
         WHERE r.ris_id = NEW.ris_id;

        SET v_new_balance = (
            SELECT balance_qty FROM stock_cards WHERE stock_card_id = v_sc_id
        ) - NEW.qty_issued;

        INSERT INTO stock_card_entries
            (stock_card_id, txn_date, ref_type, ref_id,
             ref_number, office_id, qty_in, qty_out, unit_cost, balance)
        VALUES
            (v_sc_id, v_ris_date, 'ISSUANCE', NEW.ris_item_id,
             v_ris_number, v_office_id, 0, NEW.qty_issued, NEW.unit_cost, v_new_balance);

        UPDATE stock_cards
           SET balance_qty = v_new_balance
         WHERE stock_card_id = v_sc_id;

    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `stock_cards`
--

CREATE TABLE `stock_cards` (
  `stock_card_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `balance_qty` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_cards`
--

INSERT INTO `stock_cards` (`stock_card_id`, `item_id`, `balance_qty`, `last_updated`) VALUES
(4, 3, 6.0000, '2026-04-29 00:31:12'),
(5, 2, 0.0000, '2026-04-28 23:38:59'),
(6, 4, 0.0000, '2026-04-29 00:00:24'),
(7, 5, 1.0000, '2026-04-29 23:30:17'),
(8, 6, 12.0000, '2026-05-04 06:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `stock_card_entries`
--

CREATE TABLE `stock_card_entries` (
  `entry_id` int(10) UNSIGNED NOT NULL,
  `stock_card_id` int(10) UNSIGNED NOT NULL,
  `txn_date` date NOT NULL,
  `ref_type` enum('RECEIPT','ISSUANCE','RETURN','ADJUSTMENT') NOT NULL,
  `ref_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to po_receipt_items or ris_items depending on ref_type',
  `ref_number` varchar(100) NOT NULL COMMENT 'IAR no. or RIS no. for display on stock card',
  `office_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Issuing/receiving office (NULL for adjustments)',
  `qty_in` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `qty_out` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(15,4) NOT NULL COMMENT 'Running balance after this transaction',
  `remarks` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `stock_card_entries`
--

INSERT INTO `stock_card_entries` (`entry_id`, `stock_card_id`, `txn_date`, `ref_type`, `ref_id`, `ref_number`, `office_id`, `qty_in`, `qty_out`, `unit_cost`, `balance`, `remarks`, `created_at`) VALUES
(9, 5, '2026-04-28', 'RECEIPT', 9, 'IAR1', NULL, 1.0000, 0.0000, 60000.00, 1.0000, NULL, '2026-04-28 06:43:15'),
(10, 5, '2026-04-28', 'ISSUANCE', 6, 'RIS-01', 1, 0.0000, 1.0000, 0.00, 0.0000, NULL, '2026-04-28 07:08:15'),
(11, 5, '2026-04-29', 'RECEIPT', 10, 'IAR2', NULL, 1.0000, 0.0000, 60000.00, 1.0000, NULL, '2026-04-28 23:33:53'),
(12, 4, '2026-04-29', 'RECEIPT', 11, 'IAR2', NULL, 1.0000, 0.0000, 49000.00, 1.0000, NULL, '2026-04-28 23:33:53'),
(13, 5, '2026-04-29', 'ISSUANCE', 7, 'RIS-02', 1, 0.0000, 1.0000, 60000.00, 0.0000, NULL, '2026-04-28 23:38:59'),
(14, 6, '2026-04-29', 'RECEIPT', 12, 'IAR3', NULL, 1.0000, 0.0000, 2000.00, 1.0000, NULL, '2026-04-28 23:58:00'),
(15, 6, '2026-04-29', 'ISSUANCE', 8, 'RIS-03', 9, 0.0000, 1.0000, 2000.00, 0.0000, NULL, '2026-04-29 00:00:24'),
(16, 4, '2026-04-29', 'RECEIPT', 13, 'IAR4', NULL, 5.0000, 0.0000, 50000.00, 6.0000, NULL, '2026-04-29 00:31:12'),
(17, 7, '2026-04-29', 'RECEIPT', 14, 'IAR5', NULL, 10.0000, 0.0000, 1000.00, 10.0000, NULL, '2026-04-29 08:05:11'),
(18, 7, '2026-04-30', 'ISSUANCE', 9, 'RIS-04', 1, 0.0000, 9.0000, 1000.00, 1.0000, NULL, '2026-04-29 23:30:17'),
(19, 8, '2026-04-30', 'RECEIPT', 15, 'IAR6', NULL, 1.0000, 0.0000, 100.00, 1.0000, NULL, '2026-04-30 06:18:44'),
(20, 8, '2026-05-04', 'RECEIPT', 16, 'IAR7', NULL, 1.0000, 0.0000, 100.00, 2.0000, NULL, '2026-05-04 03:47:20'),
(21, 8, '2026-05-04', 'RECEIPT', 17, 'IAR8', NULL, 10.0000, 0.0000, 100.00, 12.0000, NULL, '2026-05-04 06:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `supplier_name` varchar(200) NOT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tin_no` varchar(20) DEFAULT NULL COMMENT 'Tax Identification Number',
  `philgeps_reg_no` varchar(50) DEFAULT NULL COMMENT 'PhilGEPS registration number',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `contact_person`, `telephone`, `mobile`, `email`, `address`, `tin_no`, `philgeps_reg_no`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'PS_DBM', '', '', '', '', '', '', '', 1, '2026-04-22 02:53:41', '2026-04-22 02:53:41'),
(3, 'Kunware Store', 'Janjan', '', '09343923993', '', '', '', '', 1, '2026-04-29 08:03:17', '2026-04-29 08:03:17'),
(4, 'Tiongsan', '', '', '', '', '', '', '', 1, '2026-04-30 06:16:21', '2026-04-30 06:16:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `personnel_id` int(10) UNSIGNED DEFAULT NULL,
  `username` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'bcrypt hash',
  `full_name` varchar(200) NOT NULL,
  `role` enum('admin','supply_officer','viewer') NOT NULL DEFAULT 'viewer',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `personnel_id`, `username`, `password`, `full_name`, `role`, `is_active`, `last_login`, `created_at`) VALUES
(1, NULL, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', 1, NULL, '2026-04-21 03:36:14'),
(3, NULL, 'adminassistant', 'Admin@1234', 'ADAS II', 'admin', 1, NULL, '2026-04-21 04:11:18');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_current_stock`
-- (See below for the actual view)
--
CREATE TABLE `v_current_stock` (
`stock_card_id` int(10) unsigned
,`item_id` int(10) unsigned
,`item_code` varchar(50)
,`item_name` varchar(300)
,`unit_of_measure` varchar(30)
,`category_name` varchar(100)
,`item_type` enum('consumable','semi_expendable','equipment')
,`uacs_code` varchar(30)
,`balance_qty` decimal(15,4)
,`unit_cost` decimal(15,2)
,`total_value` decimal(27,2)
,`last_updated` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_ics_registry`
-- (See below for the actual view)
--
CREATE TABLE `v_ics_registry` (
`ics_id` int(10) unsigned
,`ics_number` varchar(50)
,`ics_date` date
,`property_no` varchar(100)
,`item_id` int(10) unsigned
,`item_name` varchar(300)
,`unit_of_measure` varchar(30)
,`category_name` varchar(100)
,`quantity` decimal(15,4)
,`unit_cost` decimal(15,2)
,`total_cost` decimal(15,2)
,`estimated_life` varchar(50)
,`location` varchar(200)
,`assigned_to` varchar(200)
,`position` varchar(150)
,`office_name` varchar(200)
,`status` enum('active','returned','written_off')
,`returned_date` date
,`remarks` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_par_registry`
-- (See below for the actual view)
--
CREATE TABLE `v_par_registry` (
`par_id` int(10) unsigned
,`par_number` varchar(50)
,`par_date` date
,`property_no` varchar(100)
,`serial_no` varchar(100)
,`brand_model` varchar(200)
,`item_id` int(10) unsigned
,`item_name` varchar(300)
,`unit_of_measure` varchar(30)
,`category_name` varchar(100)
,`quantity` decimal(15,4)
,`unit_cost` decimal(15,2)
,`total_cost` decimal(15,2)
,`location` varchar(200)
,`assigned_to` varchar(200)
,`position` varchar(150)
,`office_name` varchar(200)
,`status` enum('active','transferred','returned','disposed')
,`transfer_date` date
,`remarks` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_rsmi_base`
-- (See below for the actual view)
--
CREATE TABLE `v_rsmi_base` (
`period` varchar(7)
,`txn_date` date
,`ris_id` int(10) unsigned
,`ris_number` varchar(50)
,`ris_date` date
,`purpose` varchar(500)
,`office_id` int(10) unsigned
,`office_name` varchar(200)
,`department` varchar(200)
,`item_id` int(10) unsigned
,`item_code` varchar(50)
,`item_name` varchar(300)
,`unit_of_measure` varchar(30)
,`uacs_code` varchar(30)
,`item_type` enum('consumable','semi_expendable','equipment')
,`qty_issued` decimal(15,4)
,`unit_cost` decimal(15,2)
,`total_cost` decimal(27,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_stock_card_ledger`
-- (See below for the actual view)
--
CREATE TABLE `v_stock_card_ledger` (
`entry_id` int(10) unsigned
,`item_id` int(10) unsigned
,`item_code` varchar(50)
,`item_name` varchar(300)
,`unit_of_measure` varchar(30)
,`txn_date` date
,`ref_type` enum('RECEIPT','ISSUANCE','RETURN','ADJUSTMENT')
,`ref_number` varchar(100)
,`office_name` varchar(200)
,`qty_in` decimal(15,4)
,`qty_out` decimal(15,4)
,`unit_cost` decimal(15,2)
,`amount_in` decimal(27,2)
,`amount_out` decimal(27,2)
,`balance` decimal(15,4)
,`remarks` varchar(300)
);

-- --------------------------------------------------------

--
-- Structure for view `v_current_stock`
--
DROP TABLE IF EXISTS `v_current_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_current_stock`  AS SELECT `sc`.`stock_card_id` AS `stock_card_id`, `i`.`item_id` AS `item_id`, `i`.`item_code` AS `item_code`, `i`.`item_name` AS `item_name`, `i`.`unit_of_measure` AS `unit_of_measure`, `ic`.`category_name` AS `category_name`, `ic`.`item_type` AS `item_type`, `i`.`uacs_code` AS `uacs_code`, `sc`.`balance_qty` AS `balance_qty`, `i`.`unit_cost` AS `unit_cost`, round(`sc`.`balance_qty` * `i`.`unit_cost`,2) AS `total_value`, `sc`.`last_updated` AS `last_updated` FROM ((`stock_cards` `sc` join `items` `i` on(`i`.`item_id` = `sc`.`item_id`)) join `item_categories` `ic` on(`ic`.`category_id` = `i`.`category_id`)) WHERE `i`.`is_active` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `v_ics_registry`
--
DROP TABLE IF EXISTS `v_ics_registry`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_ics_registry`  AS SELECT `ics`.`ics_id` AS `ics_id`, `ics`.`ics_number` AS `ics_number`, `ics`.`ics_date` AS `ics_date`, `ics`.`property_no` AS `property_no`, `i`.`item_id` AS `item_id`, `i`.`item_name` AS `item_name`, `i`.`unit_of_measure` AS `unit_of_measure`, `ic`.`category_name` AS `category_name`, `ics`.`quantity` AS `quantity`, `ics`.`unit_cost` AS `unit_cost`, `ics`.`total_cost` AS `total_cost`, `ics`.`estimated_life` AS `estimated_life`, `ics`.`location` AS `location`, `p`.`full_name` AS `assigned_to`, `p`.`position` AS `position`, `o`.`office_name` AS `office_name`, `ics`.`status` AS `status`, `ics`.`returned_date` AS `returned_date`, `ics`.`remarks` AS `remarks` FROM (((((`ics` join `ris_items` `ri` on(`ri`.`ris_item_id` = `ics`.`ris_item_id`)) join `items` `i` on(`i`.`item_id` = `ri`.`item_id`)) join `item_categories` `ic` on(`ic`.`category_id` = `i`.`category_id`)) join `personnel` `p` on(`p`.`personnel_id` = `ics`.`personnel_id`)) join `offices` `o` on(`o`.`office_id` = `p`.`office_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_par_registry`
--
DROP TABLE IF EXISTS `v_par_registry`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_par_registry`  AS SELECT `par`.`par_id` AS `par_id`, `par`.`par_number` AS `par_number`, `par`.`par_date` AS `par_date`, `par`.`property_no` AS `property_no`, `par`.`serial_no` AS `serial_no`, `par`.`brand_model` AS `brand_model`, `i`.`item_id` AS `item_id`, `i`.`item_name` AS `item_name`, `i`.`unit_of_measure` AS `unit_of_measure`, `ic`.`category_name` AS `category_name`, `par`.`quantity` AS `quantity`, `par`.`unit_cost` AS `unit_cost`, `par`.`total_cost` AS `total_cost`, `par`.`location` AS `location`, `p`.`full_name` AS `assigned_to`, `p`.`position` AS `position`, `o`.`office_name` AS `office_name`, `par`.`status` AS `status`, `par`.`transfer_date` AS `transfer_date`, `par`.`remarks` AS `remarks` FROM (((((`par` join `ris_items` `ri` on(`ri`.`ris_item_id` = `par`.`ris_item_id`)) join `items` `i` on(`i`.`item_id` = `ri`.`item_id`)) join `item_categories` `ic` on(`ic`.`category_id` = `i`.`category_id`)) join `personnel` `p` on(`p`.`personnel_id` = `par`.`personnel_id`)) join `offices` `o` on(`o`.`office_id` = `p`.`office_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_rsmi_base`
--
DROP TABLE IF EXISTS `v_rsmi_base`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rsmi_base`  AS SELECT date_format(`sce`.`txn_date`,'%Y-%m') AS `period`, `sce`.`txn_date` AS `txn_date`, `r`.`ris_id` AS `ris_id`, `r`.`ris_number` AS `ris_number`, `r`.`ris_date` AS `ris_date`, `r`.`purpose` AS `purpose`, `o`.`office_id` AS `office_id`, `o`.`office_name` AS `office_name`, `o`.`department` AS `department`, `i`.`item_id` AS `item_id`, `i`.`item_code` AS `item_code`, `i`.`item_name` AS `item_name`, `i`.`unit_of_measure` AS `unit_of_measure`, `i`.`uacs_code` AS `uacs_code`, `ic`.`item_type` AS `item_type`, `ri`.`qty_issued` AS `qty_issued`, `sce`.`unit_cost` AS `unit_cost`, round(`ri`.`qty_issued` * `sce`.`unit_cost`,2) AS `total_cost` FROM ((((((`stock_card_entries` `sce` join `stock_cards` `sc` on(`sc`.`stock_card_id` = `sce`.`stock_card_id`)) join `items` `i` on(`i`.`item_id` = `sc`.`item_id`)) join `item_categories` `ic` on(`ic`.`category_id` = `i`.`category_id`)) join `ris_items` `ri` on(`ri`.`ris_item_id` = `sce`.`ref_id` and `sce`.`ref_type` = 'ISSUANCE')) join `ris` `r` on(`r`.`ris_id` = `ri`.`ris_id`)) join `offices` `o` on(`o`.`office_id` = `r`.`office_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_stock_card_ledger`
--
DROP TABLE IF EXISTS `v_stock_card_ledger`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stock_card_ledger`  AS SELECT `sce`.`entry_id` AS `entry_id`, `i`.`item_id` AS `item_id`, `i`.`item_code` AS `item_code`, `i`.`item_name` AS `item_name`, `i`.`unit_of_measure` AS `unit_of_measure`, `sce`.`txn_date` AS `txn_date`, `sce`.`ref_type` AS `ref_type`, `sce`.`ref_number` AS `ref_number`, `o`.`office_name` AS `office_name`, `sce`.`qty_in` AS `qty_in`, `sce`.`qty_out` AS `qty_out`, `sce`.`unit_cost` AS `unit_cost`, round(`sce`.`qty_in` * `sce`.`unit_cost`,2) AS `amount_in`, round(`sce`.`qty_out` * `sce`.`unit_cost`,2) AS `amount_out`, `sce`.`balance` AS `balance`, `sce`.`remarks` AS `remarks` FROM (((`stock_card_entries` `sce` join `stock_cards` `sc` on(`sc`.`stock_card_id` = `sce`.`stock_card_id`)) join `items` `i` on(`i`.`item_id` = `sc`.`item_id`)) left join `offices` `o` on(`o`.`office_id` = `sce`.`office_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ics`
--
ALTER TABLE `ics`
  ADD PRIMARY KEY (`ics_id`),
  ADD UNIQUE KEY `uq_ics_number` (`ics_number`),
  ADD KEY `idx_ics_ris_item` (`ris_item_id`),
  ADD KEY `idx_ics_personnel` (`personnel_id`),
  ADD KEY `idx_ics_status` (`status`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `uq_item_code` (`item_code`),
  ADD KEY `idx_items_category` (`category_id`);
ALTER TABLE `items` ADD FULLTEXT KEY `ftx_item_name` (`item_name`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`office_id`),
  ADD UNIQUE KEY `uq_office_code` (`office_code`);

--
-- Indexes for table `par`
--
ALTER TABLE `par`
  ADD PRIMARY KEY (`par_id`),
  ADD UNIQUE KEY `uq_par_number` (`par_number`),
  ADD KEY `idx_par_ris_item` (`ris_item_id`),
  ADD KEY `idx_par_personnel` (`personnel_id`),
  ADD KEY `idx_par_status` (`status`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`personnel_id`),
  ADD UNIQUE KEY `uq_employee_no` (`employee_no`),
  ADD KEY `idx_personnel_office` (`office_id`);

--
-- Indexes for table `po_items`
--
ALTER TABLE `po_items`
  ADD PRIMARY KEY (`po_item_id`),
  ADD KEY `idx_po_items_po` (`po_id`),
  ADD KEY `idx_po_items_item` (`item_id`);

--
-- Indexes for table `po_receipts`
--
ALTER TABLE `po_receipts`
  ADD PRIMARY KEY (`receipt_id`),
  ADD UNIQUE KEY `uq_iar_number` (`iar_number`),
  ADD KEY `idx_receipt_po` (`po_id`),
  ADD KEY `idx_receipt_date` (`receipt_date`);

--
-- Indexes for table `po_receipt_items`
--
ALTER TABLE `po_receipt_items`
  ADD PRIMARY KEY (`receipt_item_id`),
  ADD KEY `idx_recv_items_receipt` (`receipt_id`),
  ADD KEY `idx_recv_items_po_item` (`po_item_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`po_id`),
  ADD UNIQUE KEY `uq_po_number` (`po_number`),
  ADD KEY `idx_po_supplier` (`supplier_id`),
  ADD KEY `idx_po_office` (`office_id`),
  ADD KEY `idx_po_date` (`po_date`),
  ADD KEY `idx_po_status` (`status`);

--
-- Indexes for table `ris`
--
ALTER TABLE `ris`
  ADD PRIMARY KEY (`ris_id`),
  ADD UNIQUE KEY `uq_ris_number` (`ris_number`),
  ADD KEY `idx_ris_office` (`office_id`),
  ADD KEY `idx_ris_date` (`ris_date`),
  ADD KEY `idx_ris_status` (`status`);

--
-- Indexes for table `ris_items`
--
ALTER TABLE `ris_items`
  ADD PRIMARY KEY (`ris_item_id`),
  ADD KEY `idx_ris_items_ris` (`ris_id`),
  ADD KEY `idx_ris_items_item` (`item_id`);

--
-- Indexes for table `stock_cards`
--
ALTER TABLE `stock_cards`
  ADD PRIMARY KEY (`stock_card_id`),
  ADD UNIQUE KEY `uq_sc_item` (`item_id`);

--
-- Indexes for table `stock_card_entries`
--
ALTER TABLE `stock_card_entries`
  ADD PRIMARY KEY (`entry_id`),
  ADD KEY `idx_sce_stock_card` (`stock_card_id`),
  ADD KEY `idx_sce_txn_date` (`txn_date`),
  ADD KEY `idx_sce_ref` (`ref_type`,`ref_id`),
  ADD KEY `idx_sce_office` (`office_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD KEY `idx_suppliers_name` (`supplier_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_username` (`username`),
  ADD KEY `fk_user_personnel` (`personnel_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ics`
--
ALTER TABLE `ics`
  MODIFY `ics_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `office_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `par`
--
ALTER TABLE `par`
  MODIFY `par_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `personnel_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `po_items`
--
ALTER TABLE `po_items`
  MODIFY `po_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `po_receipts`
--
ALTER TABLE `po_receipts`
  MODIFY `receipt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `po_receipt_items`
--
ALTER TABLE `po_receipt_items`
  MODIFY `receipt_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `po_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ris`
--
ALTER TABLE `ris`
  MODIFY `ris_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ris_items`
--
ALTER TABLE `ris_items`
  MODIFY `ris_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_cards`
--
ALTER TABLE `stock_cards`
  MODIFY `stock_card_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stock_card_entries`
--
ALTER TABLE `stock_card_entries`
  MODIFY `entry_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ics`
--
ALTER TABLE `ics`
  ADD CONSTRAINT `fk_ics_personnel` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`personnel_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ics_ris_item` FOREIGN KEY (`ris_item_id`) REFERENCES `ris_items` (`ris_item_id`) ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_category` FOREIGN KEY (`category_id`) REFERENCES `item_categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `par`
--
ALTER TABLE `par`
  ADD CONSTRAINT `fk_par_personnel` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`personnel_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_par_ris_item` FOREIGN KEY (`ris_item_id`) REFERENCES `ris_items` (`ris_item_id`) ON UPDATE CASCADE;

--
-- Constraints for table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `fk_personnel_office` FOREIGN KEY (`office_id`) REFERENCES `offices` (`office_id`) ON UPDATE CASCADE;

--
-- Constraints for table `po_items`
--
ALTER TABLE `po_items`
  ADD CONSTRAINT `fk_po_items_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_items_po` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`po_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `po_receipts`
--
ALTER TABLE `po_receipts`
  ADD CONSTRAINT `fk_receipt_po` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`po_id`) ON UPDATE CASCADE;

--
-- Constraints for table `po_receipt_items`
--
ALTER TABLE `po_receipt_items`
  ADD CONSTRAINT `fk_recv_items_po_item` FOREIGN KEY (`po_item_id`) REFERENCES `po_items` (`po_item_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_recv_items_receipt` FOREIGN KEY (`receipt_id`) REFERENCES `po_receipts` (`receipt_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `fk_po_office` FOREIGN KEY (`office_id`) REFERENCES `offices` (`office_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON UPDATE CASCADE;

--
-- Constraints for table `ris`
--
ALTER TABLE `ris`
  ADD CONSTRAINT `fk_ris_office` FOREIGN KEY (`office_id`) REFERENCES `offices` (`office_id`) ON UPDATE CASCADE;

--
-- Constraints for table `ris_items`
--
ALTER TABLE `ris_items`
  ADD CONSTRAINT `fk_ris_items_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ris_items_ris` FOREIGN KEY (`ris_id`) REFERENCES `ris` (`ris_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_cards`
--
ALTER TABLE `stock_cards`
  ADD CONSTRAINT `fk_stock_card_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON UPDATE CASCADE;

--
-- Constraints for table `stock_card_entries`
--
ALTER TABLE `stock_card_entries`
  ADD CONSTRAINT `fk_sce_office` FOREIGN KEY (`office_id`) REFERENCES `offices` (`office_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sce_stock_card` FOREIGN KEY (`stock_card_id`) REFERENCES `stock_cards` (`stock_card_id`) ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_personnel` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`personnel_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
