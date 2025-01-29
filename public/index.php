<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Log incoming request headers to debug what Railway is sending
error_log(print_r(getallheaders(), true));

Request::setTrustedProxies(
    [$_SERVER['REMOTE_ADDR']], // Could be set to proxy IP or '*'
    Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_HOST
);

Request::setTrustedHosts([getenv('APP_URL')]); // Replace with your actual app URL or domain

error_log(print_r(getallheaders(), true));

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
