# Quick Start

## Laravel application

1. Install PHP and JavaScript dependencies if they are not installed yet:

   ```bash
   composer install
   npm install
   ```

2. Configure `.env`, then migrate the database:

   ```bash
   php artisan migrate
   ```

3. Build the frontend assets and start the application:

   ```bash
   npm run build
   php artisan serve
   ```

4. Open `http://localhost:8000/register` to create a customer account. Public registration always creates a `pelanggan` account; it cannot grant administrator or mechanic access.

### Role dashboards

| Role | Dashboard | Access |
|---|---|---|
| `admin` | `/admin/dashboard` | Overview of customers, mechanics, and service queues |
| `pelanggan` | `/pelanggan/dashboard` | The signed-in customer's vehicles, active service, and recent history |
| `mekanik` or `teknisi` | `/mekanik/dashboard` | Active service queue and controlled status updates |

`/dashboard` redirects each signed-in user to their role dashboard. Account access is rejected when `is_active` is false. Customer data is loaded through the signed-in customer's own relationships. Mechanics can advance queues from `waiting` to `in_progress`, then to `completed`; invalid transitions are rejected.

New public accounts cannot select a privileged role. Assign `admin`, `mekanik`, or `teknisi` only to a trusted existing account through an authorized administrative process.

## Legacy application

The legacy front controller is `legacy/index.php`. Its login page uses the configured legacy database's `users` table, accepts only active accounts with a recognized role, and has separate sessions from Laravel.

- Administrators can open the legacy queue, customer database, inventory, and reminder views.
- Mechanics and technicians can open the legacy queue view.
- Customers are sent to the Laravel portal for their account dashboard.

Legacy screens remain presentation-only where no data controller exists; they should not be treated as connected CRUD features.

## Focused role and authentication tests

```bash
php artisan test tests/Feature/RoleAccessTest.php tests/Feature/Auth/AuthenticationTest.php tests/Feature/Auth/RegistrationTest.php
```
