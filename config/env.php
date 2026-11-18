<?php

require_once __DIR__ . '/../function/Environment.php';

loadEnvironmentFileIfRequired(
    [
        'DB_HOST',
        'DB_PORT',
        'DB_NAME',
        'DB_USER',
        'DB_PASSWORD',
        'MAIL_HOST',
        'MAIL_PORT',
        'MAIL_USERNAME',
        'MAIL_PASSWORD',
        'MAIL_FROM_ADDRESS',
        'MAIL_FROM_NAME'
    ],
    dirname(__DIR__) . '/.env'
);
