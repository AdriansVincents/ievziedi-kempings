-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 06:00 PM
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
-- Database: `ievziedi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `route_id` int(11) DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_price` decimal(10,2) NOT NULL,
  `prepayment_amount` decimal(10,2) DEFAULT NULL,
  `remaining_amount` decimal(10,2) DEFAULT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_products`
--

CREATE TABLE `booking_products` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(1) NOT NULL,
  `product_price_at_booking` decimal(10,2) NOT NULL,
  `product_name_snapshot` varchar(255) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
(1, 'Atpūta uz ūdens', NULL),
(2, 'Atpūta uz sauszemes', NULL),
(3, 'SUP dēļi', 1),
(4, 'Laivas', 1),
(5, 'Mājiņas', 2),
(6, 'Pirts', 2),
(7, 'Kubuls', 2),
(8, 'Velosipēdi', 2);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prepayments`
--

CREATE TABLE `prepayments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` longtext DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `price_per_day` decimal(10,2) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL,
  `available_units` int(11) NOT NULL DEFAULT 1,
  `capacity` varchar(100) DEFAULT NULL,
  `facilities` text DEFAULT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `description`, `short_description`, `price_per_day`, `main_image`, `gallery_images`, `available_units`, `capacity`, `facilities`, `status_id`) VALUES
