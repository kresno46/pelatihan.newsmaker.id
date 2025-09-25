# Manual Testing Instructions for Dynamic Branch Sorting Feature in Quiz Report

## Overview
This document provides step-by-step instructions to manually test the dynamic branch sorting feature implemented in the quiz report page.

## Prerequisites
- You must have admin access to the application.
- The application should be running locally or on a test server.
- You should have some quiz sessions with users assigned to different companies and branches (cabang).

## Test Steps

### 1. Login
- Open the application login page.
- Login with an admin account.

### 2. Navigate to Quiz Report
- Go to the Post Test section.
- Select a quiz session.
- Click on the "Report" link to open the quiz report page.

### 3. Verify Company Filter
- Locate the "Filter Perusahaan" dropdown.
- Select a company from the dropdown.
- Observe the "Urutkan" (Sort) dropdown.

### 4. Verify Dynamic Branch Sorting Options
- When a company is selected, the "Urutkan" dropdown should update to show branch names (cabang) related to the selected company.
- The branch names should appear as options in the sort dropdown.
- When no company is selected, the sort dropdown should show default sorting options.

### 5. Test Sorting by Branch
- Select a branch from the "Urutkan" dropdown.
- Click the "Terapkan" (Apply) button.
- Verify that the report table filters and sorts results by the selected branch.

### 6. Test Reset Functionality
- Click the "Reset" button.
- Verify that all filters and sorting options reset to default.

### 7. Test Export CSV
- Apply filters and sorting.
- Click the "Export" button to download the CSV.
- Open the CSV and verify that the data respects the applied filters and sorting.

### 8. Edge Cases
- Select a company with no branches and verify the sort dropdown behavior.
- Select invalid or unexpected values if possible and verify the system handles gracefully.

## Expected Results
- The sort dropdown dynamically updates based on the selected company.
- Branch names appear correctly as sort options.
- Sorting and filtering work correctly on the report page and in the exported CSV.
- Reset button clears filters and sorting.
- No errors or unexpected behavior occur during the tests.

## Notes
- If any issues are found, please document the steps to reproduce and the observed behavior.
- Provide screenshots if possible.

---

Please follow these instructions and let me know if you encounter any issues or need further assistance.
