<?php
session_start();

$config = require __DIR__ . "/../../config/app.php";

$adminUsername = $config["admin"]["username"];
$adminPassword = $config["admin"]["password"];

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username == $adminUsername && $password == $adminPassword)
    {
        $_SESSION["is_admin"] = true;
        $_SESSION["adminUsername"] = $adminUsername;

        header("Location: dashboard.php");

        exit("一些地方发生了错误");
    }
    else
    {
        $errorMessage = "账户名或密码有误";
    }
}

require_once __DIR__ . "/../components/head.php";
require_once __DIR__ . "/../components/header.php";
require_once __DIR__ . "/../components/script.php";
require_once __DIR__ . "/../components/footer.php";