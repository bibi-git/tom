<?php
namespace Admin\Controller;

/**
 * 后台首页控制器
 */
class IndexController extends PublicController {

    /**
     * 后台首页
     */
    public function index(){
        $this->meta_title = '管理首页';
        $this->display();
    }

}
