-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 172.17.0.1:3306
-- Generation Time: Nov 24, 2024 at 01:30 AM
-- Server version: 10.5.26-MariaDB-ubu2004-log
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iis_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `fotka_zvirete`
--

CREATE TABLE `fotka_zvirete` (
  `id` int(11) NOT NULL,
  `zvire_id` int(11) NOT NULL,
  `priorita` int(11) NOT NULL,
  `url_velka` varchar(256) NOT NULL,
  `url_stredni` varchar(256) NOT NULL,
  `url_mala` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nalezeni`
--

CREATE TABLE `nalezeni` (
  `id` int(11) NOT NULL,
  `jmeno_nalezce` varchar(150) NOT NULL,
  `kontakt_na_nalezce` varchar(150) NOT NULL,
  `misto_nalezeni` varchar(150) NOT NULL,
  `cas` datetime NOT NULL,
  `zvire_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pozadavek_na_prohlidku`
--

CREATE TABLE `pozadavek_na_prohlidku` (
  `id` int(11) NOT NULL,
  `cas` datetime NOT NULL DEFAULT current_timestamp(),
  `zamereni` text NOT NULL,
  `osetrovatel_id` int(11) DEFAULT NULL,
  `zvire_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodej`
--

CREATE TABLE `prodej` (
  `id` int(11) NOT NULL,
  `jmeno_zakaznika` varchar(150) NOT NULL,
  `telefon_zakaznika` varchar(150) NOT NULL,
  `cena` int(11) NOT NULL,
  `cas` datetime NOT NULL,
  `zvire_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prohlidka`
--

CREATE TABLE `prohlidka` (
  `id` int(11) NOT NULL,
  `cas` datetime NOT NULL DEFAULT current_timestamp(),
  `zdravotni_stav` varchar(250) DEFAULT NULL,
  `pozadavek_id` int(11) DEFAULT NULL,
  `vakcina` varchar(100) DEFAULT NULL,
  `vyska` varchar(20) DEFAULT NULL,
  `delka` varchar(20) DEFAULT NULL,
  `hmotnost` varchar(20) DEFAULT NULL,
  `zvire_id` int(11) NOT NULL,
  `zverolekar_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rezervace`
--

CREATE TABLE `rezervace` (
  `id` int(11) NOT NULL,
  `cas_zacatku` datetime NOT NULL,
  `cas_konce` datetime NOT NULL,
  `schvalena` tinyint(1) NOT NULL,
  `zvire_zapujceno` tinyint(1) NOT NULL,
  `zvire_vraceno` tinyint(1) NOT NULL,
  `osetrovatel_id` int(11) DEFAULT NULL,
  `klient_id` int(11) DEFAULT NULL,
  `zvire_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `jmeno` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `jmeno`) VALUES
(1, 'Administrátor'),
(2, 'Pečovatel'),
(3, 'Veterinář'),
(4, 'Dobrovolník');

-- --------------------------------------------------------

--
-- Table structure for table `umrti`
--

