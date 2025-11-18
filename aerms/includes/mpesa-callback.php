<?php
/**
 * M-Pesa Callback Handler
 * This file receives payment confirmation from Safaricom
 */

require_once('dbconnection.php');

// Log file for debugging
$logFile = __DIR__ . '/mpesa_callback_log.txt';

// Get the callback data
$callbackData = file_get_contents('php://input');

// Log the raw callback data
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Callback Received:\n" . $callbackData . "\n\n", FILE_APPEND);

// Decode the JSON data
$data = json_decode($callbackData, true);

if (!$data) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Invalid JSON data\n\n", FILE_APPEND);
    exit;
}

// Extract callback data
$resultCode = $data['Body']['stkCallback']['ResultCode'];
$resultDesc = $data['Body']['stkCallback']['ResultDesc'];
$merchantRequestId = $data['Body']['stkCallback']['MerchantRequestID'];
$checkoutRequestId = $data['Body']['stkCallback']['CheckoutRequestID'];

// Log the extracted data
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Processing:\n" . 
    "MerchantRequestID: $merchantRequestId\n" .
    "CheckoutRequestID: $checkoutRequestId\n" .
    "ResultCode: $resultCode\n" .
    "ResultDesc: $resultDesc\n\n", FILE_APPEND);

if ($resultCode == 0) {
    // Payment successful
    $callbackMetadata = $data['Body']['stkCallback']['CallbackMetadata']['Item'];
    
    $amount = 0;
    $mpesaReceiptNumber = '';
    $phoneNumber = '';
    $transactionDate = '';
    
    // Extract metadata
    foreach ($callbackMetadata as $item) {
        switch ($item['Name']) {
            case 'Amount':
                $amount = $item['Value'];
                break;
            case 'MpesaReceiptNumber':
                $mpesaReceiptNumber = $item['Value'];
                break;
            case 'PhoneNumber':
                $phoneNumber = $item['Value'];
                break;
            case 'TransactionDate':
                $transactionDate = $item['Value'];
                break;
        }
    }
    
    // Update transaction in database
    $stmt = $con->prepare("UPDATE tblmpesa_transactions SET 
        TransactionStatus = 'Completed', 
        MpesaReceiptNumber = ?, 
        TransactionDate = ?, 
        ResultDesc = ?, 
        UpdatedAt = NOW() 
        WHERE CheckoutRequestID = ?");
    
    $stmt->bind_param("ssss", $mpesaReceiptNumber, $transactionDate, $resultDesc, $checkoutRequestId);
    
    if ($stmt->execute()) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Transaction updated successfully\n\n", FILE_APPEND);
        
        // Get booking number from transaction
        $result = mysqli_query($con, "SELECT BookingNumber FROM tblmpesa_transactions WHERE CheckoutRequestID = '$checkoutRequestId'");
        if ($row = mysqli_fetch_assoc($result)) {
            $bookingNumber = $row['BookingNumber'];
            
            // Update booking payment status
            mysqli_query($con, "UPDATE tblbooking SET PaymentStatus = 'Paid', PaymentMethod = 'M-Pesa', MpesaReceiptNumber = '$mpesaReceiptNumber' WHERE BookingNumber = '$bookingNumber'");
            
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Booking updated: $bookingNumber\n\n", FILE_APPEND);
        }
    } else {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Failed to update transaction: " . $stmt->error . "\n\n", FILE_APPEND);
    }
    
    $stmt->close();
} else {
    // Payment failed or cancelled
    $stmt = $con->prepare("UPDATE tblmpesa_transactions SET 
        TransactionStatus = 'Failed', 
        ResultDesc = ?, 
        UpdatedAt = NOW() 
        WHERE CheckoutRequestID = ?");
    
    $stmt->bind_param("ss", $resultDesc, $checkoutRequestId);
    $stmt->execute();
    $stmt->close();
    
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Transaction failed: $resultDesc\n\n", FILE_APPEND);
}

// Send response to Safaricom
header('Content-Type: application/json');
echo json_encode(array('ResultCode' => 0, 'ResultDesc' => 'Accepted'));

?>
