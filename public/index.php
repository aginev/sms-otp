<?php

$loader = require __DIR__ . '/../vendor/autoload.php';

use SmsOtp\Controllers\DashboardController;
use SmsOtp\Controllers\HomeController;
use SmsOtp\Controllers\RegisterController;
use SmsOtp\Controllers\RegistrationFormController;
use SmsOtp\Controllers\SendVerificationController;
use SmsOtp\Controllers\VerificationController;
use SmsOtp\Controllers\VerificationFormController;
use SmsOtp\Core\App;
use SmsOtp\Core\Auth\SessionAuth;
use SmsOtp\Core\Database\MySqlConnector;
use SmsOtp\Core\Log\FileLogger;
use SmsOtp\Core\Routing\PathRouteMatcher;
use SmsOtp\Core\Routing\Route;
use SmsOtp\Core\Routing\Router;
use SmsOtp\Core\Session\NativeSession;
use SmsOtp\Repositories\UserRepository;
use SmsOtp\Services\Notification\DatabaseNotification;

const DS = DIRECTORY_SEPARATOR;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . DS . '..' . DS);
$dotenv->safeLoad();

$routeMatcher = new PathRouteMatcher($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
$router = new Router($routeMatcher, [
    Route::get('/', HomeController::class),
    Route::get('/dashboard', DashboardController::class),
    Route::get('register', RegistrationFormController::class),
    Route::post('register', RegisterController::class),
    Route::get('verify', VerificationFormController::class),
    Route::post('verify', VerificationController::class),
    Route::post('issue-new-verification-code', SendVerificationController::class),
    
    Route::post('logout', function () {
        auth()->setUser(null);
        
        redirect()
            ->to('/')
            ->flash('success', 'Bye, bye!')
            ->go();
    }),
]);

$db = new MySqlConnector([
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => $_ENV['DB_CHARSET'],
    'collation' => $_ENV['DB_COLLATION'],
]);
$session = new NativeSession();
$auth = new SessionAuth($session, new UserRepository());
$logger = new FileLogger(__DIR__ . '/../storage/logs/' . date('Y-m-d') . '.log');

$app = App::getInstance();
$app->setRouter($router)
    ->setLogger($logger)
    ->setDb($db)
    ->setSession($session)
    ->setAuth($auth)
    ->setNotification(new DatabaseNotification())
    ->run();
