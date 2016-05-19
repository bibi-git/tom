<?php
/**
 * Created by PhpStorm.
 * User: Tom D
 * Date: 2016/5/18
 * Time: 18:25
 */
namespace Home\Controller;
use Think\Controller;

/**
 * 后台首页控制器
 */
class IndexController extends Controller {

    /**
     * 后台首页
     */
    public function index(){
        echo '首页';
    }

}