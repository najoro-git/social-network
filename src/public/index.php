<?php
//echo "<H1> Social Network - OK </H1>";
//echo "<p> PHP : " . phpversion() . "</p>";

//try {
//    $pdo = new PDO(
//        "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME'),
//        getenv('DB_USER'),
//        getenv('DB_PASSWORD')
//    );
//    echo "<p style='color:green'> Mysql connecté</p>";
//}catch (Exception $e){
//    echo "<p style='color:red'> Musql :" . $e->getMessage() . "</p>";
//}

//try{
//    $redis = new Redis();
//    $redis->connect('redis', 6379);
//    echo "<p style='color:green'> Redis connecté</p>";

//} catch (Exception $e){
//    echo "<p style='color:red'> Musql :" . $e->getMessage() . "</p>";
//}
require_once __DIR__ . '/../config/config.php';

// Routes (on les enrichira au fur et à mesure)


use App\Core\Router;
use App\Core\Session;

Session::start();

$router = new Router();

// Routes
$router->get('/',          [\App\Controllers\HomeController::class, 'index']);
$router->get('/register',  [\App\Controllers\AuthController::class, 'registerForm']);
$router->post('/register', [\App\Controllers\AuthController::class, 'register']);
$router->get('/login',     [\App\Controllers\AuthController::class, 'loginForm']);
$router->post('/login',    [\App\Controllers\AuthController::class, 'login']);
$router->get('/logout',    [\App\Controllers\AuthController::class, 'logout']);

$router->dispatch();