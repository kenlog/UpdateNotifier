<?php

namespace Kanboard\Plugin\UpdateNotifier\Schema;

use PDO;

const VERSION = 2;

function version_2(PDO $pdo)
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS plugin_dates (
            name VARCHAR(80) NOT NULL PRIMARY KEY,
            date_creation INTEGER NOT NULL DEFAULT 0
        )
    ");
}
