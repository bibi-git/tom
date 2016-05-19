<?php
namespace Install\Controller;
use Think\Controller;
use Think\Db;
use Think\Storage;
class IndexController extends Controller {


    //安装首页
    public function index(){

        session(null); // 清空当前的session
        
        if(is_file(APP_PATH . 'Common/Conf/config.php')){
            // 已经安装过了 执行更新程序
            session('update',true);
            $msg = '请删除install.lock文件后再运行升级!';
        }else{
            $msg = '已经成功安装了此系统，请不要重复安装!';
        }
        if(Storage::has('./Data/install.lock')){
            $this->error($msg);
        }
        $this->display();
    }



    //安装第一步，检测运行所需的环境设置
    public function step1(){

        if(Storage::has('./Data/install.lock')){
            $this->error('已经成功安装了此系统，请不要重复安装!');
        }

        session('error', false);

        //环境检测
        $env = check_env();

        //目录文件读写检测
        if(IS_WRITE){
            $dirfile = check_dirfile();
            $this->assign('dirfile', $dirfile);
        }

        //函数检测
        $func = check_func();

        session('step', 1);

        $this->assign('env', $env);
        $this->assign('func', $func);
        $this->display();
    }

    //安装第二步，创建数据库
    public function step2($db = null, $admin = null){
        if(IS_POST){
            //检测管理员信息
            if(!is_array($admin) || empty($admin[0]) || empty($admin[1]) || empty($admin[3])){
                $this->error('请填写完整管理员信息');
            } else if($admin[1] != $admin[2]){
                $this->error('确认密码和密码不一致');
            } else {
                $info = array();
                list($info['username'], $info['password'], $info['repassword'], $info['email'])
                = $admin;
                //缓存管理员信息
                session('admin_info', $info);
            }

            //检测数据库配置
            if(!is_array($db) || empty($db[0]) ||  empty($db[1]) || empty($db[2]) || empty($db[3])){
                $this->error('请填写完整的数据库配置');
            } else {
                $DB = array();
                list($DB['DB_TYPE'], $DB['DB_HOST'], $DB['DB_NAME'], $DB['DB_USER'], $DB['DB_PWD'],
                     $DB['DB_PORT'], $DB['DB_PREFIX']) = $db;
                //缓存数据库配置
                session('db_config', $DB);

                //创建数据库
                $dbname = $DB['DB_NAME'];
                unset($DB['DB_NAME']);
                $db  = Db::getInstance($DB);
                $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
                $db->execute($sql) || $this->error($db->getError());
            }

            //跳转到数据库安装页面
            $this->redirect('step3');
        } else {
            if(session('update')){
                session('step', 2);
                $this->display('update');
            }else{
                session('error') && $this->error('环境检测没有通过，请调整环境后重试！');

                $step = session('step');
                if($step != 1 && $step != 2){
                    $this->redirect('step1');
                }

                session('step', 2);
                $this->display();
            }
        }
    }

    //安装第三步，安装数据表，创建配置文件
    public function step3(){
        if(session('step') != 2){
            $this->redirect('step2');
        }

        $this->display();

        if(session('update')){
            $db = Db::getInstance();
            //更新数据表
            update_tables($db, C('DB_PREFIX'));
        }else{
            //连接数据库
            $dbconfig = session('db_config');
            $db = Db::getInstance($dbconfig);
            //创建数据表
            create_tables($db, $dbconfig['DB_PREFIX']);
            //注册创始人帐号
            $auth  = build_auth_key();
            $admin = session('admin_info');
            register_administrator($db, $dbconfig['DB_PREFIX'], $admin, $auth);

            //创建配置文件
            $conf   =   write_config($dbconfig, $auth);
            session('config_file',$conf);
        }

        if(session('error')){
            //show_msg();
        } else {
            session('step', 3);
            $this->redirect('complete');
        }
    }



	//安装完成
    public function complete(){
        $step = session('step');

        if(!$step){
            $this->redirect('index');
        } elseif($step != 3) {
            $this->redirect("step{$step}");
        }

        // 写入安装锁定文件
        Storage::put('./Data/install.lock', 'lock');
        if(!session('update')){
            //创建配置文件
            $this->assign('info',session('config_file'));
        }
        session('step', null);
        session('error', null);
        session('update',null);
        $this->display();
    }
}