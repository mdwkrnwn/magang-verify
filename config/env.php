<?php

require_once __DIR__ . '/../function/Environment.php';

loadEnvironmentFileIfRequired(
    ['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'],
    dirname(__DIR__) . '/.env'
);