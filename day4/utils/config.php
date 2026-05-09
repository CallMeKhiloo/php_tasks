<?php

const DB_HOST = '127.0.0.1';
const DB_USER = 'php';
const DB_PASS = 'test123';
const DB_NAME = 'php_test';

function connect_db()
{
    try {
        $dsn = 'mysql:host=127.0.0.1;dbname=' . DB_NAME . ';charset=utf8mb4';
        return new PDO($dsn, DB_USER, DB_PASS);
    } catch (PDOException $e) {
        error_log('DB connect error: ' . $e->getMessage());
        return null;
    }
}