(1, 'Mājiņa \"Ievziedi\"', 5, 'Mājīga pusotra stāva kempinga mājiņa, kas piemērota ģimenei ar diviem pieaugušajiem un diviem bērniem. Papildus iespējama nakšņošana uz saliekamās gultas.\r\n\r\nPirmajā stāvā atrodas plaša atpūtas telpa, pilnībā aprīkota virtuve ar ledusskapi, plītiņu un traukiem ēdiena pagatavošanai, kā arī WC. Otrajā stāvā ierīkota ērtā atpūtas zona ar guļvietām, kas piemērota mierīgam naktsmieram vai nesteidzīgai atpūtai pēc dienas aktivitātēm.\r\n\r\nPie mājiņas ir terase ar saulessargiem un atpūtas krēsliem, ideāla vieta, kur baudīt rīta kafiju, lasīt grāmatu vai vienkārši relaksēties dabas tuvumā.\r\n\r\nMājiņa ir piemērota gan īslaicīgai atpūtai, gan garākām brīvdienām dabas ielokā – viss, kas nepieciešams ērtam un patīkamam ģimenes atpūtai.', 'Ģimenes kempinga mājiņa ar terasi. Mājīga pusotra stāva kempinga mājiņa, kas piemērota ģimenei ar diviem pieaugušajiem un diviem bērniem.', 150.00, 'images/Majinas/maja1.jpg', 'images/Majinas/maja2.jpg', 2, '4 personas', 'Virtuve, duša, tualete, terase, ledusskapis, elektrība.', 1),
(2, 'Pirts', 6, 'Mūsu koka pirts piedāvā autentisku un relaksējošu pieredzi dabas ielokā. \r\nTā atrodas pie ezera, tikai dažu soļu attālumā no galvenās kempinga zonas, \r\nun ir ideāla vieta, lai atbrīvotos no ikdienas spriedzes un baudītu mieru.\r\n\r\nPirts ir apsildāma ar tradicionālu malkas krāsni, kas rada patīkamu aromātu un siltumu. \r\nIekšpusē pieejama plaša atpūtas telpa, ģērbtuve un duša. \r\nĀrpusē – terase ar skatu uz ezeru, kur pēc pirts var veldzēties vai iemalkot tēju.\r\n\r\nPirts ir piemērota gan nelielām kompānijām, gan romantiskam vakaram divatā. \r\nPieejama visu gadu, arī ziemā, un pēc vēlēšanās iespējams pasūtīt arī kubulu vai nakšņošanu kempinga mājiņā.', 'Koka pirts ar skatu uz ezeru, pieejama visu gadu.', 100.00, 'images/pirts/pirts1.jpg', 'images/pirts/pirts2.jpg', 1, '6 personas', 'Pirts krāsns, atpūtas telpa, duša, tualete, terase.', 2),
(3, 'Kubls', 7, 'Kubuls ir lieliska iespēja atslābināties un izbaudīt siltu ūdeni brīvā dabā. \r\nTas atrodas gleznainā vietā pie Gaujas, kur apkārt valda miers un dabas skaņas. \r\nKubuls ir piemērots gan romantiskam vakaram divatā, gan jautram draugu vakaram. \r\n\r\nŪdens tiek uzsildīts ar malkas krāsni, kas rada patīkamu siltumu arī vēsākos vakaros. \r\nKubuls aprīkots ar burbuļsistēmu un LED apgaismojumu, lai radītu relaksējošu atmosfēru. \r\nLietošana iespējama visu gadu — arī ziemā, kad īpaši baudāms ir kontrasts starp aukstu gaisu un siltu ūdeni.\r\n', 'Āra kubuls ar burbuļiem, iespējams izmantot kopā ar pirti.', 80.00, 'images/kubls/kubls1.jpg', NULL, 1, '4–6 personas', 'Burbuļsistēma, LED apgaismojums, karsts ūdens.', 2),
(4, 'SUP dēlis', 3, 'SUP dēļi ir ideāla izvēle tiem, kas vēlas apvienot mierīgu atpūtu ar vieglu fizisku aktivitāti. Nesteidzīgi slīdot pa Gaujas virsmu, iespējams baudīt klusumu, dabas skaņas un gleznainos krastus no pavisam cita skatpunkta. Lieliska nodarbe gan iesācējiem, gan pieredzējušiem supotājiem!\r\n\r\n', 'Pieejami 10 SUP dēļi. Cena atkarīga no izvēlētā maršruta.', NULL, 'images/Sup/supi1.jpg', NULL, 10, '1 persona', 'Airis, drošības veste.', 1),
(5, 'Laiva', 4, 'Ērtas un stabilas trīsvietīgās kanoe laivas ir lieliski piemērotas mierīgai atpūtai vai vairāku dienu braucieniem pa Gauju. Tās nodrošina pietiekami daudz vietas diviem pieaugušajiem un bērnam vai draugu kompānijai, kā arī vietu somām.\r\n\r\nVieglas un vadāmas, šīs laivas ļauj nesteidzīgi izbaudīt Gaujas Nacionālā parka skaistumu, klausoties upes čalās, putnu dziesmās un sajūtot dabas mieru visapkārt.', '3-vietīga laiva ar airiem. Cena atkarīga no maršruta.', NULL, 'images/laivas/laiva1.png', 'images/laivas/laiva2.png', 10, '3 personas', 'Airi, vestes.', 1),
(6, 'Velosipēds', 8, 'Izbaudi Siguldas novada skaistumu un tuvējās dabas takas ar mūsu velosipēdu nomu! Piedāvājumā dažādi velosipēdi gan pieaugušajiem, gan bērniem, kā arī bērnu sēdeklīši un aizsargķiveres drošam un ērtam braucienam.\r\n\r\nAr velosipēdu iespējams doties īsos izbraucienos pa apkārtni vai garākos maršrutos gar Gauju, uz Turaidas pili, Siguldas panorāmas skatu vietām un citām iecienītām vietām.\r\n\r\nVelobrauciens ir lieliska iespēja apvienot aktīvu atpūtu ar dabas baudīšanu, atklājot apkārtni savā tempā. ', 'Dažādi velosipēdi gan pieaugušajiem, gan bērniem, kā arī bērnu sēdeklīši, un aizsargķiveres drošam un ērtam braucienam.', 20.00, 'images/riteni/ritenis2.jpg', 'images/riteni/ritenis1.jpg,images/riteni/ritenis3.jpg', 6, '1 persona', 'Ķivere, bērnu sēdeklis pēc pieprasījuma.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `from_location` varchar(100) NOT NULL,
  `to_location` varchar(100) NOT NULL,
  `distance_km` int(11) NOT NULL,
  `duration_min_day` int(11) NOT NULL,
  `duration_max_day` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `product_id`, `from_location`, `to_location`, `distance_km`, `duration_min_day`, `duration_max_day`, `price`) VALUES
