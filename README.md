# Supply & Inventory System

COA-compliant procurement and supply inventory system for Philippine government agencies.

## Requirements
- PHP 8.1+
- MySQL 8.0+
- Apache with mod_rewrite (XAMPP/WAMP/LAMP)

## Setup

### 1. Copy to web root
```
cp -r supply_inventory/ C:/xampp/htdocs/
```

### 2. Create the database
```sql
CREATE DATABASE supply_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Import schema
```
mysql -u root -p supply_inventory < config/supply_inventory_schema.sql
mysql -u root -p supply_inventory < config/users_table.sql
```

### 4. Configure database credentials
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'supply_inventory');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
```

### 5. Set your agency name
Edit `config/app.php`:
```php
define('APP_AGENCY', 'Your Agency Name Here');
define('BASE_URL',   'http://localhost/supply_inventory');
```

### 6. Open in browser
```
http://localhost/supply_inventory/
```

### 7. Default login
- **Username:** `admin`
- **Password:** `Admin@1234`
- **Change the password immediately after first login.**

---

## Module Summary

| Module         | COA Form / Report         |
|----------------|---------------------------|
| Purchase Orders| PO                        |
| Receiving      | IAR (Inspection & Acceptance Report) |
| Stock Cards    | Stock Card (GAM for NGAs) |
| RIS            | Requisition & Issue Slip  |
| ICS            | Inventory Custodian Slip  |
| PAR            | Property Acknowledgement Receipt |
| Reports > RSMI | Report of Supplies & Materials Issued |
| Reports > RPCI | Report on Physical Count of Inventories |

## Roles
- **admin** — full access including user management
- **supply_officer** — create/edit POs, RIS, receive deliveries, issue items
- **viewer** — read-only access to all modules and reports
