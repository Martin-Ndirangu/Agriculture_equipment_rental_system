-- M-Pesa Payment Integration Database Schema
-- Run this SQL script to add necessary tables and columns

-- 1. Create M-Pesa transactions table
CREATE TABLE IF NOT EXISTS `tblmpesa_transactions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `BookingNumber` varchar(100) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `CheckoutRequestID` varchar(100) DEFAULT NULL,
  `MerchantRequestID` varchar(100) DEFAULT NULL,
  `MpesaReceiptNumber` varchar(100) DEFAULT NULL,
  `TransactionStatus` enum('Pending','Completed','Failed','Cancelled') DEFAULT 'Pending',
  `TransactionDate` varchar(20) DEFAULT NULL,
  `ResultDesc` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `BookingNumber` (`BookingNumber`),
  KEY `CheckoutRequestID` (`CheckoutRequestID`),
  KEY `TransactionStatus` (`TransactionStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 2. Add payment-related columns to tblbooking table (if they don't exist)
ALTER TABLE `tblbooking` 
ADD COLUMN IF NOT EXISTS `TotalAmount` decimal(10,2) DEFAULT NULL AFTER `AddressProof`,
ADD COLUMN IF NOT EXISTS `PaymentStatus` enum('Pending','Paid','Failed','Refunded') DEFAULT 'Pending' AFTER `TotalAmount`,
ADD COLUMN IF NOT EXISTS `PaymentMethod` varchar(50) DEFAULT NULL AFTER `PaymentStatus`,
ADD COLUMN IF NOT EXISTS `MpesaReceiptNumber` varchar(100) DEFAULT NULL AFTER `PaymentMethod`,
ADD COLUMN IF NOT EXISTS `MpesaPhoneNumber` varchar(15) DEFAULT NULL AFTER `MpesaReceiptNumber`;

-- 3. Create index for better performance
ALTER TABLE `tblbooking` 
ADD INDEX IF NOT EXISTS `idx_payment_status` (`PaymentStatus`),
ADD INDEX IF NOT EXISTS `idx_booking_number` (`BookingNumber`);

-- Note: After running this script, you should update existing bookings if needed
-- Example: UPDATE tblbooking SET PaymentStatus = 'Pending' WHERE PaymentStatus IS NULL;
