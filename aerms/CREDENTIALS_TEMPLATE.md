# M-Pesa Credentials Configuration Template

## How to Get Your Credentials

1. Visit: https://developer.safaricom.co.ke/
2. Register or login to your account
3. Click on "My Apps" and create a new app
4. Select "Lipa Na M-Pesa Online" API
5. Copy your credentials below

---

## Sandbox Credentials (For Testing)

**Consumer Key:**

```
[Paste your sandbox Consumer Key here]
Example: 1a2b3c4d5e6f7g8h9i0j
```

**Consumer Secret:**

```
[Paste your sandbox Consumer Secret here]
Example: A1B2C3D4E5F6G7H8
```

**Shortcode:**

```
174379
(This is the default sandbox shortcode - don't change)
```

**Passkey:**

```
[Paste your sandbox Passkey here]
Get this from the "Test Credentials" section in Daraja portal
Example: bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919
```

---

## Production Credentials (For Live Environment)

⚠️ **Note:** You need to apply for Go-Live before getting production credentials

**Consumer Key:**

```
[Will be provided after Go-Live approval]
```

**Consumer Secret:**

```
[Will be provided after Go-Live approval]
```

**Shortcode (Paybill/Till Number):**

```
[Your business Paybill or Till Number]
Example: 123456
```

**Passkey:**

```
[Will be provided after Go-Live approval]
```

---

## Callback URL Configuration

### For Testing (Using ngrok):

```
https://[your-ngrok-url].ngrok.io/aerms/includes/mpesa-callback.php

Example: https://abc123.ngrok.io/aerms/includes/mpesa-callback.php
```

### For Production:

```
https://yourdomain.com/aerms/includes/mpesa-callback.php

Example: https://myrentals.com/aerms/includes/mpesa-callback.php
```

---

## Test Phone Numbers (Sandbox Only)

Use these Safaricom numbers for testing in sandbox:

- 254708374149
- 254719789044
- Or your own Safaricom number (if M-Pesa registered)

---

## Configuration Steps

1. Copy this file and save it securely (DO NOT COMMIT TO GIT)
2. Fill in your credentials above
3. Open: `aerms/includes/mpesa-config.php`
4. Replace the placeholders with your actual credentials:

```php
// Update these lines in mpesa-config.php:
define('MPESA_CONSUMER_KEY', 'YOUR_CONSUMER_KEY_HERE');
define('MPESA_CONSUMER_SECRET', 'YOUR_CONSUMER_SECRET_HERE');
define('MPESA_SHORTCODE', 'YOUR_SHORTCODE_HERE');
define('MPESA_PASSKEY', 'YOUR_PASSKEY_HERE');
define('MPESA_CALLBACK_URL', 'YOUR_CALLBACK_URL_HERE');
```

---

## Verification Checklist

After configuration, verify:

- [ ] All credentials are filled in (no placeholders left)
- [ ] Callback URL is publicly accessible (use ngrok for testing)
- [ ] Environment is set correctly (sandbox/live)
- [ ] Credentials match the environment (don't mix sandbox and live)
- [ ] Phone number format is correct for testing

---

## Security Notes

⚠️ **IMPORTANT SECURITY GUIDELINES:**

1. **Never share your credentials** with anyone
2. **Never commit credentials to version control** (Git, SVN, etc.)
3. **Keep this file secure** - don't upload to public servers
4. **Use environment variables** in production
5. **Rotate credentials regularly** for production
6. **Monitor transactions** for suspicious activity

---

## Support

If you need help getting credentials:

- Daraja Portal: https://developer.safaricom.co.ke/
- Support: https://developer.safaricom.co.ke/support
- Documentation: https://developer.safaricom.co.ke/Documentation

---

## Quick Test

After configuration, test with:

1. Amount: 1 KSH
2. Phone: 254708374149 (sandbox test number)
3. Verify STK Push is received
4. Complete payment with test PIN
5. Check if callback is received

---

Last Updated: November 10, 2025
