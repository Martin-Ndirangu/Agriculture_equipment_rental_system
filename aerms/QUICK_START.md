# M-Pesa Integration - Quick Start Guide

## 🚀 Quick Setup (5 Minutes)

### Step 1: Run Database Migration

Open phpMyAdmin and run this SQL:

```sql
-- Create M-Pesa transactions table
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
  KEY `CheckoutRequestID` (`CheckoutRequestID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Add payment columns to booking table
ALTER TABLE `tblbooking`
ADD COLUMN `TotalAmount` decimal(10,2) DEFAULT NULL,
ADD COLUMN `PaymentStatus` enum('Pending','Paid','Failed','Refunded') DEFAULT 'Pending',
ADD COLUMN `PaymentMethod` varchar(50) DEFAULT NULL,
ADD COLUMN `MpesaReceiptNumber` varchar(100) DEFAULT NULL,
ADD COLUMN `MpesaPhoneNumber` varchar(15) DEFAULT NULL;
```

### Step 2: Get M-Pesa Credentials

**For Testing (Sandbox):**

1. Go to: https://developer.safaricom.co.ke/
2. Register/Login
3. Create a new app
4. Select "Lipa Na M-Pesa Online"
5. Copy:
   - Consumer Key
   - Consumer Secret
   - Passkey (from Test Credentials section)
   - Use Shortcode: **174379** (sandbox default)

### Step 3: Update Configuration

Edit: `aerms/includes/mpesa-config.php`

```php
// Sandbox Test Credentials
define('MPESA_CONSUMER_KEY', 'YOUR_CONSUMER_KEY_HERE');
define('MPESA_CONSUMER_SECRET', 'YOUR_CONSUMER_SECRET_HERE');
define('MPESA_SHORTCODE', '174379'); // Sandbox shortcode
define('MPESA_PASSKEY', 'YOUR_PASSKEY_HERE');
```

### Step 4: Setup Callback URL (Important!)

**Option A: Using ngrok (Recommended for testing)**

```bash
# Download ngrok from https://ngrok.com/download
# Run in terminal:
ngrok http 80

# Copy the HTTPS URL (e.g., https://abc123.ngrok.io)
# Update in mpesa-config.php:
define('MPESA_CALLBACK_URL', 'https://abc123.ngrok.io/aerms/includes/mpesa-callback.php');
```

**Option B: Using localhost (Limited testing)**

```php
// Will work for STK Push but callbacks won't be received
define('MPESA_CALLBACK_URL', 'http://localhost/aerms/includes/mpesa-callback.php');
```

### Step 5: Test It!

1. Navigate to: http://localhost/aerms/
2. Login to the system
3. Go to any product and click "Book"
4. Fill the form:
   - Select dates
   - Enter quantity
   - Fill delivery details
   - **M-Pesa Phone:** Use format `0712345678` or `254712345678`
5. Click "Book & Pay with M-Pesa"
6. Check your phone for STK Push
7. Enter your M-Pesa PIN

---

## 📱 Test Phone Numbers (Sandbox)

For sandbox testing, use these Safaricom test numbers:

- **254708374149** (Recommended)
- **254719789044**
- **Your own Safaricom number** (if registered for M-Pesa)

---

## ✅ Verification Checklist

- [ ] Database tables created successfully
- [ ] M-Pesa credentials configured
- [ ] Callback URL is publicly accessible (if using ngrok)
- [ ] XAMPP Apache and MySQL running
- [ ] Can access booking form
- [ ] Phone number field accepts valid format
- [ ] Amount calculation shows correctly

---

## 🔍 Testing Scenarios

### Test 1: Successful Payment

1. Book a product
2. Enter valid M-Pesa number
3. Complete payment with PIN
4. ✅ Should see success message
5. ✅ Payment status should be "Paid"

### Test 2: Failed/Cancelled Payment

1. Book a product
2. Enter valid M-Pesa number
3. Cancel or enter wrong PIN
4. ✅ Should see failure message
5. ✅ Payment status should be "Failed"

### Test 3: Amount Calculation

1. Select dates: 3 days apart
2. Set quantity: 2
3. ✅ Should show: `3 days × 2 units × price per day`

---

## 🐛 Common Issues & Quick Fixes

### "Failed to get access token"

**Fix:** Check Consumer Key and Secret are correct

### "Invalid phone number"

**Fix:** Use format `0712345678` or `254712345678` (Safaricom only)

### "Payment timeout"

**Fix:**

- Check phone has network
- Ensure M-Pesa is active on the number
- Try again with correct PIN

### "Callback not received"

**Fix:**

- Verify ngrok is running
- Check callback URL in config
- View logs: `aerms/includes/mpesa_callback_log.txt`

### STK Push not received on phone

**Fix:**

- Verify phone number is Safaricom
- Check phone has network coverage
- Ensure M-Pesa app is working
- Try sandbox test numbers

---

## 📊 Check Transaction Status

Run this SQL in phpMyAdmin:

```sql
-- View all transactions
SELECT * FROM tblmpesa_transactions ORDER BY CreatedAt DESC;

