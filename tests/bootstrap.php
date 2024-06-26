<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

function bootstrap(): void
{
    if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
        require dirname(__DIR__) . '/config/bootstrap.php';
    } elseif (method_exists(Dotenv::class, 'bootEnv')) {
        (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
    }
}

$_ENV['APP_ENV'] = 'test';
bootstrap();
