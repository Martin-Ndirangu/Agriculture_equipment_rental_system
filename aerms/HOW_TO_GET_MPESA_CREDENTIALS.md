# How to Get M-Pesa Daraja API Passkeys and Credentials

## 🎯 Quick Answer

**You CANNOT create passkeys yourself** - they are provided by Safaricom when you register for the Daraja API.

---

## 📋 Step-by-Step Guide to Get M-Pesa Credentials

### Step 1: Create Safaricom Developer Account

1. Go to: **https://developer.safaricom.co.ke/**
2. Click **"Sign Up"** (top right)
3. Fill in your details:
   - Email address
   - Phone number (Safaricom number recommended)
   - Password
   - Accept terms and conditions
4. Check your email for verification link
5. Click the verification link to activate your account
6. Login to the Daraja Portal

---

### Step 2: Create a New App

1. After logging in, click **"My Apps"** in the top menu
2. Click **"Add A New App"** button
3. Fill in the app details:
   - **App Name:** e.g., "Agriculture Equipment Rental"
   - **Description:** Brief description of your system
4. Click **"Create App"**
5. Your app is now created!

---

### Step 3: Add Lipa Na M-Pesa Online API

1. In your app dashboard, scroll down to **"Select API products"**
2. Find **"Lipa Na M-Pesa Online"**
3. Click to select it
4. Click **"Generate Keys"** or **"Add API"**
5. Wait for confirmation (may take a few seconds)

---

### Step 4: Get Your Credentials

After adding the API, you'll see your credentials:

#### A. Consumer Key and Consumer Secret

Located in the **"Keys"** tab of your app:

```
Consumer Key: abc123xyz456...
Consumer Secret: ABC123XYZ456...
```

**Copy these immediately** - The Consumer Secret may not be shown again!

#### B. Shortcode (Business Short Code)

For **Sandbox (Testing)**:

```
Shortcode: 174379
```

This is the default test shortcode - **use this for testing**.

For **Production (Live)**:

- You'll use your actual Paybill or Till Number
- This is your business M-Pesa number

#### C. Passkey (Lipa Na M-Pesa Online Passkey)

This is the tricky one! Here's how to find it:

**For Sandbox:**

1. In your app, go to **"APIs"** tab
2. Click on **"Lipa Na M-Pesa Online"**
3. Look for **"Test Credentials"** section
4. You'll see:
   ```
   Shortcode: 174379
   Passkey: bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919
   ```
   ⚠️ **This is the DEFAULT SANDBOX PASSKEY** - everyone uses this for testing

**For Production:**

- You'll receive this after Go-Live approval
- It's unique to your business
- Safaricom provides it during onboarding

---

## 🧪 Sandbox Test Credentials (Ready to Use)

For immediate testing, use these **public sandbox credentials**:

```php
// These are DEFAULT SANDBOX credentials - safe to use for testing

define('MPESA_ENV', 'sandbox');

define('MPESA_CONSUMER_KEY', 'YOUR_CONSUMER_KEY_FROM_DARAJA');
define('MPESA_CONSUMER_SECRET', 'YOUR_CONSUMER_SECRET_FROM_DARAJA');

// These are standard sandbox values:
define('MPESA_SHORTCODE', '174379');
define('MPESA_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919');
```

**What you need to replace:**

- ✅ `MPESA_CONSUMER_KEY` - Get from your Daraja app
- ✅ `MPESA_CONSUMER_SECRET` - Get from your Daraja app
- ❌ `MPESA_SHORTCODE` - Keep as 174379 for sandbox
- ❌ `MPESA_PASSKEY` - Keep the default value for sandbox

---

## 📱 Test Phone Numbers

For sandbox testing, use these numbers:

```
254708374149 (Most reliable)
254719789044
254790564853
```

Or use your own Safaricom number (if M-Pesa registered)

---

## 🎬 Visual Guide - Where to Find Each Credential

### Consumer Key & Secret:

```
Daraja Portal → My Apps → [Your App Name] → Keys Tab
You'll see:
┌─────────────────────────────────────┐
│ Consumer Key: [Copy]                │
│ Consumer Secret: [Copy]             │
└─────────────────────────────────────┘
```

### Passkey:

```
Daraja Portal → My Apps → [Your App Name] → APIs Tab
→ Lipa Na M-Pesa Online → Test Credentials
You'll see:
┌─────────────────────────────────────┐
│ Shortcode: 174379                   │
│ Passkey: bfb279f9aa9bdbcf...        │
└─────────────────────────────────────┘
```

---

## ⚡ Quick Configuration Example

Here's a complete working example for **SANDBOX**:

