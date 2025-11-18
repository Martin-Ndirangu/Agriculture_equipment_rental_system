# M-Pesa Callback URL - Visual Guide

## 🎯 What is a Callback URL?

```
┌─────────────────────────────────────────────────────────────┐
│                     CALLBACK URL FLOW                        │
└─────────────────────────────────────────────────────────────┘

1. Customer Books Product
   └─→ Your Website (localhost/aerms)

2. System Sends STK Push
   └─→ Safaricom M-Pesa API

3. Customer Receives STK Push
   └─→ Customer's Phone

4. Customer Enters PIN
   └─→ M-Pesa Processes Payment

5. M-Pesa Sends Confirmation ⭐ THIS IS WHERE CALLBACK URL IS USED
   └─→ YOUR CALLBACK URL (needs to be publicly accessible)

6. Your System Updates Status
   └─→ Database Updated

7. Customer Sees Confirmation
   └─→ Success Message
```

---

## 🏠 Local Development Problem

```
❌ PROBLEM:
┌──────────────┐         ┌──────────────┐
│   Safaricom  │──X──→   │  localhost   │
│   (Internet) │         │  (Your PC)   │
└──────────────┘         └──────────────┘

Safaricom CANNOT reach http://localhost/aerms/...
Your computer is not accessible from the internet!
```

## ✅ Solution: ngrok Creates a Tunnel

```
✅ SOLUTION WITH ngrok:
┌──────────────┐         ┌──────────┐         ┌──────────────┐
│   Safaricom  │───→     │  ngrok   │───→     │  localhost   │
│   (Internet) │  HTTPS  │ (Tunnel) │  HTTP   │  (Your PC)   │
└──────────────┘         └──────────┘         └──────────────┘
    Sends to:           Creates tunnel         Your XAMPP
    abc123.ngrok.io     to your computer       receives it!
```

---

## 📋 Step-by-Step Visual Setup

### STEP 1: Download ngrok

```
🌐 Visit: https://ngrok.com/download
📥 Download for Windows
📂 Extract to: C:\ngrok\
```

### STEP 2: Start XAMPP

```
┌─────────────────────────────┐
│    XAMPP Control Panel      │
├─────────────────────────────┤
│ Apache  [Start]  [Stop]     │ ← Click START
│ MySQL   [Start]  [Stop]     │ ← Click START
└─────────────────────────────┘
```

### STEP 3: Run ngrok

```
💻 Command Prompt:
C:\> cd ngrok
C:\ngrok> ngrok http 80

┌─────────────────────────────────────────┐
│ ngrok is running!                       │
│                                         │
│ https://abc123.ngrok.io → localhost:80  │ ← COPY THIS URL
└─────────────────────────────────────────┘
```

### STEP 4: Build Callback URL

```
Take ngrok URL:  https://abc123.ngrok.io
Add your path:   /aerms/includes/mpesa-callback.php
                 ─────────────────────────────────────
Final URL:       https://abc123.ngrok.io/aerms/includes/mpesa-callback.php
```

### STEP 5: Update Config

```php
// Open: aerms/includes/mpesa-config.php
// Find this line:
define('MPESA_CALLBACK_URL', 'https://yourdomain.com/aerms/includes/mpesa-callback.php');

// Change to:
define('MPESA_CALLBACK_URL', 'https://abc123.ngrok.io/aerms/includes/mpesa-callback.php');
```

### STEP 6: Test It!

```
🌐 Open in browser:
https://abc123.ngrok.io/aerms/includes/mpesa-callback.php

✅ Should show: Blank page or simple message
❌ Should NOT show: 404 error or "cannot connect"
```

---

## 🔍 How to Monitor Callbacks

### ngrok Web Interface

```
🌐 Open: http://127.0.0.1:4040

┌──────────────────────────────────────────────────────┐
│  ngrok - Inspect                                     │
├──────────────────────────────────────────────────────┤
│  POST /aerms/includes/mpesa-callback.php             │
│  Status: 200 OK                                      │
│  From: 196.201.214.xxx (Safaricom)                   │
│  Body: {"Body":{"stkCallback":{...}}}                │
└──────────────────────────────────────────────────────┘

You'll see ALL requests here in real-time! 👀
```

### Callback Log File

```
📁 Location: aerms/includes/mpesa_callback_log.txt

2025-11-10 14:30:22 - Callback Received:
{"Body":{"stkCallback":{"MerchantRequestID":"...",...}}}

2025-11-10 14:30:22 - Processing:
MerchantRequestID: 29115-34620561-1
CheckoutRequestID: ws_CO_191220191020363925
ResultCode: 0
ResultDesc: The service request is processed successfully.
```

