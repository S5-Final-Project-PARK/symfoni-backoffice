<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Log incoming request headers to debug what Railway is sending
error_log(print_r(getallheaders(), true));

Request::setTrustedProxies(
    [$_SERVER['REMOTE_ADDR']], // Proxy IPs to trust (adjust as needed)
    Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_HOST
);

Request::setTrustedHosts([getenv('APP_URL')]); // Replace with your actual app URL or domain

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
