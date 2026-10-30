# HelpingPlate workflow test checklist

This checklist is for testing the local XAMPP installation with separate accounts for each role.

## Before testing
- Start Apache and MySQL in XAMPP.
- Open `http://localhost/Helpingplate/HelpingPlate_Complete_Project/`.
- Confirm `helpingplate` is selected in phpMyAdmin and the 10 project tables exist.
- Keep the SQL database; replacing the PHP project folder does not require importing the SQL again.
- Use a separate browser profile or log out when switching between donor, requester, volunteer and admin accounts.

## 1. Donor → Admin donation approval
1. Register a donor account and log in.
2. Open **Donate Food** and submit a small test donation.
3. Use a preparation time in the past and an expiry time in the future.
4. Confirm it appears in **My Donations** with status **Pending**.
5. Log in as admin and open **Donations**.
6. Approve the test donation.
7. Log back in as donor and confirm the status is **Approved** and a notification is present.

Expected database change: one row in `donations`, initially `pending`, then `approved`.

## 2. Requester → Admin request approval and stock reservation
1. Register a requester account and log in.
2. Open **Available Food** and find the approved test donation.
3. Submit a request for part of its available quantity.
4. Confirm it appears in **My Requests** as **Pending**.
5. Log in as admin, open **Food Requests**, and approve it.
6. Confirm the donation's available quantity decreases by the requested amount.
7. Confirm the request becomes **Approved** and an unassigned delivery is created.
8. If quantity remains, confirm the donation still appears in Available Food and can accept another request.

Expected database changes: one row in `food_requests`, reduced `donations.available_quantity`, and one row in `deliveries`.

## 3. Volunteer application → Admin approval
1. Register a volunteer account and log in.
2. Open **Application**, enter service area and availability, and submit.
3. Log in as admin, open **Volunteers**, and approve the application.
4. Log back in as volunteer and confirm the application status is **Approved**.

## 4. Admin assignment → Volunteer delivery completion
1. Log in as admin and open **Deliveries**.
2. Assign the unassigned delivery to the approved volunteer.
3. Log in as that volunteer and open **My Deliveries**.
4. Open the assigned delivery and click **Accept delivery**.
5. Progress the task through **Picked Up**, **In Transit**, and **Delivered**.
6. Confirm the delivery ends as **Delivered** and the related food request becomes **Completed**.
7. Check donor and requester notifications.

Expected database changes: delivery status moves `unassigned` → `assigned` → `accepted` → `picked_up` → `in_transit` → `delivered`; related request becomes `completed`.

## 5. Contact and feedback administration
1. Submit one test message from **Contact** and one test entry from **Feedback**.
2. Log in as admin and open **Feedback**.
3. Confirm both records appear. Use the action to archive them if desired.

## Important
- Notifications are in-app only; this local project does not send emails or SMS.
- This checklist uses test data. Remove test records in phpMyAdmin only after you have finished verifying the workflow.
