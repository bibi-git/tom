<?php

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string 
 */
function user_md5($str, $key = 'TomUserMD5'){
	return '' === $str ? '' : md5(sha1($str) . $key);
}
