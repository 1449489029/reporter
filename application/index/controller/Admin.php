<?php

namespace application\index\controller;


use mysql_xdevapi\Exception;
use reporter\lib\Model;
use reporter\lib\Controller;
use reporter\lib\Log;
use application\index\model\Demo;
use reporter\lib\db\DB;
use reporter\lib\Request;
use reporter\lib\File;
use reporter\lib\UploadFile;
use reporter\lib\Container;
use reporter\lib\Config;
use application\common\library\AnalysisDatabase;
use application\common\library\Export;


class Admin extends Controller
{
    /**
     * 添加物品
     *
     * @param int $RoleID 角色ID
     * @param int $ServerID 服务ID
     * @param int $ItemType 物品类型
     * @param int $ItemID 物品ID
     * @param int $Quantity 数量
     * @return json
     */
    public function AddItem()
    {
        $Request = new Request();

        if ($Request->isPost()) {
            $RoleID = $Request->post('RoleID');
            $ServerID = $Request->post('ServerID');
            $ItemType = $Request->post('ItemType');
            $ItemID = $Request->post('ItemID');
            $Quantity = $Request->post('Quantity');
        } else {

            return $this->display('AddItem.html');
        }


    }
}