CREATE TABLE `umrti` (
  `id` int(11) NOT NULL,
  `pricina` varchar(250) NOT NULL,
  `cas` datetime NOT NULL,
  `zvire_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uzivatel`
--

CREATE TABLE `uzivatel` (
  `id` int(11) NOT NULL,
  `jmeno` varchar(128) NOT NULL,
  `prijmeni` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `heslo` varchar(64) NOT NULL,
  `overen_kdy` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Creating default admin user (email: admin, password: admin)
--

INSERT INTO `uzivatel` (`id`, `jmeno`, `prijmeni`, `email`, `heslo`, `overen_kdy`) VALUES
(1, 'admin', 'admin', 'admin', '$2y$10$a09orptjNBBypsIJtvUBnOQrZuIydM1f9M6vQTFrDmDbsRD2tU2K2', '2024-11-24 03:10:03');

-- --------------------------------------------------------

--
-- Table structure for table `uzivatel_ma_role`
--

CREATE TABLE `uzivatel_ma_role` (
  `id` int(11) NOT NULL,
  `uzivatel_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zvire`
--

CREATE TABLE `zvire` (
  `id` int(11) NOT NULL,
  `jmeno` varchar(128) NOT NULL,
  `zivocisny_druh` varchar(128) NOT NULL,
  `plemeno` varchar(128) NOT NULL,
  `pohlavi` varchar(64) NOT NULL,
  `datum_narozeni` datetime NOT NULL,
  `popis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zvire_je_volne`
--

CREATE TABLE `zvire_je_volne` (
  `id` int(11) NOT NULL,
  `cas_zacatku` datetime NOT NULL,
  `cas_konce` datetime NOT NULL,
  `zvire_id` int(11) NOT NULL,
  `osetrovatel_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fotka_zvirete`
--
ALTER TABLE `fotka_zvirete`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zvire_id` (`zvire_id`);

--
-- Indexes for table `nalezeni`
--
ALTER TABLE `nalezeni`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pozadavek_na_prohlidku`
--
ALTER TABLE `pozadavek_na_prohlidku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `osetrovatel_id` (`osetrovatel_id`);

--
-- Indexes for table `prodej`
--
ALTER TABLE `prodej`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prohlidka`
--
ALTER TABLE `prohlidka`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rezervace`
--
ALTER TABLE `rezervace`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klient_id` (`klient_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `umrti`
--
ALTER TABLE `umrti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uzivatel`
--
ALTER TABLE `uzivatel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uzivatel_ma_role`
--
ALTER TABLE `uzivatel_ma_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uzivatel_id` (`uzivatel_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `zvire`
--
ALTER TABLE `zvire`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zvire_je_volne`
--
ALTER TABLE `zvire_je_volne`
  ADD PRIMARY KEY (`id`),
  ADD KEY `osetrovatel_id` (`osetrovatel_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fotka_zvirete`
--
ALTER TABLE `fotka_zvirete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nalezeni`
--
ALTER TABLE `nalezeni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pozadavek_na_prohlidku`
--
ALTER TABLE `pozadavek_na_prohlidku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prodej`
--
ALTER TABLE `prodej`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prohlidka`
--
ALTER TABLE `prohlidka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rezervace`
--
ALTER TABLE `rezervace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `umrti`
--
ALTER TABLE `umrti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uzivatel`
--
ALTER TABLE `uzivatel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uzivatel_ma_role`
--
ALTER TABLE `uzivatel_ma_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zvire`
--
ALTER TABLE `zvire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zvire_je_volne`
--
ALTER TABLE `zvire_je_volne`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fotka_zvirete`
--
ALTER TABLE `fotka_zvirete`
  ADD CONSTRAINT `fotka_zvirete_ibfk_1` FOREIGN KEY (`zvire_id`) REFERENCES `zvire` (`id`);

--
-- Constraints for table `pozadavek_na_prohlidku`
--
ALTER TABLE `pozadavek_na_prohlidku`
  ADD CONSTRAINT `pozadavek_na_prohlidku_ibfk_1` FOREIGN KEY (`osetrovatel_id`) REFERENCES `uzivatel` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `rezervace`
--
ALTER TABLE `rezervace`
  ADD CONSTRAINT `rezervace_ibfk_1` FOREIGN KEY (`klient_id`) REFERENCES `uzivatel` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `uzivatel_ma_role`
--
ALTER TABLE `uzivatel_ma_role`
  ADD CONSTRAINT `uzivatel_ma_role_ibfk_1` FOREIGN KEY (`uzivatel_id`) REFERENCES `uzivatel` (`id`),
  ADD CONSTRAINT `uzivatel_ma_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `zvire_je_volne`
--
ALTER TABLE `zvire_je_volne`
  ADD CONSTRAINT `zvire_je_volne_ibfk_1` FOREIGN KEY (`osetrovatel_id`) REFERENCES `uzivatel` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
