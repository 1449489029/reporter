<?php

namespace application\controller;

use reporter\lib\Model;
use reporter\lib\Controller;

class Index extends Controller
{
    public function index($id, $name)
    {
        $Model = new Model();
        $result = $Model->query('SELECT * FROM `mc_missed_record`');
        print_r($result->fetchAll(Model::FETCH_ASSOC));
//        $sql = 'INSERT INTO `mc_missed_record`(`consultation_record_id`, `target_type`, `target_id`, `from_user_type`, `from_user_id`, `to_user_type`, `to_user_id`, `communication_type`, `payment_type`, `call_time`, `view_status`, `createtime`) VALUES(1, 1, 1, 1, 1, 1, 1, 1, 1, ' . time() . ', 1, ' . time() . ')';
//        echo $Model->exec($sql);
    }

    public function show_view()
    {
        $this->assign('name', '显示视图');
        $this->display('/index/show_view.html');
    }
}