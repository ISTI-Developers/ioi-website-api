<?php
const DB_SERVER = '';
const DB_USERNAME = '';
const DB_PASSWORD = '';
const DB_NAME = '';

const DEV_SERVER = 'localhost';
const DEV_USERNAME = 'root';
const DEV_PASSWORD = '';
const DEV_NAME = 'ioi_website';

function getDbConfig($mode = "PROD")
{
    if($mode === 'DEV') {
        return [
            'host' => DEV_SERVER,
            'username' => DEV_USERNAME,
            'password' => DEV_PASSWORD,
            'database' => DEV_NAME,
        ];
    }

    return [
        'host' => DB_SERVER,
        'username' => DB_USERNAME,
        'password' => DB_PASSWORD,
        'database' => DB_NAME,
    ];
}










