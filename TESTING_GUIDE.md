# Quick Test Guide - UA-FMS New Features

## Start the Server
Open a new terminal and run:
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Then visit: http://127.0.0.1:8000

## Test Accounts
| Role | Email | Password |
|------|-------|----------|
| Admin | adminA@example.com | password123 |
| College Staff | college@ccis.com | password123 |
| Org Staff | orgA@example.com | password123 |

---

## ✅ Feature 1: Dashboard Charts

### Test Admin Dashboard
1. Login as: `adminA@example.com` / `password123`
2. Go to: http://127.0.0.1:8000/admin/dashboard
3. **Expected**: See two charts:
   - Pie chart for "Facilities Requests Overview"
   - Bar chart for "Repair & Maintenance Overview"

### Test College Dashboard
1. Login as: `college@ccis.com` / `password123`
2. Go to: http://127.0.0.1:8000/college/dashboard
3. **Expected**: See two charts showing YOUR requests only

### Test Org Dashboard
1. Login as: `orgA@example.com` / `password123`
2. Go to: http://127.0.0.1:8000/org/dashboard
3. **Expected**: See two charts showing YOUR requests only

---

## ✅ Feature 2: Org Staff Maintenance Tickets

### Create a Repair Request (Org)
1. Login as: `orgA@example.com` / `password123`
2. Click "Maintenance" → "New repair request" in sidebar
3. OR visit: http://127.0.0.1:8000/org/maintenance-requests/create
4. Fill in:
   - Facility: Choose any
   - Subject: "Broken chair"
   - Message: "The chair in the office is broken"
5. Click "Send request"
6. **Expected**: Success message with tracking code (e.g., RM-20261008-ABC123)

### View Your Repair Requests (Org)
1. Click "My repair requests" in sidebar
2. OR visit: http://127.0.0.1:8000/org/maintenance-requests
3. **Expected**: See list of your tickets with tracking codes

### Admin Views Org Repair Request
1. Logout, login as: `adminA@example.com` / `password123`
2. Go to: http://127.0.0.1:8000/admin/maintenance-tickets
3. **Expected**: See the ticket from orgA in the list

---

## ✅ Feature 3: Payment Upload & Verification

### Upload Payment Receipt (College)
1. Login as: `college@ccis.com` / `password123`
2. Go to: http://127.0.0.1:8000/college/requests/facilities/create
3. Fill in the form:
   - Date of Activity: (7 days from today)
   - Start Time: 09:00
   - End Time: 12:00
   - Venue: Choose any facility
   - Purpose: "Test event with payment"
4. Scroll to "Payment Receipt (Optional)"
5. Upload any JPG, PNG, or PDF file (max 5MB)
6. Click "Submit Request"
7. **Expected**: Request submitted successfully

### Verify Payment (Admin)
1. Logout, login as: `adminA@example.com` / `password123`
2. Go to: http://127.0.0.1:8000/admin/forms/facilities
3. Find the request you just created (Status: "Pending Payment")
4. Click "View" or visit the submission detail page
5. **Expected**: See "Payment Information" section with:
   - Payment Status: "Awaiting Verification"
   - Link to "View Attachment"
6. Click "View Attachment" to see the uploaded file
7. Click "Verify Payment" button
8. **Expected**: Success message, status changes to "Verified"

### Approve After Payment Verification
1. Still on the same page as admin
2. **Expected**: Now you can see "Approve" and "Disapprove" buttons
3. Click "Approve"
4. **Expected**: Request approved successfully

### Test Payment Required Validation
1. Try to approve a request with "Pending Payment" status WITHOUT verifying payment first
2. **Expected**: Error message: "Please verify the payment attachment before approving this request."

---

## ✅ Feature 4: Repair Form PDF Generation (Fixed)

### Generate Repair Form PDF
1. Login as: `adminA@example.com` / `password123`
2. Go to: http://127.0.0.1:8000/forms/repair
3. Fill in the form with test data
4. Click "Generate PDF Form"
5. **Expected**: PDF downloads successfully (no template error)

---

## Common Issues & Solutions

### Issue: Charts not showing
**Solution**: Make sure Chart.js loaded. Check browser console for errors.

### Issue: Payment upload not working
**Solution**: 
1. Check `storage/app/public/payment_attachments` exists
2. Run: `php artisan storage:link`
3. Check file permissions

### Issue: Template error on repair form
**Solution**: Already fixed! Templates are in `storage/app/templates/`

### Issue: Routes not found
**Solution**: 
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

---

## Database Check Commands

### View payment-enabled requests:
```bash
mysql -u root facility_management -e "SELECT id, status, payment_status, payment_attachment FROM form_submissions WHERE payment_attachment IS NOT NULL;"
```

### View all maintenance tickets:
```bash
mysql -u root facility_management -e "SELECT id, ticket_code, status, requester_id FROM maintenance_tickets ORDER BY created_at DESC LIMIT 10;"
```

### Check dashboard stats:
```bash
mysql -u root facility_management -e "SELECT status, COUNT(*) as count FROM form_submissions WHERE type='facilities_utilization' GROUP BY status;"
```

---

## Summary of URLs

| Feature | URL |
|---------|-----|
| Admin Dashboard | http://127.0.0.1:8000/admin/dashboard |
| College Dashboard | http://127.0.0.1:8000/college/dashboard |
| Org Dashboard | http://127.0.0.1:8000/org/dashboard |
| Org Maintenance (Create) | http://127.0.0.1:8000/org/maintenance-requests/create |
| Org Maintenance (List) | http://127.0.0.1:8000/org/maintenance-requests |
| Admin Approve Requests | http://127.0.0.1:8000/admin/forms/facilities |
| Admin Maintenance Tickets | http://127.0.0.1:8000/admin/maintenance-tickets |
| College New Request | http://127.0.0.1:8000/college/requests/facilities/create |
| Org New Request | http://127.0.0.1:8000/org/requests/facilities/create |
| Repair Form (Admin) | http://127.0.0.1:8000/forms/repair |

---

**All features are now implemented and ready to test!**
