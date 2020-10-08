<?php

/**
 *
 *  Here the whole project is bootstraped and loaded up.
 *
 */

use Illuminate\Database\Capsule\Manager as DatabaseManager;

Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();
require_once __DIR__ . '/database/Connection.php';
require_once __DIR__ . '/migrations/migration.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/Request.php';
$migrations = (new Connection(new DatabaseManager))->setup();
(new Migration($migrations))->migrate();

Router::load('/../routes/web.php')
    ->direct(Request::uri(), Request::method(), Request::params());
