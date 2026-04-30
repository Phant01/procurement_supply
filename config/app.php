<?php
define('APP_NAME',    'Supply & Inventory System');
define('APP_AGENCY',  'MBLISTTDA');
define('APP_VERSION', '1.0.0');

// !! Change this to match your local setup !!
define('BASE_URL', 'http://localhost/supply_inventory');

// DBM-COA Joint Circular capitalization threshold
// >= threshold → Equipment (PPE) → PAR
//  < threshold → Semi-expendable    → ICS
define('CAPITALIZATION_THRESHOLD', 15000.00);

define('ITEMS_PER_PAGE', 20);
define('DATE_FORMAT',    'Y-m-d');
define('DATE_DISPLAY',   'F d, Y');
define('SESSION_TIMEOUT', 28800); // 8 hours

error_reporting(E_ALL);
ini_set('display_errors', 1); // set 0 in production
