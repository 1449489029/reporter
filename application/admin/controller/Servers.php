<?php

namespace application\admin\controller;

use reporter\lib\Controller;
use reporter\lib\Request;
use reporter\lib\Response;
use reporter\lib\db\DB;


// 游戏服务器
class Servers extends Controller
{
    public function getListData()
    {
        $Request = new Request();
        if ($Request->isPost()) {
            $serversOptions = DB::name('servers')->field(['ServerID', 'Name', 'Domain'])->select();

            return Response::jsonOutput($serversOptions);
        }
    }

}