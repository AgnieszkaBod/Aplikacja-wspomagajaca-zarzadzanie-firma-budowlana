-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 12 Sty 2021, 14:41
-- Wersja serwera: 10.4.16-MariaDB
-- Wersja PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `inzynierka`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `budowa`
--

CREATE TABLE `budowa` (
  `id_budowy` int(11) NOT NULL,
  `nazwa_budowy` varchar(100) NOT NULL,
  `miejscowosc` varchar(56) NOT NULL,
  `ulica` varchar(56) NOT NULL,
  `numer_budynku` varchar(11) NOT NULL,
  `kod_pocztowy` varchar(6) NOT NULL,
  `data_rozpoczecia_budowy` date DEFAULT NULL,
  `data_zakonczenia_budowy` date DEFAULT NULL,
  `id_statusu_budowy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `budowa`
--

INSERT INTO `budowa` (`id_budowy`, `nazwa_budowy`, `miejscowosc`, `ulica`, `numer_budynku`, `kod_pocztowy`, `data_rozpoczecia_budowy`, `data_zakonczenia_budowy`, `id_statusu_budowy`) VALUES
(1, 'Lublin-Szpital', 'Lublin', 'Lubartowska', '15', '22-202', '2021-01-09', '2021-01-09', 2),
(2, 'Lublin-Stadion', 'Lublin', 'Krotka', '13', '20-341', '2020-11-03', '2020-11-30', 3),
(3, 'PuÅ‚awy_Szpital', 'PuÅ‚awy', 'Nowa', '1', '20-202', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `harmonogram`
--

CREATE TABLE `harmonogram` (
  `id_harmonogramu` int(11) UNSIGNED NOT NULL,
  `data_pracy` date NOT NULL,
  `godzina_rozpoczecia` time NOT NULL,
  `godzina_zakonczenia` time NOT NULL,
  `notatka` varchar(256) DEFAULT NULL,
  `przepracowane_godziny` float NOT NULL,
  `id_budowy` int(11) NOT NULL,
  `id_pracownika` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `harmonogram`
--

INSERT INTO `harmonogram` (`id_harmonogramu`, `data_pracy`, `godzina_rozpoczecia`, `godzina_zakonczenia`, `notatka`, `przepracowane_godziny`, `id_budowy`, `id_pracownika`) VALUES
(5, '2020-11-04', '15:00:00', '17:50:00', 'aaa', 0, 1, 3),
(6, '2020-11-24', '07:20:00', '17:26:00', '4', 0, 1, 3),
(7, '2020-11-10', '13:20:00', '15:20:00', 'co chcesz', 0, 2, 3),
(8, '2020-11-12', '13:20:00', '17:20:00', '1c', 0, 2, 3),
(9, '2020-11-05', '14:22:00', '18:22:00', '123', 0, 1, 6),
(10, '2020-11-10', '14:23:00', '17:23:00', '1234', 0, 2, 6),
(11, '2020-12-22', '16:35:00', '19:35:00', 'test1', 0, 2, 3),
(13, '2020-12-14', '21:40:00', '22:40:00', 'test0 test1', 0, 1, 3),
(14, '2020-12-14', '21:50:00', '22:50:00', 'testgodz', 0, 3, 3),
(15, '2020-12-14', '21:50:00', '22:50:00', 'testgodz\r\n', 0, 2, 3),
(16, '2020-12-28', '22:03:00', '23:03:00', ' ', 0, 1, 3),
(17, '2020-12-14', '22:04:00', '23:04:00', '', 0, 1, 3),
(18, '0000-00-00', '00:00:00', '00:00:00', '   ', 0, 1, 3),
(19, '2020-12-08', '22:05:00', '23:05:00', ' ', 0, 1, 3),
(20, '2020-12-01', '22:07:00', '23:07:00', '', 0, 1, 3),
(21, '2020-12-14', '22:16:00', '23:16:00', '  ', 0, 1, 3),
(22, '2020-12-14', '20:00:00', '23:53:00', '', 3.88333, 1, 6),
(23, '2020-12-10', '14:33:00', '20:03:00', '', 5.5, 1, 6),
(24, '2021-01-08', '14:58:00', '19:58:00', 'Zakonczono', 5, 1, 3),
(25, '2021-01-07', '15:05:00', '20:05:00', ' Nowa notatka', 5, 2, 3),
(26, '2021-01-08', '08:00:00', '14:55:00', 'Ukonczono 50%', 6.91667, 2, 3),
(27, '2021-01-09', '08:05:00', '15:00:00', 'Zakonczono', 6.91667, 1, 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pracownik`
--

CREATE TABLE `pracownik` (
  `id` int(11) NOT NULL,
  `imie` varchar(45) NOT NULL,
  `nazwisko` varchar(45) NOT NULL,
  `login` varchar(45) NOT NULL,
  `haslo` varchar(256) NOT NULL,
  `pesel` varchar(11) NOT NULL,
  `stawka_za_godzine` decimal(5,2) NOT NULL,
  `id_stanowiska` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `pracownik`
--

INSERT INTO `pracownik` (`id`, `imie`, `nazwisko`, `login`, `haslo`, `pesel`, `stawka_za_godzine`, `id_stanowiska`) VALUES
(2, 'Admin', 'Admin', 'admin', '$2y$10$JWa72j1Oq3HxvqInEce9cukbrpsGNLSn9Thal8o/JLLtv5FE/4B6.', '12345678901', '10.00', 1),
(3, 'Jan', 'Kowalski', 'janKow', '$2y$10$/ga/ztX0LRNK3F/MEFezfu9yZDGYsmOor0icXvX1Rs0iDz5fIICZ6', '98052602196', '8.00', 4),
(4, 'Adam', 'Nowak', 'adamNow', '$2y$10$aY2DkRGea5G2WJmDP6EniuKbk3scgARWe88uHejzEndRRN/0BH4MW', '76052209091', '7.00', 3),
(5, 'Andrzej', 'Kowalski', 'prezes', '$2y$10$.shrX/QvwwBtorSk1DbpvOUt2dJ.EI3Ydukkfv5P7dGmc0sm.oUHq', '70012002023', '50.00', 2),
(6, 'Tomasz', 'Kwiat', 'tomkwiat', '$2y$10$ARWDXT4h6H19HpsW2K50qe22pbiWf4tCtheJUeSYqxQyQpuIl5jWu', '12345670089', '5.00', 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `stanowisko`
--

CREATE TABLE `stanowisko` (
  `id_stanowiska` int(11) NOT NULL,
  `nazwa` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `stanowisko`
--

INSERT INTO `stanowisko` (`id_stanowiska`, `nazwa`) VALUES
(1, 'admin'),
(2, 'prezes'),
(3, 'brygadzista'),
(4, 'pracownik');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `status_budowy`
--

CREATE TABLE `status_budowy` (
  `id_statusu_budowy` int(11) NOT NULL,
  `nazwa_statusu_budowy` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `status_budowy`
--

INSERT INTO `status_budowy` (`id_statusu_budowy`, `nazwa_statusu_budowy`) VALUES
(1, 'planowana'),
(2, 'w trakcie'),
(3, 'zakonczona');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `budowa`
--
ALTER TABLE `budowa`
  ADD PRIMARY KEY (`id_budowy`),
  ADD KEY `id_statusu_budowy` (`id_statusu_budowy`);

--
-- Indeksy dla tabeli `harmonogram`
--
ALTER TABLE `harmonogram`
  ADD PRIMARY KEY (`id_harmonogramu`),
  ADD KEY `id_budowy` (`id_budowy`),
  ADD KEY `id_pracownika` (`id_pracownika`);

--
-- Indeksy dla tabeli `pracownik`
--
ALTER TABLE `pracownik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_stanowiska` (`id_stanowiska`);

--
-- Indeksy dla tabeli `stanowisko`
--
ALTER TABLE `stanowisko`
  ADD PRIMARY KEY (`id_stanowiska`);

--
-- Indeksy dla tabeli `status_budowy`
--
ALTER TABLE `status_budowy`
  ADD PRIMARY KEY (`id_statusu_budowy`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `budowa`
--
ALTER TABLE `budowa`
  MODIFY `id_budowy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `harmonogram`
--
ALTER TABLE `harmonogram`
  MODIFY `id_harmonogramu` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT dla tabeli `pracownik`
--
ALTER TABLE `pracownik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `stanowisko`
--
ALTER TABLE `stanowisko`
  MODIFY `id_stanowiska` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `status_budowy`
--
ALTER TABLE `status_budowy`
  MODIFY `id_statusu_budowy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `budowa`
--
ALTER TABLE `budowa`
  ADD CONSTRAINT `budowa_ibfk_1` FOREIGN KEY (`id_statusu_budowy`) REFERENCES `status_budowy` (`id_statusu_budowy`);

--
-- Ograniczenia dla tabeli `harmonogram`
--
ALTER TABLE `harmonogram`
  ADD CONSTRAINT `harmonogram_ibfk_1` FOREIGN KEY (`id_budowy`) REFERENCES `budowa` (`id_budowy`),
  ADD CONSTRAINT `harmonogram_ibfk_2` FOREIGN KEY (`id_pracownika`) REFERENCES `pracownik` (`id`);

--
-- Ograniczenia dla tabeli `pracownik`
--
ALTER TABLE `pracownik`
  ADD CONSTRAINT `pracownik_ibfk_1` FOREIGN KEY (`id_stanowiska`) REFERENCES `stanowisko` (`id_stanowiska`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
