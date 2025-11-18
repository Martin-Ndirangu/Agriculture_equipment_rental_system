# How to Get M-Pesa Callback URL - Complete Guide

## 🎯 Understanding Callback URLs

A **Callback URL** is a publicly accessible web address where Safaricom M-Pesa will send payment confirmation after a customer completes or cancels a payment.

**Simple explanation:** When a customer pays via M-Pesa, Safaricom needs to tell your system "Hey, the payment was successful!" - they do this by sending data to your callback URL.

---

## ⚠️ Important Requirements

Your callback URL MUST be:

1. ✅ **HTTPS** (not HTTP) - SSL certificate required for production
2. ✅ **Publicly accessible** - Not localhost, not behind firewall
3. ✅ **Reachable from internet** - Safaricom servers must be able to access it
4. ✅ **Returns 200 OK** - Must respond to POST requests

---

## 🏠 Local Development (Testing)

### Option 1: Using ngrok (Recommended - FREE)

**ngrok** creates a secure tunnel from the internet to your localhost.

#### Step 1: Download ngrok

- Visit: https://ngrok.com/download
- Download for Windows
- Extract the ZIP file to a folder (e.g., `C:\ngrok\`)

#### Step 2: (Optional) Create Free Account

- Sign up at https://ngrok.com/
- Get your authtoken
- Run: `ngrok config add-authtoken YOUR_TOKEN`
  (This removes the 2-hour session limit)

#### Step 3: Start XAMPP

```bash
# Make sure Apache is running on port 80
# Open XAMPP Control Panel and start Apache
```

#### Step 4: Start ngrok Tunnel

Open Command Prompt or Git Bash and run:

```bash
# Navigate to ngrok folder
cd C:\ngrok

# Start tunnel to port 80 (where XAMPP runs)
ngrok http 80
```

#### Step 5: Copy the HTTPS URL

You'll see output like this:

```
ngrok by @inconshreveable

Session Status    online
Account           Your Name (Plan: Free)
Version           2.3.40
Region            United States (us)
Web Interface     http://127.0.0.1:4040
Forwarding        http://abc123.ngrok.io -> http://localhost:80
Forwarding        https://abc123.ngrok.io -> http://localhost:80

Connections       ttl     opn     rt1     rt5     p50     p90
                  0       0       0.00    0.00    0.00    0.00
```

**Copy the HTTPS URL:** `https://abc123.ngrok.io`

#### Step 6: Create Your Callback URL

Append your callback script path to the ngrok URL:

```
https://abc123.ngrok.io/aerms/includes/mpesa-callback.php
```

#### Step 7: Update Configuration

Edit `aerms/includes/mpesa-config.php`:

```php
define('MPESA_CALLBACK_URL', 'https://abc123.ngrok.io/aerms/includes/mpesa-callback.php');
```

#### Step 8: Test the URL

Open your browser and visit:

```
https://abc123.ngrok.io/aerms/includes/mpesa-callback.php
```

You should see a blank page or a simple message (not an error).

#### ⚠️ Important ngrok Notes:

- The URL changes every time you restart ngrok (Free plan)
- Keep ngrok running while testing
- Update the callback URL in config each time you restart ngrok
- Paid plans ($8/month) give you a permanent URL

---

### Option 2: Using Localtunnel (FREE Alternative)

**localtunnel** is another free option, similar to ngrok.

#### Step 1: Install Node.js

- Download from: https://nodejs.org/
- Install it on your computer

#### Step 2: Install localtunnel

Open Command Prompt:

```bash
npm install -g localtunnel
```

#### Step 3: Start Tunnel

```bash
lt --port 80
```

You'll get a URL like:

```
your url is: https://random-word-1234.loca.lt
```

#### Step 4: Set Callback URL

```php
define('MPESA_CALLBACK_URL', 'https://random-word-1234.loca.lt/aerms/includes/mpesa-callback.php');
```

---

### Option 3: Using Serveo (No Installation)

**serveo** requires SSH but no installation.

```bash
ssh -R 80:localhost:80 serveo.net
```

You'll get a URL like: `https://random.serveo.net`

---

## 🌐 Production/Live Environment

### Option 1: Using Your Own Domain (Recommended)

If you have a domain (e.g., myrentals.com) hosted on a server:

#### Your Callback URL will be:

```
https://myrentals.com/aerms/includes/mpesa-callback.php
```

#### Requirements:

1. ✅ SSL Certificate installed (HTTPS)
2. ✅ Domain points to your server
3. ✅ Files uploaded to server
4. ✅ Callback file is accessible

#### Test Your Production URL:

```bash
# Use curl to test
curl -X POST https://myrentals.com/aerms/includes/mpesa-callback.php

# Should return HTTP 200 OK
```

---

### Option 2: Free Hosting Options for Testing

#### InfinityFree (Free Hosting with SSL)

- Website: https://infinityfree.net/
- Features: Free hosting, free SSL
- Your URL: `https://yourusername.epizy.com/aerms/includes/mpesa-callback.php`

#### 000webhost (Free Hosting)

- Website: https://www.000webhost.com/
- Features: Free hosting, free SSL
- Your URL: `https://yoursite.000webhostapp.com/aerms/includes/mpesa-callback.php`

---

## 🔧 Setting Up the Callback URL

### Complete Setup Example:

#### 1. Start ngrok (for local testing):

```bash
# In Command Prompt or Git Bash
cd C:\ngrok
ngrok http 80
```

#### 2. Copy the HTTPS URL:

```
Example: https://abc123.ngrok.io
```

#### 3. Build Complete Callback URL:

```
https://abc123.ngrok.io/aerms/includes/mpesa-callback.php
```

#### 4. Update mpesa-config.php:

```php
define('MPESA_CALLBACK_URL', 'https://abc123.ngrok.io/aerms/includes/mpesa-callback.php');
```

#### 5. Verify the URL Works:

Open in browser:

```
https://abc123.ngrok.io/aerms/includes/mpesa-callback.php
```

You should NOT see:

- ❌ 404 Not Found
- ❌ Connection refused
- ❌ SSL errors

You SHOULD see:

- ✅ Blank page (that's OK!)
- ✅ Or a simple JSON response

---

## 🧪 Testing Your Callback URL

### Test 1: Manual Access

Visit your callback URL in a browser:

```
https://abc123.ngrok.io/aerms/includes/mpesa-callback.php
```

Should load without errors.

### Test 2: Using curl (Command Line)

```bash
curl -X POST https://abc123.ngrok.io/aerms/includes/mpesa-callback.php \
  -H "Content-Type: application/json" \
  -d '{"test": "data"}'
```

Should return HTTP 200.

### Test 3: Check ngrok Web Interface

- Open: http://127.0.0.1:4040
- This shows all requests to your ngrok tunnel
- After a test payment, you should see POST requests from Safaricom here

---

## 📊 Monitoring Callbacks

### View ngrok Traffic:

```
http://127.0.0.1:4040
```

Shows all incoming requests in real-time.

### Check Callback Logs:

```
aerms/includes/mpesa_callback_log.txt
```

All callbacks are automatically logged here.

### View in Browser:

Open the log file in notepad:

```bash
notepad C:\xampp\htdocs\aerms\includes\mpesa_callback_log.txt
```

---

## 🔍 Common Issues & Solutions

### Issue 1: "Callback URL not accessible"

**Solution:**

- ✅ Make sure ngrok is running
- ✅ Check XAMPP Apache is running
- ✅ Verify the URL in browser
- ✅ Check no firewall blocking port 80

### Issue 2: "ngrok URL changes every time"

**Solution:**

- Get ngrok paid plan ($8/month) for permanent URL
- Or use a free hosting service
- Or update config each time you restart ngrok

### Issue 3: "Callback not received"

**Solution:**

- ✅ Check ngrok web interface (http://127.0.0.1:4040)
- ✅ Verify callback URL in mpesa-config.php
- ✅ Check callback log file
- ✅ Ensure URL is HTTPS (for production)

### Issue 4: "localhost in callback URL"

**Solution:**

- ❌ Never use: `http://localhost/aerms/...`
- ✅ Use ngrok URL: `https://abc123.ngrok.io/aerms/...`

### Issue 5: "SSL certificate error"

**Solution:**

- For ngrok: No action needed (ngrok provides SSL)
- For production: Install SSL certificate on your domain

---

## 📝 Configuration Examples

### Example 1: Local Development with ngrok

```php
define('MPESA_CALLBACK_URL', 'https://abc123.ngrok.io/aerms/includes/mpesa-callback.php');
```

### Example 2: Production with Domain

```php
define('MPESA_CALLBACK_URL', 'https://myrentals.com/aerms/includes/mpesa-callback.php');
```

### Example 3: Free Hosting

```php
define('MPESA_CALLBACK_URL', 'https://mysite.epizy.com/aerms/includes/mpesa-callback.php');
```

### Example 4: Subdomain

```php
define('MPESA_CALLBACK_URL', 'https://api.myrentals.com/aerms/includes/mpesa-callback.php');
```

---

## 🎯 Quick Start Checklist

For local testing with ngrok:

- [ ] Download and extract ngrok
- [ ] Start XAMPP Apache
- [ ] Run `ngrok http 80` in command prompt
- [ ] Copy the HTTPS URL (e.g., https://abc123.ngrok.io)
- [ ] Add `/aerms/includes/mpesa-callback.php` to the URL
- [ ] Update `mpesa-config.php` with the complete URL
- [ ] Test the URL in browser (should load without error)
- [ ] Keep ngrok running while testing
- [ ] Monitor callbacks at http://127.0.0.1:4040

---

## 🚀 Production Deployment Checklist

Before going live:

- [ ] Domain name registered and active
- [ ] SSL certificate installed (HTTPS)
- [ ] Files uploaded to production server
- [ ] Callback URL accessible from internet
- [ ] Test callback URL with curl
- [ ] Update mpesa-config.php with production URL
- [ ] Set MPESA_ENV to 'live'
- [ ] Use production credentials
- [ ] Test with small transaction
- [ ] Monitor callbacks for 24 hours

---

## 💡 Pro Tips

### Tip 1: Use ngrok's Web Interface

```
http://127.0.0.1:4040
```

Shows all incoming requests - perfect for debugging!

### Tip 2: Keep ngrok Running

Don't close the ngrok window while testing - it needs to stay open.

### Tip 3: Test Callback First

Before doing a real payment, test that your callback URL is accessible.

### Tip 4: Check Logs

Always check `mpesa_callback_log.txt` to see what data Safaricom sent.

### Tip 5: Use Permanent URL

For serious development, get a permanent ngrok URL or use free hosting.

---

## 📞 Need Help?

### Test Your Setup:

1. Start ngrok
2. Visit your callback URL in browser
3. Should load without errors
4. Check ngrok web interface
5. Try a test payment

### Still Having Issues?

- Check XAMPP Apache is running
- Verify file path is correct
- Check firewall settings
- Review callback log file
- Test with curl command

---

## 📋 Summary

**What is Callback URL?**
The web address where M-Pesa sends payment confirmation.

**Format:**

```
https://[your-domain]/aerms/includes/mpesa-callback.php
```

**For Testing:**
Use ngrok to create a public URL for your localhost:

```bash
ngrok http 80
# Then use: https://[random].ngrok.io/aerms/includes/mpesa-callback.php
```

**For Production:**
Use your actual domain with SSL:

```
https://yourdomain.com/aerms/includes/mpesa-callback.php
```

---

## 🎬 Video Tutorial Links

- ngrok Tutorial: https://www.youtube.com/results?search_query=ngrok+tutorial
- Daraja API Setup: https://www.youtube.com/results?search_query=safaricom+daraja+api

---

**You're Ready!** 🎉

Follow the ngrok steps above, update your config, and start testing M-Pesa payments!

---

_Last Updated: November 10, 2025_
_Works with: Windows, Mac, Linux_
_Compatible with: XAMPP, WAMP, LAMP, MAMP_
