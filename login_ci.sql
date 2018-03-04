

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";



--
-- Database: `login_ci`
--

-- --------------------------------------------------------


CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `cookie` varchar(100) DEFAULT NULL,
  `is_admin` enum('1','0') NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `cookie`, `is_admin`, `created`, `updated`) VALUES
(1, 'admin', '$2y$10$tplstsTv8SYhN9xTsH5YOe19jF8BNcdHhMYEswgYlp.BgOEuFgf7y', 'hjTB6YaC9zyQRRjP6zGHnKglErHxlrk8JgPt8kemD07JMdiSDfdpTnwXsWANOf0u', '1', NOW(), '0000-00-00 00:00:00'),
(2, 'user', '$2y$10$jUnncavC73WrpB5PLVOazuC3iti9Tt3mMpLAAdn9QSS9AKTbkg3WK', 'UTBumaAgPFJQu8IrNZWySi6pMvL6LyRI90zXwrnvZdnsTpJf2q2cOO73QmoKHNBa', '0', NOW(), '0000-00-00 00:00:00');

--
-- Triggers `user`
--

ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);


ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;


