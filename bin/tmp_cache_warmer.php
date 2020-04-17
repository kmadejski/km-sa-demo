<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__) . '/config/bootstrap.php';

$environment = getenv('APP_ENV');
if ($environment === false) {
    $environment = 'prod';
}

if (($useDebugging = getenv('APP_DEBUG')) === false || $useDebugging === '') {
    $useDebugging = $environment === 'dev';
}

$kernel = new Kernel($environment, $useDebugging);
$kernel->boot();

$container = $kernel->getContainer();

# warm cache for professionals
$request = Request::create('/professionals');

$response = $kernel->handle($request);
if (strpos($response->getContent(), 'Professionals!') !== false) {
    echo  'OK' . PHP_EOL;
}

$kernel->terminate($request, $response);
