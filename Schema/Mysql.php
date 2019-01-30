<?php

namespace Kanboard\Plugin\UpdateNotifier\Schema;

use PDO;

const VERSION = 1;

function version_1(PDO $pdo)
{
    $pdo->exec("
        CREATE TABLE plugin_dates (
            name VARCHAR(80) NOT NULL,
            date_creation INT NOT NULL DEFAULT 0,
            PRIMARY KEY(name)
        ) ENGINE=InnoDB CHARSET=utf8
    ");
}
