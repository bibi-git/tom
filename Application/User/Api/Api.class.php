<?php

namespace User\Api;
define('USER_CLIENT_PATH', dirname(dirname(__FILE__)));

//载入配置文件
require_cache(USER_CLIENT_PATH . '/Conf/config.php');

//载入函数库文件
require_cache(USER_CLIENT_PATH . '/Common/common.php');

/**
 * USER API调用控制器层
 * 调用方法 A('Uc/User', 'Api')->login($username, $password, $type);
 */
abstract class Api{

	/**
	 * API调用模型实例
	 * @access  protected
	 * @var object
	 */
	protected $model;

	/**
	 * 构造方法，检测相关配置
	 */
	public function __construct(){
		//相关配置检测
		defined('USER_APP_ID') || throw_exception('USER配置错误：缺少USER_APP_ID');
		defined('USER_API_TYPE') || throw_exception('USER配置错误：缺少USER_APP_API_TYPE');
		defined('USER_AUTH_KEY') || throw_exception('USER配置错误：缺少USER_APP_AUTH_KEY');
		defined('USER_DB_DSN') || throw_exception('USER配置错误：缺少USER_DB_DSN');
		defined('USER_TABLE_PREFIX') || throw_exception('USER配置错误：缺少USER_TABLE_PREFIX');
		if(USER_API_TYPE != 'Model' && USER_API_TYPE != 'Service'){
			throw_exception('USER配置错误：USERC_API_TYPE只能为 Model 或 Service');
		}
		if(USER_API_TYPE == 'Service' && UC_AUTH_KEY == ''){
			throw_exception('USER配置错误：Service方式调用Api时USER_AUTH_KEY不能为空');
		}
		if(USER_API_TYPE == 'Model' && USER_DB_DSN == ''){
			throw_exception('USER配置错误：Model方式调用Api时USER_DB_DSN不能为空');
		}

		$this->_init();
	}

	/**
	 * 抽象方法，用于设置模型实例
	 */
	abstract protected function _init();

}
