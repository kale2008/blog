<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Cookie;
class Index extends Controller
{
	protected function initialize()
	{
        // 判断用户名是否存在
    	if (Cookie::has('admin_user')) {
    		if (Cookie::get('admin_user') == 'admin') {
				$this->index();
    		} else {
    			$this->error('您还没有登录', 'admin/user/login');
    		}
    	} else {
    		$this->error('您还没有登录', 'admin/user/login');
    	}
	}
    public function index()
    {
        $this->assign('name', Cookie::get('admin_user'));
        return $this->fetch();
    }
}
