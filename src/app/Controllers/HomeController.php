<?php
namespace App\Controllers;

use App\Core\Database;

class HomeController
{
    public function index(): void
    {
        $db = Database::getInstance();
        echo "<h1>🚀 Social Network</h1>";
        echo "<p>PHP : " . phpversion() . "</p>";
        echo "<p style='color:green'>✅ Database OK</p>";
    }
}