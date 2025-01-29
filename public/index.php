<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Trust proxies and set the headers we care about (you can adjust the header flags)
Request::setTrustedProxies(
    [$_SERVER['REMOTE_ADDR']], // Proxy IPs to trust (adjust as needed)
    Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_HOST
);

// Optionally, set trusted hosts to avoid Host Header attacks
Request::setTrustedHosts([getenv('APP_URL')]); // Replace with your actual app URL or domain

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
