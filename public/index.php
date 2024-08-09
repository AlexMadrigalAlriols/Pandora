<?php
use App\Controllers\AppointmentController;
use App\Database\Database;

require __DIR__ . '/../vendor/autoload.php';

// Initialize environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

Database::getConnection();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$controller = new AppointmentController();

// Basic Route System (Es solo para esta prueba)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $requestUri === '/appointment/store') {
    $controller->store();
} else if($_SERVER['REQUEST_METHOD'] === 'POST' && $requestUri === '/appointment/check-dni') {
    $controller->checkDni();
} else if ($requestUri === '/appointment/create') {
    $controller->create();
} else {
    $controller->index();
}

?>