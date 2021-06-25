<?php

$app = reporter\lib\Application::instance();


$app->bind(
    reporter\lib\Request::class,
    reporter\lib\Request::class,
    true
);


return $app;
