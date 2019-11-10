<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Cookie;
use think\Request;
use app\admin\model\Admin;

class User extends Controller
{
    //登录页
    public function login()
    {	
    	$code = $this->code();
    	$this->assign('code', $code);
    	return $this->fetch('login');
    }

    //登录验证
    public function dologin(Request $request)
    {
    	//获取登录参数
    	$username = $request->param('username');
    	$password = $request->param('password');
    	$yzm = $request->param('code');
        $is_three = $request->param('is_three');

        //判断验证码是否正确
       	if ($yzm != Cookie::get('code')) { 
    		return json([
    			'num'=>0, 'msg'=>'验证码不正确，请重新输入', 
    			'code'=>$this->code()
    		]);
    	}

        //实例化admin模型，查询当前登录用户信息
        $admin = new Admin();
    	$res = $admin->login($username, $password);

        //判断用户名是否正确
    	if ($res == null) {
    		return json([
    			'num'   =>  0, 
                'msg'   =>  '用户名不正确，请重新输入', 
    			'code'  =>  $this->code()
    		]);
    	}

        //判断密码是否正确
    	if ($res['admin_password'] != md5($password)) {
    		return json([
    			'num'   =>  0, 
                'msg'   =>  '密码不正确，请重新输入', 
    			'code'  =>  $this->code()
    		]);
    	}

        //判断是否勾选了三天免登陆
        $time = 86400*3;
        if ($is_three) {
            Cookie::set('admin_user', $username, $time);
        } else {
            Cookie::set('admin_user', $username);
        }
    	return json([
    			'num'   =>  1, 
                'msg'   =>  '登陆验证成功', 
    		]);
    	

    }

    //生成验证码，保存到cookie中
    public function code()
    {
    	$code = code();
    	Cookie::set('code', $code);
    	return $code;
    }

    //退出登录
    public function logout()
    {
        Cookie::clear();
        return $this->login();
    }

}
