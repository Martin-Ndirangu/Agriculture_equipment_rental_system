<?php
/**
 * M-Pesa STK Push Initiation
 * This file processes the STK Push request to the customer's phone
 */

session_start();
error_reporting(0);
require_once('dbconnection.php');
require_once('mpesa-config.php');

header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

$phoneNumber = isset($data['phone']) ? $data['phone'] : '';
$amount = isset($data['amount']) ? $data['amount'] : 0;
$bookingNumber = isset($data['booking_number']) ? $data['booking_number'] : '';
$userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : '';

// Validate inputs
if (empty($phoneNumber) || $amount <= 0 || empty($bookingNumber)) {
    echo json_encode(array('success' => false, 'message' => 'Invalid input data'));
    exit;
}

// Format phone number
$formattedPhone = formatPhoneNumber($phoneNumber);
if (!$formattedPhone) {
    echo json_encode(array('success' => false, 'message' => 'Invalid phone number format. Use format: 07XXXXXXXX or 2547XXXXXXXX'));
    exit;
}

// Round amount to nearest integer (M-Pesa doesn't accept decimals)
$amount = round($amount);

// Get access token
$tokenResult = getMpesaAccessToken();
if (!$tokenResult['success']) {
    echo json_encode(array('success' => false, 'message' => 'Failed to authenticate with M-Pesa: ' . $tokenResult['message']));
    exit;
}

$accessToken = $tokenResult['token'];

// Generate password and timestamp
$passwordData = generateMpesaPassword();
$password = $passwordData['password'];
$timestamp = $passwordData['timestamp'];

// Prepare STK Push request
$stkPushData = array(
    'BusinessShortCode' => MPESA_SHORTCODE,
    'Password' => $password,
    'Timestamp' => $timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $amount,
    'PartyA' => $formattedPhone,
    'PartyB' => MPESA_SHORTCODE,
    'PhoneNumber' => $formattedPhone,
    'CallBackURL' => MPESA_CALLBACK_URL,
    'AccountReference' => MPESA_ACCOUNT_REF . '-' . $bookingNumber,
    'TransactionDesc' => MPESA_TRANSACTION_DESC
);

// Send STK Push request
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, MPESA_STK_PUSH_URL);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken
));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($stkPushData));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$curl_response = curl_exec($curl);

if ($curl_response === false) {
    $error = curl_error($curl);
    curl_close($curl);
    echo json_encode(array('success' => false, 'message' => 'Connection error: ' . $error));
    exit;
}

curl_close($curl);

$response = json_decode($curl_response);

// Check response
if (isset($response->ResponseCode) && $response->ResponseCode == '0') {
    // STK Push sent successfully
    $checkoutRequestId = $response->CheckoutRequestID;
    $merchantRequestId = $response->MerchantRequestID;
    
    // Store transaction in database
    $stmt = $con->prepare("INSERT INTO tblmpesa_transactions (BookingNumber, UserID, PhoneNumber, Amount, CheckoutRequestID, MerchantRequestID, TransactionStatus, CreatedAt) VALUES (?, ?, ?, ?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("sssdss", $bookingNumber, $userId, $formattedPhone, $amount, $checkoutRequestId, $merchantRequestId);
    
    if ($stmt->execute()) {
        echo json_encode(array(
            'success' => true,
            'message' => 'STK Push sent successfully. Please check your phone and enter your M-Pesa PIN.',
            'checkout_request_id' => $checkoutRequestId,
            'merchant_request_id' => $merchantRequestId
        ));
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'STK Push sent but failed to record transaction: ' . $stmt->error
        ));
    }
    
    $stmt->close();
} else {
    // STK Push failed
    $errorMessage = isset($response->errorMessage) ? $response->errorMessage : 'Unknown error';
    $errorCode = isset($response->errorCode) ? $response->errorCode : 'N/A';
    
    echo json_encode(array(
        'success' => false,
        'message' => 'M-Pesa Error: ' . $errorMessage,
        'error_code' => $errorCode,
        'response' => $response
    ));
}

?>