---

## 🎬 Real Payment Flow Example

```
┌────────────────────────────────────────────────────────────┐
│  1. Customer Books Tractor (3 days, 1 unit = KSH 1500)    │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  2. Customer Enters Phone: 0712345678                      │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  3. System Sends Request to Safaricom                      │
│     "Send STK Push to 254712345678 for KSH 1500"          │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  4. Customer's Phone Receives:                             │
│     ┌──────────────────────────────┐                      │
│     │  M-PESA                      │                      │
│     │  Pay KSH 1500.00             │                      │
│     │  to AERMS                    │                      │
│     │  Enter PIN: ****             │                      │
│     │  [Confirm]      [Cancel]     │                      │
│     └──────────────────────────────┘                      │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  5. Customer Enters PIN and Confirms                       │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  6. Safaricom Processes Payment                            │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  7. Safaricom Sends Callback to:                          │
│     https://abc123.ngrok.io/aerms/includes/mpesa-callback.php │
│                                                            │
│     Data sent:                                             │
│     - Receipt Number: QLK12ABC34                           │
│     - Amount: 1500                                         │
│     - Status: Success                                      │
│     - Phone: 254712345678                                  │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  8. ngrok Forwards to Your PC                             │
│     (Internet → ngrok tunnel → localhost)                  │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  9. Your Callback Script Processes:                       │
│     - Logs the data                                        │
│     - Updates database                                     │
│     - Marks booking as "Paid"                             │
│     - Saves receipt number                                 │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  10. Customer Sees Success Message:                       │
│      ✓ Payment Successful!                                │
│      Receipt: QLK12ABC34                                   │
│      Booking: 123456789                                    │
└────────────────────────────────────────────────────────────┘
```

---

## ⚡ Quick Command Reference

### Start ngrok:

```bash
cd C:\ngrok
ngrok http 80
```

### Test callback URL:

```bash
curl https://abc123.ngrok.io/aerms/includes/mpesa-callback.php
```

### View logs:

```bash
notepad C:\xampp\htdocs\aerms\includes\mpesa_callback_log.txt
```

### Open ngrok web interface:

```
http://127.0.0.1:4040
```

---

## 🎯 URLs You Need to Know

### 1. Your Local Site:

```
http://localhost/aerms/
```

### 2. ngrok Public URL:

```
https://abc123.ngrok.io
```

### 3. Callback URL (for config):

```
https://abc123.ngrok.io/aerms/includes/mpesa-callback.php
```

### 4. ngrok Web Interface:

```
http://127.0.0.1:4040
```

---

## ✅ Checklist Before Testing

```
□ XAMPP Apache is running
□ XAMPP MySQL is running
□ ngrok is downloaded and extracted
□ ngrok tunnel is running (ngrok http 80)
□ ngrok HTTPS URL copied
□ Callback URL built correctly
□ mpesa-config.php updated with callback URL
□ Callback URL tested in browser (loads without error)
□ ngrok web interface accessible (http://127.0.0.1:4040)
□ Ready to test payment!
```

---

## 🚨 Common Mistakes

### ❌ WRONG:

```php
// Using localhost (won't work!)
define('MPESA_CALLBACK_URL', 'http://localhost/aerms/includes/mpesa-callback.php');

// Using HTTP instead of HTTPS
define('MPESA_CALLBACK_URL', 'http://abc123.ngrok.io/aerms/includes/mpesa-callback.php');

// Missing the path
define('MPESA_CALLBACK_URL', 'https://abc123.ngrok.io');

// Wrong path
define('MPESA_CALLBACK_URL', 'https://abc123.ngrok.io/mpesa-callback.php');
```

### ✅ CORRECT:

```php
// Complete HTTPS URL with correct path
define('MPESA_CALLBACK_URL', 'https://abc123.ngrok.io/aerms/includes/mpesa-callback.php');
```

---

## 🎉 You're Ready!

Follow these steps:

1. ✅ Download ngrok
2. ✅ Start XAMPP
3. ✅ Run: `ngrok http 80`
4. ✅ Copy the HTTPS URL
5. ✅ Update mpesa-config.php
6. ✅ Test the URL in browser
7. ✅ Make a test payment
8. ✅ Monitor ngrok web interface

---

**Need More Help?**
See: `CALLBACK_URL_SETUP_GUIDE.md` for detailed instructions

---

_Visual Guide - November 10, 2025_
