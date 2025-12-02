# Agriculture Equipment Rental System (AERS)

AERMS is a PHP/MySQL web application for renting agricultural equipment. This repository contains the frontend, admin area and an M-Pesa (Daraja) STK Push integration for payments.

**Contents**

- Overview
- Features
- Prerequisites
- Installation (Windows / XAMPP)
- Database setup & migrations
- M-Pesa (Daraja) configuration and testing
- Running and testing the app
- Troubleshooting
- Files added for M-Pesa integration

---

## Overview

This project is built with plain PHP and MySQL and intended to run on a local XAMPP stack or a LAMP server. The app supports product listings, bookings, an admin dashboard, and M-Pesa STK Push payment workflow (sandbox and production-ready code included).

## Features

- Product listing and single-product view
- Booking flow with date selection and quantity
- M-Pesa STK Push integration to charge customers at booking time
- Callback endpoint to receive M-Pesa confirmation and update bookings
- Admin pages to view bookings and M-Pesa transactions

## Prerequisites

- Windows (instructions below use XAMPP on Windows)
- XAMPP (Apache + PHP + MySQL)
- PHP 7.4+ or PHP 8.x recommended
- Composer (optional, not required for this codebase)
- ngrok (for local HTTPS webhook testing with M-Pesa sandbox)
- A browser with DevTools for debugging

## Installation (Windows + XAMPP)

1. Copy the project into your XAMPP `htdocs` folder. The expected path used in this README is `C:\xampp\htdocs\aerms`.

2. Start XAMPP and enable Apache and MySQL.

3. Database setup

   - If you already have the project database dump, import it into MySQL (phpMyAdmin is the easiest option).
   - To import the M-Pesa DB schema additions included with this integration, use phpMyAdmin or the MySQL CLI.

   Using phpMyAdmin:

   - Open `http://localhost/phpmyadmin`
   - Create or select the project database (for example `aermsdb`).
   - Use the Import tab to upload `C:\xampp\htdocs\aerms\mpesa_database_schema.sql`.

   Using MySQL CLI (Git Bash):

   ```bash
   /c/xampp/mysql/bin/mysql -u root aermsdb < C:\xampp\htdocs\aerms\mpesa_database_schema.sql
   ```

   Replace `aermsdb` with your database name if different.

4. Configure the database connection

   - Edit `C:\xampp\htdocs\aerms\includes\dbconnection.php` and update the DB host, username, password and database name to match your environment.

5. Configure M-Pesa (Daraja) credentials

   - Edit `C:\xampp\htdocs\aerms\includes\mpesa-config.php` and update the following constants with your credentials:
     - `MPESA_CONSUMER_KEY`
     - `MPESA_CONSUMER_SECRET`
     - `MPESA_SHORTCODE` (your till/shortcode)
     - `MPESA_PASSKEY` (Lipa Na M-Pesa passkey)
     - `MPESA_CALLBACK_URL` (public HTTPS URL for Daraja to call)
     - `MPESA_ENV` — set to `sandbox` or `production`

   Note: For local development you must give Daraja a public HTTPS callback URL (see ngrok section below).

6. Ensure file permissions and PHP configuration allow writing to the `includes/mpesa_callback_log.txt` file (for webhook logging) and that Apache has access to the project files.

## Exposing local callback URL (ngrok)

M-Pesa sandbox requires a public HTTPS callback URL. Use ngrok to expose your local site.

1. Download and install ngrok and authenticate it if you haven't already: `ngrok config add-authtoken <your_authtoken>`.
2. Run ngrok to forward HTTP port 80:

```bash
ngrok http 80
```

3. Copy the generated HTTPS URL (for example `https://abcd-12-34-56.ngrok.io`) and set `MPESA_CALLBACK_URL` in `includes/mpesa-config.php` to the route: `https://<your-ngrok-host>/aerms/includes/mpesa-callback.php` (be precise, no leading/trailing spaces).

## M-Pesa (Daraja) sandbox setup & testing

