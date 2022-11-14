
CREATE DATABASE IF NOT EXISTS `projectdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `projectdb`;

CREATE TABLE `cart` (
  `name` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `total_price` varchar(100) NOT NULL,
  `itemID` int(10) NOT NULL,
  `consignmentStoreID` int(10) NOT NULL,
  `rQ` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `consignmentstore` (
  `consignmentStoreID` int(10) NOT NULL,
  `tenantID` varchar(50) NOT NULL,
  `ConsignmentStoreName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `consignmentstore` (`consignmentStoreID`, `tenantID`, `ConsignmentStoreName`) VALUES
(1, 'marcus888', 'Marucs ConsignmentStore'),
(2, 'root', 'root shop'),
(4, 'ken', 'ken shop'),
(5, 'ken', 'shop 2'),
(6, 'root', 'root shop 2');

CREATE TABLE `consignmentstore_shop` (
  `consignmentStoreID` int(10) NOT NULL,
  `shopID` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `consignmentstore_shop` (`consignmentStoreID`, `shopID`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(4, 1),
(4, 2),
(6, 2);


CREATE TABLE `customer` (
  `customerEmail` varchar(50) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `phoneNumber` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `customer` (`customerEmail`, `firstName`, `lastName`, `password`, `phoneNumber`) VALUES
('ken@gmail.com', 'ken', 'ken', '123', '67412365'),
('root@gmail.com', 'root', 'rootuser', '123', '23214567'),
('taiMan@gmail.com', 'Tai Man', 'Chan', 'marcus123', '52839183');


CREATE TABLE `goods` (
  `goodsNumber` int(10) NOT NULL,
  `consignmentStoreID` int(10) NOT NULL,
  `goodsName` varchar(255) NOT NULL,
  `stockPrice` decimal(10,1) NOT NULL,
  `remainingStock` int(7) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL COMMENT 'The goods should include 2 stock status:  \n1. “Available”: Show only the available goods.  \n2. “Unavailable”: The goods has been discontinued or not already for sell.  '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `goods` (`goodsNumber`, `consignmentStoreID`, `goodsName`, `stockPrice`, `remainingStock`, `status`) VALUES
(1, 1, 'Bracelet', '99.5', 74, 1),
(2, 2, 'Anklet', '200.0', 85, 1),
(3, 2, 'apple', '20.0', 65, 1),
(5, 2, 'Pen', '12.0', 233, 1),
(6, 2, 'banana', '20.0', 200, 2),
(7, 2, 'cat', '233.0', 111, 1),
(8, 2, 'pie', '23.0', 44, 2),
(9, 2, 'dog', '34.0', 34, 1),
(10, 2, 'Glass', '234.0', 34, 2),
(11, 2, 'ive', '34.0', 34, 1),
(12, 2, 'doki', '34.0', 34, 1),
(13, 2, 'ba', '34.0', 33, 1),
(14, 2, 'it', '23.0', 22, 1),
(15, 6, 'kkk', '34.0', 34, 2),
(16, 4, 'apple', '23.0', 54, 1),
(17, 4, 'pen', '23.0', 23, 1),
(18, 6, 'item2', '23.0', 22, 1);


CREATE TABLE `orderitem` (
  `orderID` int(10) NOT NULL,
  `goodsNumber` int(10) NOT NULL,
  `quantity` int(7) NOT NULL,
  `sellingPrice` decimal(10,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orderitem` (`orderID`, `goodsNumber`, `quantity`, `sellingPrice`) VALUES
(1, 1, 3, '99.5'),
(1, 2, 1, '200.0'),
(82, 1, 1, '99.5'),
(83, 3, 1, '20.0'),
(84, 1, 1, '99.5'),
(85, 3, 1, '20.0'),
(86, 1, 1, '99.5'),
(88, 1, 1, '99.5'),
(89, 3, 1, '20.0'),
(90, 1, 1, '99.5'),
(91, 2, 1, '200.0'),
(91, 3, 1, '20.0'),
(92, 1, 1, '99.5'),
(93, 2, 1, '200.0'),
(93, 3, 1, '20.0'),
(94, 1, 1, '99.5'),
(95, 2, 1, '200.0'),
(95, 3, 1, '20.0'),
(96, 1, 1, '99.5'),
(98, 13, 1, '34.0'),
(98, 14, 1, '23.0'),
(99, 16, 6, '23.0'),
(100, 18, 1, '23.0');

CREATE TABLE `orders` (
  `orderID` int(10) NOT NULL,
  `customerEmail` varchar(50) NOT NULL,
  `consignmentStoreID` int(10) NOT NULL,
  `shopID` int(6) NOT NULL,
  `orderDateTime` datetime NOT NULL,
  `status` int(1) NOT NULL COMMENT 'The orders should include 3 statuses:  \n1.     “Delivery”: The parts are delivering to shop  \n2.     “Awaiting”: Goods are ready for pick up  \n3.     “Completed”: The goods has been picked up from customer  ',
  `totalPrice` decimal(10,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orders` (`orderID`, `customerEmail`, `consignmentStoreID`, `shopID`, `orderDateTime`, `status`, `totalPrice`) VALUES
(1, 'taiMan@gmail.com', 1, 1, '2020-05-14 07:34:29', 3, '498.5'),
(2, 'taiMan@gmail.com', 1, 2, '2020-06-22 08:25:13', 2, '99.5'),
(82, 'root@gmail.com', 1, 1, '2020-06-20 18:14:09', 1, '99.5'),
(83, 'root@gmail.com', 2, 2, '2020-06-20 18:14:09', 2, '20.0'),
(84, 'root@gmail.com', 1, 1, '2020-06-20 18:14:26', 1, '99.5'),
(85, 'root@gmail.com', 6, 2, '2020-06-20 18:14:26', 3, '20.0'),
(86, 'root@gmail.com', 1, 1, '2020-06-20 22:37:13', 1, '99.5'),
(88, 'root@gmail.com', 1, 1, '2020-06-20 22:37:24', 1, '99.5'),
(89, 'root@gmail.com', 6, 2, '2020-06-20 22:37:24', 2, '20.0'),
(90, 'root@gmail.com', 1, 1, '2020-06-20 22:43:31', 1, '99.5'),
(91, 'root@gmail.com', 2, 2, '2020-06-20 22:43:31', 1, '220.0'),
(92, 'root@gmail.com', 1, 1, '2020-06-20 22:43:56', 1, '99.5'),
(93, 'root@gmail.com', 2, 2, '2020-06-20 22:43:56', 1, '220.0'),
(94, 'root@gmail.com', 1, 1, '2020-06-20 22:44:55', 1, '99.5'),
(95, 'root@gmail.com', 2, 2, '2020-06-20 22:44:55', 2, '220.0'),
(96, 'root@gmail.com', 1, 1, '2020-06-20 22:45:11', 1, '99.5'),
(98, 'root@gmail.com', 2, 2, '2020-06-24 18:31:22', 1, '57.0'),
(99, 'root@gmail.com', 4, 1, '2020-06-24 18:31:22', 1, '138.0'),
(100, 'root@gmail.com', 6, 2, '2020-06-24 18:31:22', 1, '23.0');

CREATE TABLE `shop` (
  `shopID` int(6) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `shop` (`shopID`, `address`) VALUES
(1, 'No. 18, 1 / F, Trendy Zone, 580A Nathan Road, Mong Kok'),
(2, 'No. 1047, 10/F, Nan Fung Centre, 264-298 Castle Peak Road, Tsuen Wan');


CREATE TABLE `tenant` (
  `tenantID` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tenant` (`tenantID`, `name`, `password`) VALUES
('ken', 'ken', '123'),
('marcus888', 'Marcus', 'it888'),
('root', 'root', '123');

ALTER TABLE `cart`
  ADD PRIMARY KEY (`itemID`);

ALTER TABLE `consignmentstore`
  ADD PRIMARY KEY (`consignmentStoreID`),
  ADD KEY `FKConsignmen625115` (`tenantID`);

ALTER TABLE `consignmentstore_shop`
  ADD PRIMARY KEY (`consignmentStoreID`,`shopID`),
  ADD KEY `FKConsignmen537135` (`consignmentStoreID`),
  ADD KEY `FKConsignmen824630` (`shopID`);

ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerEmail`);

ALTER TABLE `goods`
  ADD PRIMARY KEY (`goodsNumber`),
  ADD KEY `FKGoods866951` (`consignmentStoreID`);

ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`orderID`,`goodsNumber`),
  ADD KEY `FKOrderItem915607` (`orderID`),
  ADD KEY `FKOrderItem82428` (`goodsNumber`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `FKOrders837071` (`customerEmail`),
  ADD KEY `FKOrders959018` (`consignmentStoreID`,`shopID`);

ALTER TABLE `shop`
  ADD PRIMARY KEY (`shopID`);


ALTER TABLE `tenant`
  ADD PRIMARY KEY (`tenantID`);

ALTER TABLE `consignmentstore`
  MODIFY `consignmentStoreID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `goods`
  MODIFY `goodsNumber` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

ALTER TABLE `orders`
  MODIFY `orderID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

ALTER TABLE `shop`
  MODIFY `shopID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `consignmentstore`
  ADD CONSTRAINT `FKConsignmen625115` FOREIGN KEY (`tenantID`) REFERENCES `tenant` (`tenantID`);

ALTER TABLE `consignmentstore_shop`
  ADD CONSTRAINT `FKConsignmen537135` FOREIGN KEY (`consignmentStoreID`) REFERENCES `consignmentstore` (`consignmentStoreID`),
  ADD CONSTRAINT `FKConsignmen824630` FOREIGN KEY (`shopID`) REFERENCES `shop` (`shopID`);

ALTER TABLE `goods`
  ADD CONSTRAINT `FKGoods866951` FOREIGN KEY (`consignmentStoreID`) REFERENCES `consignmentstore` (`consignmentStoreID`);

ALTER TABLE `orderitem`
  ADD CONSTRAINT `FKOrderItem82428` FOREIGN KEY (`goodsNumber`) REFERENCES `goods` (`goodsNumber`),
  ADD CONSTRAINT `FKOrderItem915607` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);

ALTER TABLE `orders`
  ADD CONSTRAINT `FKOrders837071` FOREIGN KEY (`customerEmail`) REFERENCES `customer` (`customerEmail`),
  ADD CONSTRAINT `FKOrders959018` FOREIGN KEY (`consignmentStoreID`,`shopID`) REFERENCES `consignmentstore_shop` (`consignmentStoreID`, `shopID`);
COMMIT;

