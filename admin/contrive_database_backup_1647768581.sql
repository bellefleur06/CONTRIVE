

CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(13,2) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `total_price` decimal(13,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4;




CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

INSERT INTO categories VALUES("1","Foundation");
INSERT INTO categories VALUES("2","Electrical");
INSERT INTO categories VALUES("3","Plumbing");



CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

INSERT INTO clients VALUES("1","Carlo Talion","09354161854","carlotalion@gmail.com","Edsa Extension Corner Roxas Boulevard, Pasay City, Metro Manila","2019-05-25");
INSERT INTO clients VALUES("2","Galtero Cayetano","09146285623","galterocayetano@gmail.com","Piape Boulevard Davao City, Davao Del Sur","2020-08-17");
INSERT INTO clients VALUES("3","Gezane Recto","09561856381","gezanerecto@gmail.com","2/F Eltanal Building, Roxas Avenue, Iligan, Lanao Del Norte","2021-07-02");
INSERT INTO clients VALUES("4","Vincent Lara","09383518463","vincentlara@gmail.com","Legaspi Towers II 1200, Makati City, Metro Manila","2021-04-01");



CREATE TABLE `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payables_id` varchar(255) NOT NULL,
  `price` decimal(13,2) NOT NULL,
  `remaining_amount` decimal(13,2) NOT NULL,
  `payable_status` varchar(255) NOT NULL,
  `date_paid` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

INSERT INTO history VALUES("1","1","2570.00","0.00","Paid","2022-03-13 20:20:54");
INSERT INTO history VALUES("2","4","495.00","4000.00","Partial","2022-03-19 20:17:16");



CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(255) NOT NULL,
  `client` varchar(255) NOT NULL,
  `transportation` decimal(13,2) NOT NULL,
  `consultation_fee` decimal(13,2) NOT NULL,
  `total_miscellaneous` decimal(13,2) NOT NULL,
  `total_materials` decimal(13,2) NOT NULL,
  `total_labor` decimal(13,2) NOT NULL,
  `subtotal` decimal(13,2) NOT NULL,
  `total_tax` decimal(13,2) NOT NULL,
  `total_invoice` decimal(13,2) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_created` date NOT NULL,
  `due_date` date NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

INSERT INTO invoices VALUES("1","1","Galtero Cayetano","500.00","1000.00","1500.00","56800.00","2816.00","61116.00","7333.92","68449.92","Paid","2022-03-20","2023-03-20","2022-03-20 05:54:45");
INSERT INTO invoices VALUES("2","4","","0.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","","0000-00-00","0000-00-00","0000-00-00 00:00:00");
INSERT INTO invoices VALUES("3","3","Gezane Recto","500.00","1000.00","1500.00","209800.00","3472.00","214772.00","25772.64","240544.64","Pending","2022-03-21","2023-03-21","2022-03-20 05:59:03");



CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `log_time` datetime NOT NULL,
  `activity` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8mb4;

INSERT INTO logs VALUES("1","Administrator","2022-02-22 09:34:55","Backuped Database");
INSERT INTO logs VALUES("2","Administrator","2022-02-23 15:56:05","Add New Project Progress Update For Jmb Food Sales - Tiles - 28%");
INSERT INTO logs VALUES("3","Administrator","2022-02-24 18:40:25","Add New Staff - Ella Manahan Accountant");
INSERT INTO logs VALUES("4","Engineer","2022-02-25 08:02:48","Update Account Password");
INSERT INTO logs VALUES("5","Clerk","2022-02-25 08:05:43","Update Account Password");
INSERT INTO logs VALUES("6","Administrator","2022-02-25 08:31:57","Update Project Details of Jmb Food Sales");
INSERT INTO logs VALUES("7","Administrator","2022-02-25 08:32:08","Update Project Details of Jmb Food Sales");
INSERT INTO logs VALUES("8","Administrator","2022-02-25 09:06:46","Add New Staff - Patrick Ail Bandola Engineer");
INSERT INTO logs VALUES("9","Administrator","2022-02-25 09:13:39","Update Staff Details For Kylex Valdez - ");
INSERT INTO logs VALUES("10","Administrator","2022-02-25 09:13:43","Update Staff Details For Kyle Valdez - ");
INSERT INTO logs VALUES("11","Administrator","2022-02-25 09:13:58","Delete Staff - Patrick Ail Bandola - ");
INSERT INTO logs VALUES("12","Administrator","2022-02-26 10:29:11","Update Project Details of Jmb Food Sales");
INSERT INTO logs VALUES("13","Administrator","2022-02-26 16:31:29","Delete Client Record of Carlo Talion");
INSERT INTO logs VALUES("14","Administrator","2022-02-26 16:35:40","Add New Client - Carlo Talion");
INSERT INTO logs VALUES("15","Administrator","2022-02-26 16:36:49","Add New Client - Galtero Cayetano");
INSERT INTO logs VALUES("16","Administrator","2022-02-26 16:38:00","Add New Client - Gezane Recto");
INSERT INTO logs VALUES("17","Administrator","2022-02-26 16:38:47","Add New Client - Vincent Lara");
INSERT INTO logs VALUES("18","Administrator","2022-02-27 03:43:14","Add New  Supplier - ash");
INSERT INTO logs VALUES("19","Administrator","2022-02-27 03:44:01","Add New Foundation Supplier - saghd");
INSERT INTO logs VALUES("20","Administrator","2022-02-27 03:44:59","Update Supplier Details of ash");
INSERT INTO logs VALUES("21","Administrator","2022-02-27 03:45:19","Delete Supplier Record of saghd");
INSERT INTO logs VALUES("22","Administrator","2022-02-27 03:45:23","Delete Supplier Record of ash");
INSERT INTO logs VALUES("23","Administrator","2022-02-27 03:45:53","Add New Foundation Supplier - HELLO WORLD");
INSERT INTO logs VALUES("24","Administrator","2022-02-27 03:46:08","Update Supplier Details of HELLO WORLD");
INSERT INTO logs VALUES("25","Administrator","2022-02-27 03:46:12","Delete Supplier Record of HELLO WORLD");
INSERT INTO logs VALUES("26","Administrator","2022-02-27 03:50:48","Add New Material - hello world from Rockwool Building Materials Philippines");
INSERT INTO logs VALUES("27","Administrator","2022-02-27 03:51:06","Update Material Details of hello worlds");
INSERT INTO logs VALUES("28","Administrator","2022-02-27 03:51:14","Delete Material Record of hello worlds");
INSERT INTO logs VALUES("29","Administrator","2022-02-27 13:12:01","Update Position Details For Electricians");
INSERT INTO logs VALUES("30","Administrator","2022-02-27 13:12:38","Update Position Details For Electrician");
INSERT INTO logs VALUES("31","Administrator","2022-02-27 13:26:26","Add New Admin - kjashfd asdhg");
INSERT INTO logs VALUES("32","Administrator","2022-02-27 13:36:28","Update Staff Details For kjashfd Bandola - ");
INSERT INTO logs VALUES("33","Administrator","2022-02-27 13:36:36","Update Staff Details For Patrick Ail Bandola - ");
INSERT INTO logs VALUES("34","Administrator","2022-02-27 13:39:07","Update Staff Details For Patrick Ail Bandola - ");
INSERT INTO logs VALUES("35","Administrator","2022-02-27 13:39:25","Update Staff Details For Patrick Ail Bandola - ");
INSERT INTO logs VALUES("36","Administrator","2022-02-27 13:45:00","Update Staff Details For Patrick Ail Bandola - ");
INSERT INTO logs VALUES("37","Administrator","2022-02-27 13:58:08","Delete Staff - Patrick Ail Bandola - ");
INSERT INTO logs VALUES("38","Administrator","2022-02-28 19:33:44","Update Material Details of Concrete Blocks");
INSERT INTO logs VALUES("39","Administrator","2022-02-28 19:46:31","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("40","Administrator","2022-02-28 19:47:03","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("41","Administrator","2022-02-28 19:53:32","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("42","Administrator","2022-02-28 19:53:48","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("43","Administrator","2022-02-28 19:57:13","Update Material Details of Concrete Blockssss");
INSERT INTO logs VALUES("44","Administrator","2022-02-28 19:57:37","Update Material Details of Concrete Blockssss");
INSERT INTO logs VALUES("45","Administrator","2022-02-28 20:00:01","Update Material Details of Concrete Block");
INSERT INTO logs VALUES("46","Administrator","2022-02-28 20:01:31","Update Material Details of Concrete Block");
INSERT INTO logs VALUES("47","Administrator","2022-02-28 20:02:01","Update Material Details of Concrete Block");
INSERT INTO logs VALUES("48","Administrator","2022-02-28 20:02:21","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("49","Administrator","2022-02-28 20:02:33","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("50","Administrator","2022-02-28 20:03:40","Update Material Details of Electrical Wire and Cables");
INSERT INTO logs VALUES("51","Administrator","2022-02-28 20:04:11","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("52","Administrator","2022-02-28 20:07:33","Update Material Details of Concrete Blocks");
INSERT INTO logs VALUES("53","Administrator","2022-02-28 20:07:54","Update Material Details of Electrical Wire and Cables");
INSERT INTO logs VALUES("54","Administrator","2022-02-28 20:23:18","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("55","Administrator","2022-02-28 20:23:42","Update Material Details of Concrete Block");
INSERT INTO logs VALUES("56","Administrator","2022-02-28 20:26:11","Update Material Details of Concrete Block");
INSERT INTO logs VALUES("57","Administrator","2022-02-28 20:31:53","Update Material Details of Concrete Block");
INSERT INTO logs VALUES("58","Administrator","2022-02-28 20:32:16","Update Material Details of Concrete Block");
INSERT INTO logs VALUES("59","Administrator","2022-02-28 20:33:04","Update Material Details of Concrete Block");
INSERT INTO logs VALUES("60","Administrator","2022-02-28 20:33:14","Update Material Details of Concrete Block");
INSERT INTO logs VALUES("61","Administrator","2022-02-28 20:37:01","Update Supplier Details of Happy Wood Construction Supply");
INSERT INTO logs VALUES("62","Administrator","2022-02-28 20:42:03","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("63","Administrator","2022-02-28 20:42:40","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("64","Administrator","2022-02-28 20:42:51","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("65","Administrator","2022-02-28 20:45:32","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("66","Administrator","2022-02-28 20:45:43","Update Material Details of Electrical Wire and Cable");
INSERT INTO logs VALUES("67","Administrator","2022-02-28 20:48:05","Update Supplier Details of Happy Wood Construction Supplies");
INSERT INTO logs VALUES("68","Administrator","2022-02-28 20:50:10","Update Supplier Details of Happy Wood Construction Supply");
INSERT INTO logs VALUES("69","Administrator","2022-03-03 07:34:25","Add New Foundation Supplier - Pat");
INSERT INTO logs VALUES("70","Administrator","2022-03-03 07:36:47","Update Supplier Details of Pat Hardware Store");
INSERT INTO logs VALUES("71","Administrator","2022-03-03 07:37:08","Update Supplier Details of Pat Hardware Store");
INSERT INTO logs VALUES("72","Administrator","2022-03-03 07:37:16","Delete Supplier Record of Pat Hardware Store");
INSERT INTO logs VALUES("73","Administrator","2022-03-03 09:04:19","Backuped Database");
INSERT INTO logs VALUES("74","Administrator","2022-03-09 07:31:18","Update Worker Details For Ezra Magpantay - Iron Worker");
INSERT INTO logs VALUES("75","Administrator","2022-03-09 07:31:24","Update Worker Details For Ezra Magpantay - Iron Worker");
INSERT INTO logs VALUES("76","Administrator","2022-03-11 11:33:48","Add New Project Progress Update For Jmb Food Sales - Tiles - 30%");
INSERT INTO logs VALUES("77","Administrator","2022-03-13 09:19:26","Receive Order fromHappy Wood Construction Supply - 25pc of ");
INSERT INTO logs VALUES("78","Administrator","2022-03-13 09:19:26","25pc of  added to stocks");
INSERT INTO logs VALUES("79","Administrator","2022-03-13 09:22:32","Receive Order From Happy Wood Construction Supply - 25 pc of Wood Lumber");
INSERT INTO logs VALUES("80","Administrator","2022-03-13 09:22:32","25 pc of Wood Lumber added to stocks");
INSERT INTO logs VALUES("81","Administrator","2022-03-13 09:30:03","Order 2 of Electrical Wire and Cable");
INSERT INTO logs VALUES("82","Administrator","2022-03-13 09:30:03","Order 4 of Electrical Conduit and Conduit Fitting");
INSERT INTO logs VALUES("83","Administrator","2022-03-13 09:40:02","Order 12 pc of Concrete Block from Rockwool Building Materials Philippines");
INSERT INTO logs VALUES("84","Administrator","2022-03-13 09:46:58","Order 27 pc of Concrete Block from Rockwool Building Materials Philippines");
INSERT INTO logs VALUES("85","Administrator","2022-03-13 09:56:39","Receive Order From Happy Wood Construction Supply - 25 pc of Wood Lumber");
INSERT INTO logs VALUES("86","Administrator","2022-03-13 09:56:39","25 pc of Wood Lumber added to stocks");
INSERT INTO logs VALUES("87","Administrator","2022-03-13 10:29:01","Receive Order To Cross-Link Electric and Construction Corporation -  m of ");
INSERT INTO logs VALUES("88","Administrator","2022-03-13 13:37:50","");
INSERT INTO logs VALUES("89","Administrator","2022-03-13 13:47:08","Receive Order From Cross-Link Electric and Construction Corporation - 10 m of Electrical Wire and Cable");
INSERT INTO logs VALUES("90","Administrator","2022-03-13 13:47:08","10 m of Electrical Wire and Cable added to stocks");
INSERT INTO logs VALUES("91","Administrator","2022-03-13 20:18:48","Add Full Payment - 2000 For Accounts Payable For Cross-Link Electric and Construction Corporation");
INSERT INTO logs VALUES("92","Administrator","2022-03-13 20:20:55","Add Full Payment of ₱2570.00 For Accounts Payable For Cross-Link Electric and Construction Corporation");
INSERT INTO logs VALUES("93","Administrator","2022-03-14 09:07:55","Add New Project - Bandola Compound");
INSERT INTO logs VALUES("94","Administrator","2022-03-14 09:17:25","Update Project Details of Bandola Compound");
INSERT INTO logs VALUES("95","Administrator","2022-03-14 09:17:44","Update Project Details of Bandola Compounds");
INSERT INTO logs VALUES("96","Administrator","2022-03-14 09:17:51","Delete Project Record of Bandola Compounds");
INSERT INTO logs VALUES("97","Administrator","2022-03-14 15:00:03","Update Position Details For Electrician");
INSERT INTO logs VALUES("98","Administrator","2022-03-14 15:00:29","Update Position Details For Foreman");
INSERT INTO logs VALUES("99","Administrator","2022-03-14 15:01:19","Update Position Details For Plumber");
INSERT INTO logs VALUES("100","Administrator","2022-03-14 15:01:38","Update Position Details For Construction Worker");
INSERT INTO logs VALUES("101","Administrator","2022-03-14 15:02:40","Update Position Details For Brick Mason");
INSERT INTO logs VALUES("102","Administrator","2022-03-14 15:02:49","Update Position Details For Carpenter");
INSERT INTO logs VALUES("103","Administrator","2022-03-14 15:02:54","Update Position Details For Concrete Finisher");
INSERT INTO logs VALUES("104","Administrator","2022-03-14 15:03:01","Update Position Details For Glazier");
INSERT INTO logs VALUES("105","Administrator","2022-03-14 15:03:07","Update Position Details For Flooring Installer");
INSERT INTO logs VALUES("106","Administrator","2022-03-14 15:03:13","Update Position Details For Iron Worker");
INSERT INTO logs VALUES("107","Administrator","2022-03-14 15:03:19","Update Position Details For Painter");
INSERT INTO logs VALUES("108","Administrator","2022-03-14 15:03:22","Update Position Details For Painter");
INSERT INTO logs VALUES("109","Administrator","2022-03-14 15:04:22","Update Position Details For Pipefitter");
INSERT INTO logs VALUES("110","Administrator","2022-03-14 15:04:34","Update Position Details For Roofer");
INSERT INTO logs VALUES("111","Administrator","2022-03-14 15:04:41","Update Position Details For Tile Setter");
INSERT INTO logs VALUES("112","Administrator","2022-03-14 21:28:36","Backuped Database");
INSERT INTO logs VALUES("113","Administrator","2022-03-15 09:07:07","Add New Project Requirement For Jmb Food Sales - 25 pc Wood Lumber");
INSERT INTO logs VALUES("114","Administrator","2022-03-15 09:09:07","Removed Project Requirement For Jmb Food Sales - 25 pc Wood Lumber");
INSERT INTO logs VALUES("115","Administrator","2022-03-15 09:09:36","Removed Project Worker For Jmb Food Sales - Navarro Alejo - Concrete Finisher");
INSERT INTO logs VALUES("116","Administrator","2022-03-15 09:41:19","Receive Order From Richwell Plumbing Hardware and Construction Supply - 15 pc of PVC Pipes");
INSERT INTO logs VALUES("117","Administrator","2022-03-15 09:41:19","15 pc of PVC Pipes added to stocks");
INSERT INTO logs VALUES("118","Administrator","2022-03-15 13:03:02","Order 28 pc of Concrete Block from Rockwool Building Materials Philippines");
INSERT INTO logs VALUES("119","Administrator","2022-03-16 07:48:03","Update Supplier Details of Happy Wood Construction Supply");
INSERT INTO logs VALUES("120","Administrator","2022-03-16 07:48:11","Update Supplier Details of Cross-Link Electric and Construction Corporation");
INSERT INTO logs VALUES("121","Administrator","2022-03-16 07:48:20","Update Supplier Details of Richwell Plumbing Hardware and Construction Supply");
INSERT INTO logs VALUES("122","Administrator","2022-03-16 07:48:34","Update Supplier Details of Enerzone Electrical Construction Corporation");
INSERT INTO logs VALUES("123","Administrator","2022-03-16 07:48:49","Update Supplier Details of Sheraton Plumbing and Construction Supply");
INSERT INTO logs VALUES("124","Administrator","2022-03-16 07:48:59","Update Supplier Details of Rockwool Building Materials Philippines");
INSERT INTO logs VALUES("125","Administrator","2022-03-16 08:47:57","Update Engineer Details For Carmina Galang Makisigs");
INSERT INTO logs VALUES("126","Administrator","2022-03-16 08:48:07","Update Engineer Details For Carmina Galang Makisig");
INSERT INTO logs VALUES("127","Administrator","2022-03-16 08:50:14","Update Engineer Details For Carmina Galang Makisigs");
INSERT INTO logs VALUES("128","Administrator","2022-03-16 08:50:27","Update Engineer Details For Carmina Galang Makisig");
INSERT INTO logs VALUES("129","Administrator","2022-03-16 08:51:53","Update Account Details for Kyle Aguinaldo Valdezs");
INSERT INTO logs VALUES("130","Administrator","2022-03-16 08:52:00","Update Account Details for Kyle Aguinaldo Valdez");
INSERT INTO logs VALUES("131","Administrator","2022-03-16 10:38:47","Add New Project - Cielito's Paradise");
INSERT INTO logs VALUES("132","Administrator","2022-03-16 10:39:37","Update Project Details of Cielito's Paradise");
INSERT INTO logs VALUES("133","Administrator","2022-03-16 11:43:53","Add New Project Division For Paradise Palms - Tiles");
INSERT INTO logs VALUES("134","Administrator","2022-03-16 11:44:32","Add New Project Progress Update For Paradise Palms - Tiles - 100%");
INSERT INTO logs VALUES("135","Accountant","2022-03-16 12:01:45","Order 10 pc of Wood Lumber from Happy Wood Construction Supply");
INSERT INTO logs VALUES("136","Accountant","2022-03-16 12:02:01","Order 20 pc of Concrete Block from Rockwool Building Materials Philippines");
INSERT INTO logs VALUES("137","Administrator","2022-03-16 12:13:28","Backuped Database");
INSERT INTO logs VALUES("138","Administrator","2022-03-17 15:55:39","Update Account Password");
INSERT INTO logs VALUES("139","Administrator","2022-03-18 07:28:04","Receive Order From Rockwool Building Materials Philippines - 20 pc of Concrete Block");
INSERT INTO logs VALUES("140","Administrator","2022-03-18 07:28:04","20 pc of Concrete Block added to stocks");
INSERT INTO logs VALUES("141","Administrator","2022-03-18 08:54:34","Backuped Database");
INSERT INTO logs VALUES("142","Administrator","2022-03-19 16:31:54","Add Partial Payment of ₱449.00 For Accounts Receivable From Galtero Cayetano");
INSERT INTO logs VALUES("143","Administrator","2022-03-19 16:38:03","Add Partial Payment of ₱449.00 For Accounts Receivable From Galtero Cayetano");
INSERT INTO logs VALUES("144","Administrator","2022-03-19 16:41:07","Add Partial Payment of ₱68000.00 For Accounts Receivable From Galtero Cayetano");
INSERT INTO logs VALUES("145","Administrator","2022-03-19 20:16:39","Receive Order From Sheraton Plumbing And Construction Supply - 5 m of Galvanized Iron Pipes");
INSERT INTO logs VALUES("146","Administrator","2022-03-19 20:16:39","5 m of Galvanized Iron Pipes added to stocks");
INSERT INTO logs VALUES("147","Administrator","2022-03-19 20:17:16","Add Partial Payment of ₱495.00 For Accounts Payable For Sheraton Plumbing And Construction Supply");
INSERT INTO logs VALUES("148","Administrator","2022-03-19 20:19:06","Add Partial Payment of ₱449.00 For Accounts Receivable From Galtero Cayetano");
INSERT INTO logs VALUES("149","Administrator","2022-03-19 20:42:45","Add Partial Payment of ₱864.00 For Accounts Receivable From Gezane Recto");
INSERT INTO logs VALUES("150","Administrator","2022-03-19 20:43:20","Add Full Payment of ₱66770.00 For Accounts Receivable From Galtero Cayetano");
INSERT INTO logs VALUES("151","Administrator","2022-03-20 05:57:27","Add Partial Payment of ₱449.92.00 For Accounts Receivable From Galtero Cayetano");
INSERT INTO logs VALUES("152","Administrator","2022-03-20 05:59:49","Add Full Payment of ₱68000.00 For Accounts Receivable From Galtero Cayetano");



CREATE TABLE `materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `price` decimal(13,2) NOT NULL,
  `stocks` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

INSERT INTO materials VALUES("1","2","Electrical","Cross-Link Electric and Construction Corporation","Electrical Wire and Cable","P/N B-30-1000 30AWG Tin Plated Copper Wire Wreppin","m","257.00","310","2022-02-03 18:42:39");
INSERT INTO materials VALUES("2","1","Foundation","Rockwool Building Materials Philippines","Concrete Block","Concrete Hollow Blocks (CHB), 6″","pc","18.00","520","2022-02-03 18:42:39");
INSERT INTO materials VALUES("3","2","Electrical","Enerzone Electrical Construction Corporation","Electrical Conduit and Conduit Fitting","PVC Conduit Pipe, 1/2″ diameter, 3m","m","81.00","400","2022-02-03 18:42:39");
INSERT INTO materials VALUES("4","3","Plumbing","Sheraton Plumbing And Construction Supply","Galvanized Iron Pipes","GI Pipe, 1/2″, Sch40, Seamless, 6 meters","m","899.00","205","2022-02-03 18:42:39");
INSERT INTO materials VALUES("5","1","Foundation","Happy Wood Construction Supply","Wood Lumber","2 x 3 x 10 (Mahogany)","pc","150.00","225","2022-02-03 18:42:39");
INSERT INTO materials VALUES("7","3","Plumbing","Richwell Plumbing Hardware and Construction Supply","PVC Pipes","PVC Pipe, (19mm D)","pc","111.00","415","2022-02-03 18:42:39");



CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `products` varchar(255) NOT NULL,
  `product_price` decimal(13,2) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `amount_paid` decimal(13,2) NOT NULL,
  `date_ordered` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `date_approved` datetime NOT NULL,
  `date_received` datetime NOT NULL,
  `rejection_reason` varchar(255) NOT NULL,
  `date_rejected` datetime NOT NULL,
  `return_reason` varchar(255) NOT NULL,
  `date_returned` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

INSERT INTO orders VALUES("1","#99762373","5","Wood Lumber","150.00","25","3750.00","2022-03-09 07:17:29","Returning","contrivekcs@gmail.com","19950b927cbd26eb6c37873ef6ee23fa6488","0000-00-00 00:00:00","2022-03-13 09:56:39","","0000-00-00 00:00:00","Items are not standard quality.","2022-03-13 10:29:01");
INSERT INTO orders VALUES("2","#25977345","1","Electrical Wire and Cable","257.00","10","2570.00","2022-03-09 07:17:29","On Delivery","contrivekcs@gmail.com","19950b927cbd26eb6c37873ef6ee23fa1224","0000-00-00 00:00:00","2022-03-13 13:47:08","","0000-00-00 00:00:00","","0000-00-00 00:00:00");
INSERT INTO orders VALUES("3","#51120221","7","PVC Pipes","111.00","15","1665.00","2022-03-09 07:17:29","Received","contrivekcs@gmail.com","19950b927cbd26eb6c37873ef6ee23fa81","0000-00-00 00:00:00","2022-03-15 09:41:19","","0000-00-00 00:00:00","","0000-00-00 00:00:00");
INSERT INTO orders VALUES("4","#16324623","2","Concrete Block","18.00","30","540.00","2022-03-09 07:17:29","Pending","contrivekcs@gmail.com","19950b927cbd26eb6c37873ef6ee23fa2318","0000-00-00 00:00:00","0000-00-00 00:00:00","","2022-03-16 11:27:42","","0000-00-00 00:00:00");
INSERT INTO orders VALUES("5","#27175382","3","Electrical Conduit and Conduit Fitting","81.00","20","1620.00","2022-03-09 07:17:29","Returned","contrivekcs@gmail.com","19950b927cbd26eb6c37873ef6ee23fa6574","0000-00-00 00:00:00","0000-00-00 00:00:00","","0000-00-00 00:00:00","","0000-00-00 00:00:00");
INSERT INTO orders VALUES("6","#8392498","4","Galvanized Iron Pipes","899.00","5","4495.00","2022-03-09 07:17:29","Received","contrivekcs@gmail.com","0","2022-03-15 19:25:31","2022-03-19 20:16:39","","0000-00-00 00:00:00","","0000-00-00 00:00:00");
INSERT INTO orders VALUES("13","#47135845","2","Concrete Block","18.00","28","504.00","2022-03-15 13:03:02","Rejected","contrivekcs@gmail.com","0","0000-00-00 00:00:00","0000-00-00 00:00:00","Out of Product Stocks","2022-03-15 19:27:52","","0000-00-00 00:00:00");
INSERT INTO orders VALUES("14","#98635575","5","Wood Lumber","150.00","10","1500.00","2022-03-16 12:01:45","Pending","contrivekcs@gmail.com","19950b927cbd26eb6c37873ef6ee23fa2528","0000-00-00 00:00:00","0000-00-00 00:00:00","","0000-00-00 00:00:00","","0000-00-00 00:00:00");
INSERT INTO orders VALUES("15","#54971183","2","Concrete Block","18.00","20","360.00","2022-03-16 12:02:01","Received","contrivekcs@gmail.com","0","2022-03-16 12:03:43","2022-03-18 07:28:04","","0000-00-00 00:00:00","","0000-00-00 00:00:00");



CREATE TABLE `payables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payable_id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `products` varchar(255) NOT NULL,
  `product_price` decimal(13,2) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `amount_paid` decimal(13,2) NOT NULL,
  `total_amount` decimal(13,2) NOT NULL,
  `date_ordered` datetime NOT NULL,
  `date_received` datetime NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_paid` datetime NOT NULL,
  `user_id` varchar(255) NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

INSERT INTO payables VALUES("1","#967871","#25977345","1","Electrical Wire and Cable","257.00","10","2570.00","0.00","2022-03-09 07:17:29","2022-03-13 13:47:08","Paid","2022-03-13 20:20:54","1");
INSERT INTO payables VALUES("3","#167326","#54971183","2","Concrete Block","18.00","20","360.00","360.00","2022-03-16 12:02:01","2022-03-18 07:28:04","Unpaid","0000-00-00 00:00:00","1");
INSERT INTO payables VALUES("4","#110506","#8392498","4","Galvanized Iron Pipes","899.00","5","4495.00","4000.00","2022-03-09 07:17:29","2022-03-19 20:16:39","Partial","0000-00-00 00:00:00","1");



CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `receivables_id` varchar(255) NOT NULL,
  `price` double(13,2) NOT NULL,
  `remaining_amount` decimal(13,2) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

INSERT INTO payments VALUES("1","1","449.92","68000.00","Partial","2022-03-20 05:57:27");
INSERT INTO payments VALUES("2","1","68000.00","0.00","Paid","2022-03-20 16:48:42");



CREATE TABLE `positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(255) NOT NULL,
  `rate` decimal(13,2) NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

INSERT INTO positions VALUES("1","Construction Worker","86.00","2021-12-02");
INSERT INTO positions VALUES("2","Flooring Installer","86.00","2021-12-02");
INSERT INTO positions VALUES("3","Glazier","86.00","2021-12-02");
INSERT INTO positions VALUES("4","Tile Setter","86.00","2021-12-02");
INSERT INTO positions VALUES("5","Brick Mason","86.00","2021-12-02");
INSERT INTO positions VALUES("6","Roofer","86.00","2021-12-02");
INSERT INTO positions VALUES("7","Concrete Finisher","86.00","2021-12-02");
INSERT INTO positions VALUES("8","Iron Worker","86.00","2021-12-02");
INSERT INTO positions VALUES("9","Plumber","90.00","2021-12-02");
INSERT INTO positions VALUES("10","Carpenter","86.00","2021-12-02");
INSERT INTO positions VALUES("11","Painter","86.00","2021-12-02");
INSERT INTO positions VALUES("12","Electrician","90.00","2021-12-02");
INSERT INTO positions VALUES("13","Pipefitter","86.00","2021-12-02");
INSERT INTO positions VALUES("14","Foreman","94.00","2021-12-02");



CREATE TABLE `progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `division_name` varchar(255) NOT NULL,
  `progress` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

INSERT INTO progress VALUES("1","3","Paradise Palms","Floor","100","2022-01-31 10:31:06","2022-01-31 10:31:06");
INSERT INTO progress VALUES("2","3","Paradise Palms","Windows","100","2022-01-31 10:31:13","2022-01-31 10:31:13");
INSERT INTO progress VALUES("3","3","Paradise Palms","Roof","100","2022-01-31 10:31:18","2022-01-31 10:31:18");
INSERT INTO progress VALUES("4","1","Jmb Food Sales","Tiles","30","2022-01-31 10:50:11","2022-01-31 10:50:11");
INSERT INTO progress VALUES("5","1","Jmb Food Sales","Door","65","2022-01-31 10:50:16","2022-01-31 10:50:16");
INSERT INTO progress VALUES("6","1","Jmb Food Sales","Walls","35","2022-01-31 10:50:19","2022-01-31 10:50:19");
INSERT INTO progress VALUES("7","3","Paradise Palms","Tiles","100","2022-03-16 11:43:53","2022-03-16 11:43:53");



CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `project_description` varchar(255) NOT NULL,
  `engineer_id` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `receivable_status` varchar(50) NOT NULL,
  `date_received` datetime NOT NULL DEFAULT current_timestamp(),
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;

INSERT INTO projects VALUES("1","Industrial Construction","Jmb Food Sales","Food Factory in Cavite","3","Cavite","Galtero Cayetano","2020-11-22","2021-12-23","On Hold","Pending","2021-12-03 13:41:23","2019-10-09");
INSERT INTO projects VALUES("2","Residential Construction","Lakeside View","Subdivision Area in Nueva Ecija","3","Nueva Ecija","Carlo Talion","2020-09-06","2022-01-08","Cancelled","Pending","0000-00-00 00:00:00","2020-07-24");
INSERT INTO projects VALUES("3","Residential Construction","Paradise Palms","Subdivision Area in Pampanga","3","Pampanga","Gezane Recto","2020-12-17","2021-05-13","Finished","Pending","2021-11-02 18:38:59","2021-08-25");
INSERT INTO projects VALUES("4","Commercial Construction","The Royal Bistro","Coffee and Cake Shop in Metro Manila","3","Metro Manila","Vincent Lara","2020-09-06","2022-10-23","Started","Pending","2021-11-02 11:44:31","2021-07-18");



CREATE TABLE `receivables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `total_invoice` decimal(13,2) NOT NULL,
  `total_remaining` decimal(13,2) NOT NULL,
  `receivable_status` varchar(255) NOT NULL,
  `receivable_date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `receivable_date_received` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

INSERT INTO receivables VALUES("1","1","Galtero Cayetano","68449.92","0.00","Paid","2022-03-20 05:54:45","2022-03-20 05:59:49");
INSERT INTO receivables VALUES("2","3","Gezane Recto","240544.64","240544.64","Unpaid","2022-03-20 05:59:03","0000-00-00 00:00:00");



CREATE TABLE `requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(255) NOT NULL,
  `project` varchar(255) NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `material_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `price` decimal(13,2) NOT NULL,
  `total` decimal(13,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

INSERT INTO requirements VALUES("7","4","The Royal Bistro","3","Plumbing","7","PVC Pipes","PVC Pipe, (19mm D)","pc","100","111.00","11100.00");
INSERT INTO requirements VALUES("8","4","The Royal Bistro","2","Electrical","3","Electrical Conduit and Conduit Fitting","PVC Conduit Pipe, 1/2″ diameter, 3m","m","100","81.00","8100.00");
INSERT INTO requirements VALUES("9","4","The Royal Bistro","3","Plumbing","4","Galvanized Iron Pipes","GI Pipe, 1/2″, Sch40, Seamless, 6 meters","m","100","899.00","89900.00");
INSERT INTO requirements VALUES("23","3","Paradise Palms","3","Plumbing","4","Galvanized Iron Pipes","GI Pipe, 1/2″, Sch40, Seamless, 6 meters","m","200","899.00","89900.00");
INSERT INTO requirements VALUES("24","3","Paradise Palms","1","Foundation","5","Wood Lumber","2 x 3 x 10 (Mahogany)","pc","200","150.00","15000.00");
INSERT INTO requirements VALUES("25","1","Jmb Food Sales","1","Foundation","2","Concrete Block","Concrete Hollow Blocks (CHB), 6″","pc","300","18.00","5400.00");
INSERT INTO requirements VALUES("26","1","Jmb Food Sales","2","Electrical","1","Electrical Wire and Cable","P/N B-30-1000 30AWG Tin Plated Copper Wire Wreppin","m","200","257.00","51400.00");



CREATE TABLE `staffs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `age` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `civil_status` varchar(255) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access` varchar(255) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `date_added` date NOT NULL,
  `last_login` datetime NOT NULL,
  `last_activity` datetime NOT NULL,
  `last_logout` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

INSERT INTO staffs VALUES("1","STF-750","Kyle Aguinaldo Valdez","1990-01-16","32","Male","Valley View Village, Munting Dilaw, Antipolo City, Rizal","09154735194","Single","mehrad-vosoughi-iUQmEFtfdLw-unsplash.jpg","Active","pbandola06@gmail.com","Administrator","e64b78fc3bc91bcbc7dc232ba8ec59e0","Admin","0","2020-04-01","2022-03-20 17:29:31","2022-03-20 17:29:31","2022-03-20 17:28:47");
INSERT INTO staffs VALUES("3","STF-158","Carmina Galang Makisig","1996-01-19","26","Female","Cubao, Quezon City","09638136868","Single","clayton-mpDV4xaFP8c-unsplash.jpg","Active","pbandola06@gmail.com","Engineer","d6f7cd0239ea1b713b76ac957d459000","Engineer","0","2021-11-21","2022-03-20 16:03:24","2022-03-20 16:03:24","2022-03-20 16:15:46");
INSERT INTO staffs VALUES("8","STF-175","Ella Camara Manahan","1982-08-29","39","Female","1858 Oroquieta Street, Santa Cruz, Manila","09027412369","Married","clayton-mpDV4xaFP8c-unsplash.jpg","Active","pbandola@gmail.com","Accountant","33b7a3ff340fae33c3f9a4b8199cbb29","Accountant","0","2022-02-24","2022-03-20 15:48:41","2022-03-20 15:48:41","2022-03-20 16:03:06");



CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `person` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

INSERT INTO suppliers VALUES("1","1","Foundation","Happy Wood Construction Supply","Alonzo Dimaanos","09815473810","contrivekcs@gmail.com","Muntinlupa, Metro Manila","Active","2019-06-14 06:51:14");
INSERT INTO suppliers VALUES("2","2","Electrical","Cross-Link Electric and Construction Corporation","Shane Manahan","09815473810","contrivekcs@gmail.com","Parañaque, Metro Manila","Active","2019-09-11 06:57:11");
INSERT INTO suppliers VALUES("3","3","Plumbing","Richwell Plumbing Hardware and Construction Supply","Cristos Caballero","09815473810","contrivekcs@gmail.com","Manila","Active","2020-07-28 07:05:06");
INSERT INTO suppliers VALUES("4","1","Foundation","Rockwool Building Materials Philippines","Warren Europa","09815473810","contrivekcs@gmail.com","Muntinlupa, Metro Manila","Active","2020-12-20 07:05:10");
INSERT INTO suppliers VALUES("5","2","Electrical","Enerzone Electrical Construction Corporation","Jaxon Mangahas","09815473810","contrivekcs@gmail.com","Quezon City","Active","2021-04-18 07:05:15");
INSERT INTO suppliers VALUES("6","3","Plumbing","Sheraton Plumbing and Construction Supply","Ian Solas","09815473810","contrivekcs@gmail.com","Manila","Active","2021-06-06 20:56:37");



CREATE TABLE `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(255) NOT NULL,
  `project` varchar(255) NOT NULL,
  `position_id` varchar(255) NOT NULL,
  `member_id` varchar(255) NOT NULL,
  `working_days` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

INSERT INTO teams VALUES("1","1","Jmb Food Sales","14","4","396");
INSERT INTO teams VALUES("2","1","Jmb Food Sales","5","24","396");
INSERT INTO teams VALUES("3","1","Jmb Food Sales","10","28","396");
INSERT INTO teams VALUES("6","3","Paradise Palms","12","2","147");
INSERT INTO teams VALUES("7","3","Paradise Palms","2","21","147");
INSERT INTO teams VALUES("8","3","Paradise Palms","3","22","147");
INSERT INTO teams VALUES("9","3","Paradise Palms","8","26","147");
INSERT INTO teams VALUES("10","3","Paradise Palms","11","29","147");
INSERT INTO teams VALUES("11","4","The Royal Bistro","13","30","777");
INSERT INTO teams VALUES("12","4","The Royal Bistro","9","27","777");
INSERT INTO teams VALUES("16","4","The Royal Bistro","6","25","777");
INSERT INTO teams VALUES("18","1","Jmb Food Sales","1","1","396");
INSERT INTO teams VALUES("19","4","The Royal Bistro","4","23","777");



CREATE TABLE `updates` (
  `update_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `division_id` varchar(255) NOT NULL,
  `division_name` varchar(255) NOT NULL,
  `progress` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `date_posted` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`update_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

INSERT INTO updates VALUES("1","3","Paradise Palms","1","Floor","100","Lorem, ipsum dolor sit amet consectetur adipisicing elit. Unde quisquam dolore dolor? Veritatis quod maiores pariatur magnam culpa beatae. Ex?","3","2022-01-31 10:31:47");
INSERT INTO updates VALUES("2","3","Paradise Palms","2","Windows","100","Lorem ipsum dolor sit amet consectetur, adipisicing elit. Libero, perferendis ipsam. Sunt ut cumque odio nulla, totam beatae consectetur earum iure sit!","3","2022-01-31 10:35:27");
INSERT INTO updates VALUES("3","3","Paradise Palms","3","Roof","100","Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam nulla fugiat inventore tempore eius numquam placeat asperiores. Repellat qui maiores aut officiis nesciunt?","3","2022-01-31 10:40:27");
INSERT INTO updates VALUES("4","1","Jmb Food Sales","4","Tiles","10","Lorem ipsum, dolor sit amet consectetur adipisicing elit. At neque exercitationem quibusdam quidem aliquid, magnam quos delectus, voluptatibus ea blanditiis inventore. Quam, quis! Reprehenderit.","3","2022-01-31 10:50:52");
INSERT INTO updates VALUES("5","1","Jmb Food Sales","5","Door","65","Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum odit laboriosam sint harum nemo corrupti sit! Labore saepe odit veniam quaerat ullam libero reprehenderit sequi.","3","2022-01-31 10:52:05");
INSERT INTO updates VALUES("6","1","Jmb Food Sales","6","Walls","35","Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolorem reprehenderit praesentium possimus necessitatibus temporibus illum? Architecto voluptate voluptatum repellendus quae unde, ipsa ut nemo modi consequatur.","3","2022-01-31 10:53:18");
INSERT INTO updates VALUES("7","1","Jmb Food Sales","4","Tiles","18","Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat sunt voluptatum impedit tenetur odit dolor consequatur nam ut, asperiores mollitia magni iste magnam quo unde vero quam, vel rem molestias porro.","3","2022-02-02 12:36:33");
INSERT INTO updates VALUES("8","1","Jmb Food Sales","4","Tiles","28","Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut voluptatum, dignissimos aperiam sed odit eos et, exercitationem ipsam deleniti suscipit, minus quod consequuntur ullam ea. Temporibus consequatur fugit ipsam consequuntur, expedita repudiandae pe","3","2022-02-23 15:56:05");
INSERT INTO updates VALUES("9","1","Jmb Food Sales","4","Tiles","30","Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus accusamus quis earum quia architecto doloremque unde voluptas nulla nesciunt itaque. Rem aliquid unde nisi, quidem aspernatur hic mollitia iure voluptates necessitatibus eos reiciendis","3","2022-03-11 11:33:48");
INSERT INTO updates VALUES("10","3","Paradise Palms","7","Tiles","100","Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique dolor reprehenderit, fugiat harum ad obcaecati commodi velit repellendus, sed perspiciatis ex numquam ipsa et quis quia doloremque vitae animi alias!","3","2022-03-16 11:44:32");



CREATE TABLE `workers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `age` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `position_id` varchar(255) NOT NULL,
  `position` varchar(50) NOT NULL,
  `rate` decimal(13,2) NOT NULL,
  `hours_per_day` varchar(255) NOT NULL,
  `profile` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `assigned` varchar(255) NOT NULL,
  `date_added` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;

INSERT INTO workers VALUES("1","EMP-945","Alcaraz","Frisco ","Santos","1985-11-10","36","Male","Dr. A. Santos Ave. Paranaque City","09632825382","Married","1","Construction Worker","86.00","8","construction worker.jpg","Active","Yes","2019-01-08");
INSERT INTO workers VALUES("2","EMP-227","Murcia","Jerome","Magno","1993-08-30","28","Male","National Highway, Balibago, Santa Rosa City, Laguna","09498376477","Single","12","Electrician","90.00","8","construction worker.jpg","Active","Yes","2019-04-04");
INSERT INTO workers VALUES("3","EMP-583","Alejo","Navarro","Dantes","1987-10-19","34","Male"," McArthur Highway corner Ligtasan Street, Tarlac City","09456112017","Married","7","Concrete Finisher","86.00","8","construction worker.jpg","Active","No","2019-09-20");
INSERT INTO workers VALUES("4","EMP-830","Sarte","Gaspar","Villosillo","1981-10-11","40","Male","280 G. Araneta Avenue, Quezon City","09632363260","Divorced","14","Foreman","94.00","8","construction worker.jpg","Active","Yes","2019-03-05");
INSERT INTO workers VALUES("21","EMP-739","Dacanay","Christian","Patacsil","1990-03-08","31","Male","National Road, Calasiao","09755173939","Married","2","Flooring Installer","86.00","8","construction worker.jpg","Active","Yes","2019-06-06");
INSERT INTO workers VALUES("22","EMP-970","Abella","Edwardo","Everardo","1995-09-12","26","Male","2308-C Lt Sy Compound Taft Avenue, Pasay City","09025520271","Single","3","Glazier","86.00","8","construction worker.jpg","Active","Yes","2020-12-13");
INSERT INTO workers VALUES("23","EMP-866","Lorete","Steven","Ampatuan","1978-04-10","43","Male","222 Violago Compound, E. Rodriguez, Quezon City ","09024149534","Divorced","4","Tile Setter","86.00","8","construction worker.jpg","Active","Yes","2020-02-17");
INSERT INTO workers VALUES("24","EMP-818","Ladera","Arthur","Declan","1965-11-11","56","Male","South Drive Baguio, Benguet","09744447122","Separated","5","Brick Mason","86.00","8","construction worker.jpg","Active","Yes","2020-08-20");
INSERT INTO workers VALUES("25","EMP-880","Macalinao","Peter","Cuanco","1985-10-12","36","Male","Zapote Road, Sycamore Annex, Las Pinas","09028071413","Married","6","Roofer","86.00","8","construction worker.jpg","Active","Yes","2020-04-19");
INSERT INTO workers VALUES("26","EMP-123","Magpantay","Ezra","Jaron","1973-11-15","48","Male","139 Mother Ignacia Avenue, South Triangle, Quezon City","09099201020","Divorced","8","Iron Worker","86.00","8","construction worker.jpg","Active","Yes","2020-05-12");
INSERT INTO workers VALUES("27","EMP-889","Frisco","Fred","Daculug","1988-02-26","33","Male","Ramagi Building, 1081 Pedro Gil Street, Paco, Manila","09025261955","Married","9","Plumber","90.00","8","construction worker.jpg","Active","Yes","2021-03-26");
INSERT INTO workers VALUES("28","EMP-951","Alfonso","Drake","Madid","1991-04-27","30","Male","434 Plateria Street, Quiapo, Manila","09077332963","Married","10","Carpenter","86.00","8","construction worker.jpg","Active","Yes","2021-01-29");
INSERT INTO workers VALUES("29","EMP-526","Torrealba","Elmer","Holden","1993-07-01","28","Male","Citibank Tower, 8741 Paseo De Roxas Street, Makati City","09028195177","Single","11","Painter","86.00","8","construction worker.jpg","Active","Yes","2021-03-09");
INSERT INTO workers VALUES("30","EMP-675","Quaimbao","Rodney","Asuncion","1985-12-07","35","Male","Baclayon, Bohol","09055409531","Married","13","Pipefitter","86.00","8","construction worker.jpg","Active","Yes","2021-12-06");

