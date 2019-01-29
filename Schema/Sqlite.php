<?php

namespace Kanboard\Plugin\UpdateNotifier\Schema;

use PDO;

const VERSION = 1;

function version_1(PDO $pdo)
{
    $pdo->exec("
        CREATE TABLE plugin_dates (
            name TEXT NOT NULL PRIMARY KEY,
            date_creation INTEGER NOT NULL DEFAULT 0
        )
    ");
}
