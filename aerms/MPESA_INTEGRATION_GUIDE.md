# M-Pesa STK Push Integration - Setup Guide

## Overview

This integration enables M-Pesa STK Push payments for the Agriculture Equipment Rental Management System. Customers can pay for equipment rentals directly using their M-Pesa accounts.

## Features

✅ Real-time STK Push to customer's phone
✅ Automatic payment verification
✅ Professional payment modal with status updates
✅ Automatic total amount calculation
✅ Payment history tracking
✅ Secure transaction handling
✅ Callback handling for payment confirmation

---

## Installation Steps

### Step 1: Database Setup

Run the SQL script to create necessary tables and columns:

```sql
-- Navigate to phpMyAdmin or MySQL command line
-- Select your database (aermsdb)
-- Run the contents of: mpesa_database_schema.sql
```

Or use command line:

```bash
mysql -u root -p aermsdb < mpesa_database_schema.sql
```

### Step 2: Configure M-Pesa Credentials

1. **Get Safaricom Daraja API Credentials:**

   - Visit: https://developer.safaricom.co.ke/
   - Create an account or login
   - Create a new app
   - Note down:
     - Consumer Key
     - Consumer Secret
     - Paybill/Till Number (Shortcode)
     - Passkey

2. **Update Configuration File:**
   - Open: `aerms/includes/mpesa-config.php`
   - Replace the following placeholders:

```php
// Replace these with your actual credentials
define('MPESA_CONSUMER_KEY', 'YOUR_CONSUMER_KEY_HERE');
define('MPESA_CONSUMER_SECRET', 'YOUR_CONSUMER_SECRET_HERE');
define('MPESA_SHORTCODE', 'YOUR_SHORTCODE_HERE'); // e.g., 174379 for sandbox
define('MPESA_PASSKEY', 'YOUR_PASSKEY_HERE');
```

### Step 3: Setup Callback URL

**IMPORTANT:** M-Pesa requires a publicly accessible callback URL (not localhost).

**For Testing (Development):**

1. Install ngrok: https://ngrok.com/download
2. Start ngrok tunnel:
   ```bash
   ngrok http 80
   ```
