<?php

use reporter\lib\Route;
use application\index\controller\Index;

// 首页
Route::get('/index/index/index', Index::class, 'index', 'test');