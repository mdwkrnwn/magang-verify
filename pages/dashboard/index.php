<?php

require_once __DIR__ . '/../../function/Helpers.php';

http_response_code(404);
require __DIR__ . '/../errors/404.php';
exit;