1. Register for a Daraja sandbox account (Safaricom Developer portal) and obtain the consumer key and consumer secret.
2. Generate or obtain your Lipa Na M-Pesa passkey for STK Push (sandbox or production as required).
3. Update `includes/mpesa-config.php` with the values.
4. Ensure `MPESA_CALLBACK_URL` is reachable over HTTPS (ngrok or production HTTPS domain).

### How to trigger an STK Push (test flow)

1. Go to the booking page `http://localhost/aerms/book-products.php` in your browser.
2. Complete the booking fields and put a valid MPESA phone number (format like `2547XXXXXXXX` or `07XXXXXXXX` — the code includes phone formatting helpers).
3. Submit the booking — the site will create a pending booking and initiate the STK Push.
4. On the phone used for testing, you should receive the M-Pesa prompt to enter your PIN for payment.
5. The Daraja webhook will call `includes/mpesa-callback.php` on success; callback requests are logged to `includes/mpesa_callback_log.txt`.
6. Verify the database tables:
   - `tblmpesa_transactions` — contains the transaction records and statuses.
   - `tblbooking` — will be updated with `PaymentStatus='Paid'` and receipt info after a successful callback.
7. Admin transactions: login to the admin area and view `admin/mpesa-transactions.php`.

## Files added or updated for M-Pesa integration

- `includes/mpesa-config.php` — Daraja config & helper functions
- `includes/mpesa-stk-push.php` — endpoint to initiate STK Push
- `includes/mpesa-callback.php` — webhook to receive Daraja payment result
- `includes/mpesa-status.php` — polling endpoint to check booking payment status
- `mpesa_database_schema.sql` — SQL to create `tblmpesa_transactions` and alter `tblbooking`
- `admin/mpesa-transactions.php` — admin UI for viewing transactions
- `includes/mpesa_callback_log.txt` — webhook logging (created at runtime)

(These files are inside `C:\xampp\htdocs\aerms\includes` and `C:\xampp\htdocs\aerms\admin`.)

## Quick troubleshooting

- "mysql: command not found" when running CLI import

  - Use the full XAMPP MySQL path: `/c/xampp/mysql/bin/mysql -u root aermsdb < C:\path\to\mpesa_database_schema.sql`
  - Or import the SQL via phpMyAdmin (recommended for Windows).

- Invalid Callback URL / Bad Request

  - Ensure `MPESA_CALLBACK_URL` has no leading/trailing spaces and is an HTTPS URL reachable by Daraja.
  - Use ngrok for local testing and paste the full HTTPS ngrok URL in `mpesa-config.php`.

- STK Push fails or returns an error

  - Check `includes/mpesa_callback_log.txt` for callback payloads.
  - Check for correct `MPESA_CONSUMER_KEY` and `MPESA_CONSUMER_SECRET`.
  - Ensure the passkey and shortcode are correct for sandbox vs production.

- Slider background images not displaying on homepage
  - The stylesheet contains a global rule that can prevent inline background images: open `C:\xampp\htdocs\aerms\css\style.css` and search for
    ```css
    .slider-item {
      background-image: none !important;
    }
    ```
    Remove or modify that rule to allow per-slide backgrounds, then hard-refresh the browser cache (Ctrl+Shift+R).

## Testing & Verification

- Start Apache and MySQL via XAMPP.
- Open `http://localhost/aerms/` and navigate to the booking flow.
- Use ngrok and the Daraja sandbox credentials to perform an STK Push; verify callback logs and DB updates.

## Next steps and notes

- Replace sandbox credentials with production credentials only after thorough testing.
- Secure access to the admin area and consider input validation/hardening for production.

## Support

If you want, I can:

- Run the SQL migration commands for you (if you grant terminal access to the correct environment).
- Walk through obtaining Daraja credentials step-by-step.
- Remove the problematic CSS rule to fix the slider backgrounds.

---

License: check repository license (if none provided, add one before production use).
