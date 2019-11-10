<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Cookie;
use app\index\model\Column;
use app\index\model\Member;

class User extends Controller
{
    //登录
    public function login(){
        //获取博客栏目分类
        $cate = $this->findCategory();
        $this->assign('cate',$cate);
        return $this->fetch();
    }

    //登录处理
    public function doLogin(){
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        if (!$username){
            $this->error('您的用户名不能为空');
        }
        if (!$password) {
            $this->error('您的密码不能为空');
        }
        //密码采用md5加密
        $password = md5($password);
        $model = new Member();
        $user = $model->findUserInfo($username,$password);
        if (empty($user)) {
            $this->error('你输入的用户名或者密码有误，请重新输入');
        }
        //设置Cookie 有效期为 1天
        Cookie::set('index_user',$username,86400);
        Cookie::set('index_avatar',$user['member_avatar'],86400);
        $this->success('登录成功','/');
    }

    //注册
    public function reg(){
        //获取博客栏目分类
        $cate = $this->findCategory();
        $this->assign('cate',$cate);
        return $this->fetch();
    }

    //获取博客栏目分类，默认取6个分类
    public function findCategory(){
        $col = new Column();
        return $col->findCategory(6);

    }


}
