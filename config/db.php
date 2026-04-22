<?php
const DB_SERVER = 'localhost';
const DB_USERNAME = 'superadmin';
const DB_PASSWORD = 'superadmin_';
const DB_NAME = 'inspire_ioi_website';

const DEV_SERVER = 'localhost';
const DEV_USERNAME = 'root';
const DEV_PASSWORD = '';
const DEV_NAME = 'ioi';

function getDbConfig($mode = "DEV")
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










