<?php

$app = reporter\lib\Application::instance();


$app->bind(
    reporter\lib\Request::class,
    reporter\lib\Request::class,
    true
);

$app->bind(\reporter\lib\Route::class, function () use ($app) {
    return new \reporter\lib\Route(
        $app->make(reporter\lib\Request::class)
    );
}, true);


return $app;
