<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\Column;
//s
class User extends Controller
{
    //登录
    public function login(){
        //获取博客栏目分类
        $cate = $this->findCategory();
        $this->assign('cate',$cate);
        return $this->fetch();
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
