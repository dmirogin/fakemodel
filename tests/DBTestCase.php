<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use yii\db\Connection;

class DBTestCase extends TestCase
{
    public function setUp()
    {
        /** @var Connection $db */
        $db = \Yii::createObject([
            'class' => Connection::class,
            'dsn' => 'sqlite::memory:'
        ]);

        $db->open();

        $fixture = __DIR__ . '/data/sqlite.sql';
        $lines = explode(';', file_get_contents($fixture));
        foreach ($lines as $line) {
            if (trim($line) !== '') {
                $db->pdo->exec($line);
            }
        }

        \Yii::$app->setComponents([
            'db' => $db
        ]);
    }
}
