<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}


// refresh the DB
if (isset($_ENV['BOOTSTRAP_FIXTURES_LOAD'])) {
    $command = 'php ' . dirname(__DIR__, 1) . '/bin/console doctrine:database:drop --force --env=test';
    passthru($command);
    $command = 'php ' . dirname(__DIR__, 1) . '/bin/console doctrine:database:create --env=test';
    passthru($command);
    $command = 'php ' . dirname(__DIR__, 1) . '/bin/console doctrine:schema:create --env=test';
    passthru($command);
    $command = 'php ' . dirname(__DIR__, 1) . '/bin/console doctrine:fixtures:load -n --group=TestFixtures --env=test';
    passthru($command);
}