3. Copy the HTTPS URL (e.g., https://abc123.ngrok.io)
4. Update callback URL in `mpesa-config.php`:
   ```php
   define('MPESA_CALLBACK_URL', 'https://abc123.ngrok.io/aerms/includes/mpesa-callback.php');
   ```

**For Production (Live):**

1. Use your actual domain:
   ```php
   define('MPESA_CALLBACK_URL', 'https://yourdomain.com/aerms/includes/mpesa-callback.php');
   ```

### Step 4: Environment Setup

**For Testing:**

- Keep: `define('MPESA_ENV', 'sandbox');`
- Use sandbox credentials from Daraja portal

**For Production:**

- Change to: `define('MPESA_ENV', 'live');`
- Use production credentials
- Complete Daraja API Go-Live process

### Step 5: Test the Integration

1. Start XAMPP (Apache and MySQL)
2. If using ngrok, start the tunnel
3. Navigate to: http://localhost/aerms/
4. Login to the system
5. Select a product and click "Book"
6. Fill in booking details including M-Pesa phone number
7. Click "Book & Pay with M-Pesa"
8. Check your phone for the STK Push prompt
9. Enter your M-Pesa PIN

---

## How It Works

### User Flow:

1. User selects product and fills booking form
2. System calculates total amount (days × quantity × rent price)
3. User enters M-Pesa phone number
4. User submits form
5. Booking is created with "Pending" payment status
6. STK Push is sent to user's phone
7. User enters M-Pesa PIN on their phone
8. System receives callback from Safaricom
9. Payment status is updated
10. User is redirected to booking confirmation

### Technical Flow:

```
Book Form → PHP Processing → Create Booking → Initiate STK Push
                                                    ↓
                                            M-Pesa API (Daraja)
                                                    ↓
                                            Customer's Phone
                                                    ↓
                                            Customer Enters PIN
                                                    ↓
                                            Callback to Server
                                                    ↓
                                            Update Payment Status
```

---

## File Structure

```
aerms/
├── book-products.php              (Updated - Main booking form with M-Pesa integration)
├── mpesa_database_schema.sql     (New - Database schema)
├── includes/
│   ├── mpesa-config.php          (New - M-Pesa configuration)
│   ├── mpesa-stk-push.php        (New - STK Push initiation)
│   ├── mpesa-callback.php        (New - Payment callback handler)
│   └── mpesa-status.php          (New - Payment status checker)
```

---

## Database Tables

### 1. tblmpesa_transactions

Stores all M-Pesa transaction details:

- ID
- BookingNumber
- UserID
- PhoneNumber
- Amount
- CheckoutRequestID
- MerchantRequestID
- MpesaReceiptNumber
- TransactionStatus (Pending, Completed, Failed)
- TransactionDate
- ResultDesc
- CreatedAt
- UpdatedAt

### 2. tblbooking (Updated)

Added columns:

- TotalAmount
- PaymentStatus (Pending, Paid, Failed, Refunded)
- PaymentMethod
- MpesaReceiptNumber
- MpesaPhoneNumber

---

## Troubleshooting

### Common Issues:

**1. "Failed to get access token"**

- Check your Consumer Key and Secret
- Verify internet connection
- Check if you're using correct sandbox/live credentials

**2. "Invalid phone number format"**

- Phone must be: 07XXXXXXXX or 254XXXXXXXX
- Must be a Safaricom number
- Must be registered for M-Pesa

**3. "Payment timeout"**

- User took too long to enter PIN
- Network issues
- Check callback URL is publicly accessible

**4. Callback not received**

- Verify callback URL is HTTPS and publicly accessible
- Check callback URL in mpesa-config.php
- Review callback log: `includes/mpesa_callback_log.txt`

**5. Database errors**

- Run the SQL schema script
- Check database connection in dbconnection.php
- Verify table and column names

### Testing Phone Numbers (Sandbox):

The sandbox environment accepts test phone numbers:

- Format: 254XXXXXXXXX
- Example: 254708374149 (Safaricom test number)

### Checking Logs:

M-Pesa callbacks are logged in:

```
aerms/includes/mpesa_callback_log.txt
```

---

## Security Best Practices

1. ✅ Never commit credentials to version control
2. ✅ Use environment variables for production
3. ✅ Keep mpesa-config.php outside web root in production
4. ✅ Implement IP whitelisting for callbacks
5. ✅ Use HTTPS for all transactions
6. ✅ Validate all input data
7. ✅ Monitor transaction logs regularly

---

## Production Checklist

Before going live:

- [ ] Complete Daraja API Go-Live application
- [ ] Update to production credentials
- [ ] Change MPESA_ENV to 'live'
- [ ] Set production callback URL (HTTPS)
- [ ] Test with real phone numbers
- [ ] Implement proper error logging
- [ ] Set up monitoring and alerts
- [ ] Configure proper database backups
- [ ] Review security settings
- [ ] Test callback handling thoroughly

---

## Support & Resources

- **Safaricom Daraja Portal:** https://developer.safaricom.co.ke/
- **API Documentation:** https://developer.safaricom.co.ke/Documentation
- **Daraja Support:** https://developer.safaricom.co.ke/support
- **Test Credentials:** Available in Daraja sandbox

---

## Currency Note

The system uses KSH (Kenyan Shillings) by default. M-Pesa only accepts whole numbers (no decimals), so amounts are rounded automatically.

---

## Maintenance

### Regular Tasks:

1. Monitor transaction logs
2. Check callback success rates
3. Review failed payments
4. Clean up old pending transactions
5. Update API credentials before expiry

### Query Examples:

```sql
-- Check pending payments
SELECT * FROM tblmpesa_transactions WHERE TransactionStatus = 'Pending';

-- Check today's transactions
SELECT * FROM tblmpesa_transactions WHERE DATE(CreatedAt) = CURDATE();

-- Reconcile bookings with payments
SELECT b.BookingNumber, b.PaymentStatus, m.TransactionStatus, m.MpesaReceiptNumber
FROM tblbooking b
LEFT JOIN tblmpesa_transactions m ON b.BookingNumber = m.BookingNumber
WHERE b.PaymentStatus = 'Pending';
```

---

## Version Information

- **Version:** 1.0
- **Last Updated:** November 2025
- **PHP Version Required:** 7.0+
- **MySQL Version Required:** 5.6+
- **cURL Required:** Yes

---

## Credits

Developed for Agriculture Equipment Rental Management System
M-Pesa Integration using Safaricom Daraja API v1

---

## License

This integration is part of the Agriculture Equipment Rental Management System.
