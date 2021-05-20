<?php

namespace application\admin\controller;

use reporter\lib\Controller;
use reporter\lib\Request;


class Items extends Controller
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
    public function Add()
    {
        $Request = new Request();

        if ($Request->isPost()) {
            $RoleID = $Request->post('RoleID');
            $ServerID = $Request->post('ServerID');
            $ItemType = $Request->post('ItemType');
            $ItemID = $Request->post('ItemID');
            $Quantity = $Request->post('Quantity');
        } else {

            return $this->display('Add.html');
        }
    }
}