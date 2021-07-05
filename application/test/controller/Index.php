<?php

namespace application\test\controller;

use reporter\lib\Controller;
use reporter\lib\Request;
use application\common\library\test\AddLogistics;


class Index extends Controller
{
    public function index()
    {
        $AddLogistics = new AddLogistics(
            'c1c2zvHODSxRoN/kS9mQ0qqwFJoC+CRATkHdB18DZsxFb5Q59PuSiO3m',
            '6E749B6864DC8A5A1C436E7FAD3BBD58',
            '126#1',
            '14,23,400001#1,0#0|$14,24,0#0,400001#1'
        );
        $AddLogistics->run();
    }
}
