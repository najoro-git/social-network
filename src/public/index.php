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

// Landing page
$router->get('/landing', [\App\Controllers\HomeController::class, 'landing']);

// Home
// $router->get('/', [\App\Controllers\HomeController::class, 'index']);
// Route racine — landing si non connecté, feed si connecté
$router->get('/', [\App\Controllers\HomeController::class, 'root']);

// Auth
$router->get('/register',  [\App\Controllers\AuthController::class, 'registerForm']);
$router->post('/register', [\App\Controllers\AuthController::class, 'register']);
$router->get('/login',     [\App\Controllers\AuthController::class, 'loginForm']);
$router->post('/login',    [\App\Controllers\AuthController::class, 'login']);
$router->get('/logout',    [\App\Controllers\AuthController::class, 'logout']);

// Profile
$router->get('/profile',       [\App\Controllers\ProfileController::class, 'index']);
$router->get('/profile/edit',  [\App\Controllers\ProfileController::class, 'editForm']);
$router->post('/profile/edit', [\App\Controllers\ProfileController::class, 'edit']);

// Posts
$router->post('/posts/create', [\App\Controllers\PostController::class, 'create']);
$router->get('/posts/edit',    [\App\Controllers\PostController::class, 'editForm']);
$router->post('/posts/edit',   [\App\Controllers\PostController::class, 'edit']);
$router->post('/posts/delete', [\App\Controllers\PostController::class, 'delete']);

// Posts détail
$router->get('/posts/show', [\App\Controllers\PostController::class, 'show']);

// Commentaires
$router->post('/comments/create', [\App\Controllers\CommentController::class, 'create']);
$router->post('/comments/delete', [\App\Controllers\CommentController::class, 'delete']);

// Likes
$router->post('/posts/like', [\App\Controllers\LikeController::class, 'toggle']);

// Posts détail
$router->get('/posts/{id}', [\App\Controllers\PostController::class, 'show']);

// Messagerie
$router->get('/messages',        [\App\Controllers\MessagingController::class, 'index']);
$router->get('/messages/new',    [\App\Controllers\MessagingController::class, 'new']);
$router->get('/messages/search', [\App\Controllers\MessagingController::class, 'search']);
$router->post('/messages/send',  [\App\Controllers\MessagingController::class, 'send']);
$router->post('/messages/delete',[\App\Controllers\MessagingController::class, 'delete']);
$router->get('/messages/{id}',   [\App\Controllers\MessagingController::class, 'show']);

// Migration temporaire
$router->get('/migrate', [\App\Controllers\MigrationController::class, 'run']);

$router->dispatch();