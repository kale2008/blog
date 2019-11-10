<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function code()
{
	$arr=array_merge(range('a','b'),range('A','B'),range('0','9'));
	shuffle($arr);
	$arr=array_flip($arr);
	$arr=array_rand($arr,4);
	$code = implode('', $arr);
	return $code;
}