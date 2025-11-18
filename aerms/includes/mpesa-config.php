<?php
/**
 * M-Pesa Daraja API Configuration
 * 
 * IMPORTANT: Replace these with your actual Safaricom Daraja API credentials
 * Get your credentials from: https://developer.safaricom.co.ke/
 */

// Environment: 'sandbox' or 'live'
define('MPESA_ENV', 'sandbox');

// Sandbox Credentials (Replace CONSUMER_KEY and CONSUMER_SECRET with your actual credentials from Daraja Portal)
// Get these from: https://developer.safaricom.co.ke/ → My Apps → Your App → Keys Tab
define('MPESA_CONSUMER_KEY', 'Bl4jl9jwaffBD7hhl7gGnJex3oGVLHWufqMX7mjmKQGONGUw');
define('MPESA_CONSUMER_SECRET', '1MVWGMM96cmkmKZNzkWZerBDAPkeGmRVx5UnXGT66iubjVL0YGZyQBkNL8s77aJ2');

// Business Short Code (Paybill or Till Number)
// For SANDBOX: Keep as 174379 (default test shortcode)
// For PRODUCTION: Use your actual Paybill/Till number
define('MPESA_SHORTCODE', '174379');

// Lipa Na M-Pesa Online Passkey
// For SANDBOX: Use the default passkey below (this is public and safe for testing)
// For PRODUCTION: Replace with your production passkey from Safaricom
// Get from: Daraja Portal → My Apps → Your App → APIs → Lipa Na M-Pesa Online → Test Credentials
define('MPESA_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919');

// Callback URL - Where M-Pesa sends payment confirmation
// IMPORTANT: Must be HTTPS and publicly accessible (not localhost)
// 
// For LOCAL TESTING with ngrok:
// 1. Download ngrok: https://ngrok.com/download
// 2. Run: ngrok http 80
// 3. Copy HTTPS URL (e.g., https://abc123.ngrok.io)
// 4. Add path: https://abc123.ngrok.io/aerms/includes/mpesa-callback.php
// 
// For PRODUCTION:
// Use your domain: https://yourdomain.com/aerms/includes/mpesa-callback.php
// 
// See CALLBACK_URL_SETUP_GUIDE.md for detailed instructions
define('MPESA_CALLBACK_URL', 'https://elenore-onymous-nonjuridically.ngrok-free.dev/aerms/includes/mpesa-callback.php');

// API URLs
if (MPESA_ENV === 'sandbox') {
    define('MPESA_BASE_URL', 'https://sandbox.safaricom.co.ke');
} else {
    define('MPESA_BASE_URL', 'https://api.safaricom.co.ke');
}

define('MPESA_AUTH_URL', MPESA_BASE_URL . '/oauth/v1/generate?grant_type=client_credentials');
define('MPESA_STK_PUSH_URL', MPESA_BASE_URL . '/mpesa/stkpush/v1/processrequest');
define('MPESA_STK_QUERY_URL', MPESA_BASE_URL . '/mpesa/stkpushquery/v1/query');

// Transaction Description
define('MPESA_TRANSACTION_DESC', 'Equipment Rental Payment');

// Account Reference
define('MPESA_ACCOUNT_REF', 'AERMS');

/**
 * Get M-Pesa Access Token
 */
function getMpesaAccessToken() {
    $credentials = base64_encode(MPESA_CONSUMER_KEY . ':' . MPESA_CONSUMER_SECRET);
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, MPESA_AUTH_URL);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $curl_response = curl_exec($curl);
    
    if ($curl_response === false) {
        $error = curl_error($curl);
        curl_close($curl);
        return array('success' => false, 'message' => 'cURL Error: ' . $error);
    }
    
    curl_close($curl);
    
    $result = json_decode($curl_response);
    
    if (isset($result->access_token)) {
        return array('success' => true, 'token' => $result->access_token);
    } else {
        return array('success' => false, 'message' => 'Failed to get access token', 'response' => $result);
    }
}

/**
 * Generate M-Pesa Password
 */
function generateMpesaPassword() {
    $timestamp = date('YmdHis');
    $password = base64_encode(MPESA_SHORTCODE . MPESA_PASSKEY . $timestamp);
    
    return array(
        'password' => $password,
        'timestamp' => $timestamp
    );
}

/**
 * Format phone number to required format (254XXXXXXXXX)
 */
function formatPhoneNumber($phone) {
    // Remove any spaces, dashes, or plus signs
    $phone = preg_replace('/[\s\-\+]/', '', $phone);
    
    // If starts with 0, replace with 254
    if (substr($phone, 0, 1) === '0') {
        $phone = '254' . substr($phone, 1);
    }
    
    // If starts with 7 or 1, add 254
    if (substr($phone, 0, 1) === '7' || substr($phone, 0, 1) === '1') {
        $phone = '254' . $phone;
    }
    
    // Validate phone number
    if (!preg_match('/^254[0-9]{9}$/', $phone)) {
        return false;
    }
    
    return $phone;
}

?>
