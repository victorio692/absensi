<?php
/**
 * Router untuk PHP Built-in Server
 * Menghandle routing ke public/index.php
 */

$requestUri = $_SERVER['REQUEST_URI'];

// Jika file static (css, js, images), serve langsung dari public
if (preg_match('/\.(jpg|jpeg|png|gif|css|js|ico|svg|woff|woff2|ttf|eot)$/i', $requestUri)) {
    return false; // Serve static file from filesystem
}

// Jika bukan file/folder static, route ke index.php
if ($requestUri !== '/' && !is_file(__DIR__ . '/public' . $requestUri)) {
    require __DIR__ . '/public/index.php';
    return;
}

return false;