(1, 5, 'Sigulda', '\"Ievziedi\"', 17, 1, 1, 45.00),
(2, 5, 'Līgatne', '\"Ievziedi\"', 40, 1, 2, 50.00),
(3, 5, 'Līgatne', 'Sigulda', 23, 1, 1, 50.00),
(4, 5, 'Cēsis', '\"Ievziedi\"', 57, 2, 2, 65.00),
(5, 5, 'Cēsis', 'Sigulda', 40, 1, 2, 65.00),
(6, 5, 'Valmiera', '\"Ievziedi\"', 102, 2, 3, 80.00),
(7, 5, 'Valmiera', 'Sigulda', 85, 2, 2, 80.00),
(8, 4, 'Sigulda', '\"Ievziedi\"', 17, 1, 1, 30.00),
(9, 4, 'Līgatne', '\"Ievziedi\"', 40, 1, 2, 35.00),
(10, 4, 'Līgatne', 'Sigulda', 23, 1, 1, 30.00),
(11, 4, 'Cēsis', 'Sigulda', 40, 1, 2, 35.00);

-- --------------------------------------------------------

--
-- Table structure for table `season_prices`
--

CREATE TABLE `season_prices` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `season_prices`
--

INSERT INTO `season_prices` (`id`, `product_id`, `start_date`, `end_date`, `price`) VALUES
(1, 1, '2026-04-01', '2026-09-30', 150.00),
(2, 1, '2026-09-30', '2027-04-01', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `status_type` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`, `description`, `status_type`) VALUES
(1, 'pieejams', 'Produkts vai statuss ir pieejams', 2),
(2, 'nav pieejams', 'Produkts šobrīd nav pieejams', 2),
(3, 'apkopē', 'Produkts ir remontā vai apkopē', 2),
(4, 'rezervēts', 'Rezervācija veikta', 1),
(5, 'atcelts', 'Rezervācija atcelta', 1),
(6, 'pabeigts', 'Rezervācija pabeigta', 1),
(7, 'aktīvs', 'Klienta konts vai statuss ir aktīvs', 4),
(8, 'neaktīvs', 'Klienta konts nav aktīvs', 4),
(9, 'gaida apstiprinājumu', 'Priekšapmaksa gaida apstiprinājumu', 3),
(10, 'apmaksāts', 'Priekšapmaksa veikta un apstiprināta', 3),
(11, 'gaida', 'Klients pievienots gaidīšanas sarakstam', 5),
(12, 'paziņots', 'Klientam ir nosūtīts paziņojums, ka produkts ir pieejams', 5),
(13, 'atteicies', 'Klients atteicies no gaidīšanas', 5);

-- --------------------------------------------------------

--
-- Table structure for table `waitlist`
--

CREATE TABLE `waitlist` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `desired_start` datetime DEFAULT NULL,
  `desired_end` datetime DEFAULT NULL,
  `notified` tinyint(1) DEFAULT 0,
  `status_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `fk_bookings_routes` (`route_id`);

--
-- Indexes for table `booking_products`
--
ALTER TABLE `booking_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `booking_id_2` (`booking_id`,`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `prepayments`
--
ALTER TABLE `prepayments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `season_prices`
--
ALTER TABLE `season_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `waitlist`
--
ALTER TABLE `waitlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `product_id` (`product_id`,`email`),
  ADD KEY `status_id` (`status_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_products`
--
ALTER TABLE `booking_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prepayments`
--
ALTER TABLE `prepayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `season_prices`
--
ALTER TABLE `season_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `waitlist`
--
ALTER TABLE `waitlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `fk_bookings_routes` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_products`
--
ALTER TABLE `booking_products`
  ADD CONSTRAINT `booking_products_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `prepayments`
--
ALTER TABLE `prepayments`
  ADD CONSTRAINT `prepayments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prepayments_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `season_prices`
--
ALTER TABLE `season_prices`
  ADD CONSTRAINT `season_prices_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `waitlist`
--
ALTER TABLE `waitlist`
  ADD CONSTRAINT `waitlist_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `waitlist_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `waitlist_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
