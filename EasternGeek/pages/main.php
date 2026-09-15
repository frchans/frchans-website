<?php
// pages/main.php

// 变量安全防护
$pageTitle = $pageTitle ?? 'FRChans Tech ++';
?>

<?php
require_once __DIR__ . '/components/head.php'; ?>
<?php
require_once __DIR__ . '/components/header.php'; ?>

    <main class="container">
        <?php
        if (isset($viewPath) && file_exists($viewPath))
        {
            require $viewPath;
        }
        else
        {
            require __DIR__ . '/404.php';
        }
        ?>
    </main>

<?php
require_once __DIR__ . '/components/footer.php'; ?>
<?php
require_once __DIR__ . '/components/script.php'; ?>