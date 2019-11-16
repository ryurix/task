<?php

ini_set('include_path', '..');

// 1. Read config

require_once 'config.php';

// 2. Connect to database

require_once 'controller/Database.php';
$config['db'] = new Database();

// 3. Load modules

require_once 'model/Task.php';

// 4. Process request

require_once 'controller/first.php';

// 5. List blocks


// 6. Page body


// 7. Process file