```php
<?php
// Sandbox Environment
define('MPESA_ENV', 'sandbox');

// YOUR CREDENTIALS (from Daraja Portal)
define('MPESA_CONSUMER_KEY', 'nF4OwB2XiUqGHFE6HvGPZHPqvhE9qBGB');
define('MPESA_CONSUMER_SECRET', 'VJjqoP9Qb9dXfRqJ');

// STANDARD SANDBOX VALUES (don't change)
define('MPESA_SHORTCODE', '174379');
define('MPESA_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919');

// Callback URL (use ngrok for testing)
define('MPESA_CALLBACK_URL', 'https://abc123.ngrok.io/aerms/includes/mpesa-callback.php');
?>
```

---

## 🚀 Complete Setup Checklist

### 1. Get Credentials from Daraja:

- [ ] Register on developer.safaricom.co.ke
- [ ] Create a new app
- [ ] Add "Lipa Na M-Pesa Online" API
- [ ] Copy Consumer Key
- [ ] Copy Consumer Secret
- [ ] Note the Passkey (from Test Credentials section)

### 2. Configure Your System:

- [ ] Open `aerms/includes/mpesa-config.php`
- [ ] Replace `YOUR_CONSUMER_KEY_HERE` with your actual key
- [ ] Replace `YOUR_CONSUMER_SECRET_HERE` with your actual secret
- [ ] Keep shortcode as `174379` for sandbox
- [ ] Keep the default passkey for sandbox
- [ ] Set up callback URL (use ngrok)

### 3. Test:

- [ ] Book a product
- [ ] Enter test phone: 254708374149
- [ ] Check STK Push is received
- [ ] Complete payment
- [ ] Verify callback is logged

---

## 🔐 Production (Going Live)

To get production credentials:

### Step 1: Apply for Go-Live

1. Login to Daraja Portal
2. Go to your app
3. Click **"Go Live"** or **"Request Production Access"**
4. Fill in the Go-Live form:
   - Business details
   - Paybill/Till number
   - Expected transaction volume
   - Use case description
   - KYC documents

### Step 2: Wait for Approval

- Safaricom reviews (1-5 business days)
- You may be contacted for more information
- Once approved, you'll receive production credentials

### Step 3: Get Production Credentials

After approval:

- New Consumer Key and Secret (for production)
- Your business Shortcode (Paybill/Till number)
- Production Passkey (unique to your business)

### Step 4: Update Configuration

```php
define('MPESA_ENV', 'live');
define('MPESA_CONSUMER_KEY', 'your_production_consumer_key');
define('MPESA_CONSUMER_SECRET', 'your_production_consumer_secret');
define('MPESA_SHORTCODE', 'your_paybill_number');
define('MPESA_PASSKEY', 'your_production_passkey');
define('MPESA_CALLBACK_URL', 'https://yourdomain.com/aerms/includes/mpesa-callback.php');
```

---

## 🆘 Troubleshooting

### "I can't find the Passkey!"

**Solution:**

1. Go to your app in Daraja Portal
2. Click "APIs" tab
3. Click "Lipa Na M-Pesa Online"
4. Look for "Test Credentials" section
5. The passkey is shown there

### "My Consumer Secret disappeared!"

**Solution:**

- Safaricom only shows it once for security
- You can regenerate keys:
  1. Go to your app → Keys tab
  2. Click "Regenerate" or "Generate New Keys"
  3. Copy the new credentials immediately
  4. Update your config file

### "Access Token request failed"

**Causes:**

- Wrong Consumer Key/Secret
- Keys haven't been activated yet (wait 5 minutes after creating app)
- Internet connection issue
- Using production keys in sandbox mode (or vice versa)

---

## 📞 Support

**Safaricom Developer Support:**

- Email: apisupport@safaricom.co.ke
- Portal: https://developer.safaricom.co.ke/support
- Phone: 0722 000 000 (Safaricom customer care)

**Developer Community:**

- Safaricom Developer Forum
- Stack Overflow (tag: daraja-api)

---

## 💡 Pro Tips

1. **Save credentials securely** - Use a password manager
2. **Don't share Consumer Secret** - Treat it like a password
3. **Test in sandbox first** - Always test before going live
4. **Keep credentials out of Git** - Use .gitignore
5. **Use environment variables** - For production deployments
6. **Monitor your usage** - Check Daraja Portal regularly
7. **Renew keys periodically** - For security

---

## 📝 Summary

**What you need to get:**

1. ✅ Consumer Key (from Daraja Portal)
2. ✅ Consumer Secret (from Daraja Portal)

**What's already provided:**

1. ✅ Sandbox Shortcode: `174379`
2. ✅ Sandbox Passkey: `bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919`

**Total time to get started:** 10-15 minutes

---

## 🎉 Ready to Go!

Once you have your Consumer Key and Secret:

1. Update `mpesa-config.php`
2. Set up ngrok for callback URL
3. Start testing!

The sandbox passkey is public and shared by all developers for testing - that's perfectly fine and intended by Safaricom.

---

_Last Updated: November 10, 2025_
_For Safaricom Daraja API v1_