-- View pending payments
SELECT
    b.BookingNumber,
    b.TotalAmount,
    b.PaymentStatus,
    m.MpesaReceiptNumber,
    m.TransactionStatus,
    m.PhoneNumber,
    m.CreatedAt
FROM tblbooking b
LEFT JOIN tblmpesa_transactions m ON b.BookingNumber = m.BookingNumber
WHERE b.PaymentStatus = 'Pending'
ORDER BY b.id DESC;
```

---

## 🎯 What You Get

### For Users:

- ✅ Instant payment via M-Pesa
- ✅ No need to visit office
- ✅ Real-time confirmation
- ✅ M-Pesa receipt number
- ✅ Secure transactions

### For Admin:

- ✅ Automated payment tracking
- ✅ Real-time payment updates
- ✅ Transaction history
- ✅ Reconciliation reports
- ✅ Reduced manual work

---

## 📞 Need Help?

### Daraja API Support

- Portal: https://developer.safaricom.co.ke/
- Support: https://developer.safaricom.co.ke/support
- Docs: https://developer.safaricom.co.ke/Documentation

### Check Logs

```bash
# View callback logs
cat aerms/includes/mpesa_callback_log.txt

# Or open in notepad
notepad aerms/includes/mpesa_callback_log.txt
```

---

## 🚀 Going Live (Production)

When ready for production:

1. Apply for Go-Live on Daraja portal
2. Get production credentials
3. Update `mpesa-config.php`:
   ```php
   define('MPESA_ENV', 'live');
   define('MPESA_CONSUMER_KEY', 'YOUR_LIVE_KEY');
   define('MPESA_CONSUMER_SECRET', 'YOUR_LIVE_SECRET');
   define('MPESA_SHORTCODE', 'YOUR_PAYBILL_NUMBER');
   define('MPESA_PASSKEY', 'YOUR_LIVE_PASSKEY');
   define('MPESA_CALLBACK_URL', 'https://yourdomain.com/aerms/includes/mpesa-callback.php');
   ```
4. Test thoroughly with real transactions
5. Monitor for 24 hours

---

## 💡 Pro Tips

1. **Always use ngrok for local testing** - It's free and works perfectly
2. **Test with small amounts** first in production
3. **Monitor callback logs** regularly
4. **Keep credentials secure** - Never commit to Git
5. **Test cancel scenarios** - Users might cancel payments

---

## ✨ Features Included

- [x] Real-time STK Push
- [x] Automatic amount calculation
- [x] Payment status tracking
- [x] Professional UI/UX
- [x] Loading indicators
- [x] Success/failure notifications
- [x] Transaction logging
- [x] Callback handling
- [x] Phone number validation
- [x] Date validation
- [x] Amount formatting

---

**You're all set! 🎉**

Start by running the database migration, then configure your credentials.
Test with a small booking first to ensure everything works.

Good luck! 🚀
