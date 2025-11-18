<?php
/**
 * Check M-Pesa Payment Status
 * This file checks the payment status for a specific booking
 */

session_start();
error_reporting(0);
require_once('dbconnection.php');

header('Content-Type: application/json');

// Get booking number
$bookingNumber = isset($_GET['booking_number']) ? $_GET['booking_number'] : '';

if (empty($bookingNumber)) {
    echo json_encode(array('success' => false, 'message' => 'Booking number not provided'));
    exit;
}

// Check payment status
$stmt = $con->prepare("SELECT TransactionStatus, MpesaReceiptNumber, ResultDesc FROM tblmpesa_transactions WHERE BookingNumber = ? ORDER BY CreatedAt DESC LIMIT 1");
$stmt->bind_param("s", $bookingNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $status = $row['TransactionStatus'];
    
    if ($status === 'Completed') {
        echo json_encode(array(
            'success' => true,
            'status' => 'Completed',
            'message' => 'Payment successful',
            'receipt_number' => $row['MpesaReceiptNumber']
        ));
    } elseif ($status === 'Failed') {
        echo json_encode(array(
            'success' => false,
            'status' => 'Failed',
            'message' => $row['ResultDesc'] ?: 'Payment failed'
        ));
    } else {
        echo json_encode(array(
            'success' => false,
            'status' => 'Pending',
            'message' => 'Payment is still pending. Please check your phone.'
        ));
    }
} else {
    echo json_encode(array(
        'success' => false,
        'status' => 'Not Found',
        'message' => 'No payment record found'
    ));
}

$stmt->close();

?>
