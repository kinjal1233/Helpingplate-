# HelpingPlate — Full-stack local project

HelpingPlate is a PHP 8+ / MySQL food-sharing application using the original project logo, public-page styling and green/orange brand palette.

## Requirements
- Windows 10/11
- XAMPP with Apache, MySQL and PHP 8+
- VS Code (or another code editor)
- A browser

## Install on XAMPP
1. This build matches the installation path `C:\xampp\htdocs\Helpingplate\HelpingPlate_Complete_Project`. Its `BASE_URL` is `/Helpingplate/HelpingPlate_Complete_Project`. If you move the project, update `BASE_URL` in `config/app.php` to match the new URL path.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin/`.
4. Choose **Import**, select `C:\xampp\htdocs\Helpingplate\HelpingPlate_Complete_Project\database\helpingplate.sql`, then click **Import/Go**. The SQL uses `CREATE TABLE IF NOT EXISTS`; it does not drop existing tables.
5. Check `backend/config/db.php`. Default XAMPP credentials are host `127.0.0.1`, database `helpingplate`, user `root`, blank password. Change them if your MySQL setup differs.
6. Visit `http://localhost/Helpingplate/HelpingPlate_Complete_Project/setup-admin.php` and create the first admin with a strong password (12+ characters).
7. **Delete `setup-admin.php` after creating the first admin.** This setup page disables itself when an admin account exists, but removing it is still recommended.
8. Open `http://localhost/Helpingplate/HelpingPlate_Complete_Project/`.
9. Register donor, requester and volunteer accounts through the public **Register** page. The volunteer application begins pending and must be approved by an admin.

## Main workflow
1. Donor registers, signs in, and submits a food donation. New donations are `pending`.
2. Admin reviews and approves or rejects the donation.
3. Requester signs in, browses non-expired approved food and submits a request.
4. Admin approves/rejects requests. Approval reserves the requested quantity in a database transaction and creates an unassigned delivery. Any remaining quantity stays available for further requests until it reaches zero.
5. Admin approves volunteer applications and may assign an approved volunteer to an unassigned delivery.
6. The assigned volunteer accepts the task, then progresses it through `picked_up` → `in_transit` → `delivered`. Approved volunteers may also claim unassigned tasks directly.
7. Completing a delivery marks the associated food request completed. In-app notifications are generated for relevant actions.

## Roles and pages
- Public: Home, About, How It Works, Get Started, Contact, Feedback, Volunteer, Login and Register.
- Donor: dashboard, add donation, donation history/details, profile, notifications.
- Requester: dashboard, available food/details, submit request, request history, profile, notifications.
- Volunteer: dashboard, application, available deliveries, my deliveries/details, profile, notifications.
- Admin: dashboard, users/donors/requesters, donations, food requests, volunteer applications, deliveries, feedback, reports, editable platform settings, profile.

## Uploads
Donation photos accept JPG, PNG and WEBP, maximum 5 MB. Files are stored under `uploads/donations/` with generated filenames. Keep the `uploads` directory writable by Apache. Do not allow PHP execution in uploads on a public server; this project is intended for local XAMPP demonstration unless deployed with additional production hardening.

## Admin platform settings
- Open Admin Portal → Settings to update the support email, phone, contact address and default city.
- The administrator can enable or disable new public registrations. These settings are stored in the `app_settings` table. The application creates the settings table automatically for an existing database.

## Password reset email setup
- The Login page now links to a secure Forgot Password flow. Reset tokens are random, stored only as SHA-256 hashes, expire after 60 minutes and can be used once.
- The feature sends a reset link using PHP `mail()`. On a hosted PHP server, configure an outgoing mail transport. On Windows/XAMPP, configure the `sendmail` section in `php.ini` (usually through an SMTP account and app password), restart Apache, and set `HP_MAIL_FROM` in `config/app.php` to a real sender address. A default `.local` sender is not a deliverable email address.
- The `password_resets` table is created automatically when the forgot/reset page is opened; it is also included in the SQL file for fresh installations.

## Security notes
- Passwords are stored using PHP `password_hash()` and checked with `password_verify()`.
- State-changing forms use CSRF tokens.
- Queries use PDO prepared statements.
- Role guards protect portal pages.
- Donation approval/request reservation use database transactions and row locking.
- Contact messages and feedback records are stored and displayed in the admin review page.

## Important limitations / production follow-up
- This is a local demo-ready implementation, not a production deployment. Configure HTTPS, secrets, backup, mail delivery, rate limits, privacy policy, retention policy and server-level upload restrictions before public hosting.
- Password reset uses email and therefore requires working PHP mail/SMTP configuration. Other notifications are in-app only.
- The contact form stores messages but does not email them.
- No live map, GPS tracking or payment processing is included.
- Volunteers are assigned by an administrator; pickup/drop-off completion is manually updated by the volunteer.
- Public volunteer applications can be submitted as an expression of interest. When the person later registers a volunteer account with the same email, the public application is linked to that account so it can be reviewed in the volunteer portal.

## Troubleshooting
- If a database error appears, confirm MySQL is running and credentials in `backend/config/db.php` are correct.
- If the project folder changes, update `BASE_URL` in `config/app.php` to match the URL path exactly.
- Use Apache through `http://localhost/Helpingplate/HelpingPlate_Complete_Project/`. Opening PHP files directly from File Explorer will not run PHP.
- If image uploads fail, ensure `uploads/donations/` is writable and PHP `file_uploads` is enabled.

## Logo and brand styling

The original logo artwork is kept at `images/logo.png`. Shared logo sizing, the stacked
`Helping` / `Plate` wordmark (purple `#551A99` and green `#174D3A`), and consistent
footer/portal logo presentation are controlled from `css/branding.css`. Edit this one
stylesheet to update branding consistently across the public pages, registration/login
pages, and donor/requester/volunteer/admin portals.
