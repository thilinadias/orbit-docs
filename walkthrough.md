# Orbit-Docs Deployment Success Walkthrough

We have successfully resolved all deployment issues for the Orbit-Docs application. This document summarizes the critical fixes applied to get the application running.

## 1. Idempotent Database Migrations (Fix for "Table Already Exists")

The primary blocker was a migration crash where `php artisan migrate` would fail because it tried to create tables that already existed (likely from a previous partial run).

**Solution:**
We modified `database/migrations/2024_02_12_000000_create_orbit_tables.php` to be **idempotent**. Every `Schema::create` call is now wrapped in a check:

```php
if (!Schema::hasTable('table_name')) {
    Schema::create('table_name', function (Blueprint $table) {
        // ...
    });
}
```

This allows the migration to safely run multiple times without crashing, skipping existing tables and creating only missing ones.

## 2. Bypassing the Installer Timeout

The web-based installer was timing out (504 Gateway Timeout) due to the long duration of the migration process (~60 seconds).

**Solution:**
Since the migrations were successfully run via the CLI (`docker-compose exec app php artisan migrate`), we bypassed the installer entirely by manually creating the "installed" flag file:

```bash
docker-compose exec app touch storage/app/installed
```

This tricked the application into believing the installation wizard was complete, allowing access to the login page.

## 3. Fixing Dashboard 404 Error

After logging in, the dashboard threw a 404 error when trying to load or save notes.

**Cause:**
The form action in `resources/views/dashboard.blade.php` was using the organization's **ID** (`$organization->id`) in the route, but `routes/web.php` defined the route to use the **SLUG** (`{organization:slug}`).

**Fix:**
We updated the Blade template to use the correct parameter:

```diff
- <form action="{{ route('organizations.notes.update', $organization->id) }}" ...>
+ <form action="{{ route('organizations.notes.update', $organization->slug) }}" ...>
```

We also rebuilt the Docker container to ensure this code change was applied, as the volume mount for local development was commented out in `docker-compose.yml`.

## 4. Default Credentials

The default admin user created by the seeder is:

*   **Email:** `admin@orbitdocs.com`
*   **Password:** `password`

## 5. Document Management Enhancements

We resolved the "slug on string" error during document editing and added missing features.

**Fixes & Features:**
1.  **Crash Fix:** Updated `DocumentController` to correctly handle the `$organization` object, preventing the crash when editing documents.
2.  **Status Field:** Added an `approval_status` column to the database (Draft, Pending Review, Published).
    *   **Migration:** `2026_02_17_183000_add_approval_status_to_documents_table.php`
3.  **Tags Support:** Implemented tagging functionality in the edit form, allowing users to add comma-separated tags (e.g., "guide, network").
4.  **UI Updates:** Modified `edit.blade.php` to include the new inputs and `show.blade.php` to display the status badge and tags.
5.  **Conflict Resolution:** Removed a legacy `tags` text column from the `documents` table that was conflicting with the new relationship, fixing the "foreach" error on the view page.
6.  **Missing Fields:** Added `Category` and `Author` input fields to the edit form and updated the controller to save these values.

**Development Note:**
We enabled the local volume mount (`./:/var/www`) in `docker-compose.yml` to allow hot-reloading of code changes. This required running `composer install` inside the container to restore dependencies.

## Production Deployment

To deploy these changes to your production server:

1.  **Access your server:** SSH into your production server.
2.  **Navigate to the project:** `cd /path/to/orbit-docs`
3.  **Pull the latest changes:**
    ```bash
    git pull origin master
    ```
4.  **Rebuild containers:** (This ensures dependencies and file mounts are updated)
    ```bash
    docker-compose up -d --build
    ```
5.  **Run Migrations:** (This adds the new status column and removes the conflicting tags column)
    ```bash
    docker-compose exec app php artisan migrate
    ```

6.  **Verify Data:** If you see a 404 on the dashboard (`/demo-msp/dashboard`), it likely means the organization `demo-msp` doesn't exist in your production database. Run this to check:
    ```bash
    docker-compose exec app php artisan tinker --execute="App\Models\Organization::all()->pluck('slug');"
    ```
    *   If it returns `[]` (empty), you need to register a new account/organization or seed the database.
    *   If it returns a different slug (e.g., `"my-org"`), update your URL to match: `/my-org/dashboard`.

## Installer Improvements

We updated the installer to automatically create your first organization:
1.  **New Step:** After creating the Admin user, you will be prompted to create an **Organization**.
2.  **Automatic Assignment:** The admin user is automatically assigned to this new organization.
3.  **Result:** You will be redirected to the correct dashboard URL immediately after installation (e.g., `/my-company/dashboard`).

## 6. Critical Bug Fixes (Installer & Navigation)

### Organization Role Assignment Error
During organization creation, the installer failed with `Column not found: unknown column 'role'`.
*   **Cause:** The pivot table `organization_user` uses `role_id`, but the code was trying to save to a column named `role`.
*   **Fix:** Updated `InstallController.php` to fetch the 'Admin' role ID and use `role_id` for the attachment.

### Global Dashboard Redirect Loop
The "Dashboard" link in the global navigation bar was hardcoded to `/demo-msp/dashboard`, causing 404s for new installations.
*   **Fix:** Updated `resources/views/layouts/app.blade.php` to dynamically link to the user's first organization:
    ```php
    {{ route('dashboard', $currentOrganization->slug ?? Auth::user()->organizations->first()->slug ?? '#') }}
    ```
*   **Important:** This fix required a forced update of the view files inside the Docker container path `/var/www/resources/views/layouts/` to take effect.

## Verification Steps

To verify the deployment is stable:
1.  **Login:** Go to your server URL and log in with the credentials above.
2.  **Dashboard:** Ensure the dashboard loads without 404 errors.
3.  **Notes:** Try adding a note in the "Quick Notes" section to verify database writes work.
4.  **Documents:**
    *   Create a new document.
    *   Edit it and change the **Status** to "Published".
    *   Add **Tags** (e.g., "test, docs").
    *   Save and verify the changes appear on the view page.

## Future Maintenence

*   **Updating Code:** Since the volume mount in `docker-compose.yml` is disabled for production (commented out), you must **rebuild the container** whenever you pull new code:
    ```bash
    git pull origin master
    docker-compose up -d --build
    ```
*   **Database Changes:** Always run migrations after pulling updates:
    ```bash
    docker-compose exec app php artisan migrate
    ```
