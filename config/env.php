<?php

require_once __DIR__ . '/../function/Environment.php';

loadEnvironmentFileIfRequired(
    [],
    dirname(__DIR__) . '/.env'
);
