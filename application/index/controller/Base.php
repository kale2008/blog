<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Column;
use think\facade\Cookie;

class Base extends Controller
{
    protected function initialize()
    {
        // 判断用户名是否存在
        if (Cookie::has('index_user')) {
            $this->assign('index_user',Cookie::get('index_user'));
            $this->assign('index_avatar',Cookie::get('index_avatar'));
        }
        //获取博客栏目分类
        $col = new Column();
        $cate = $col->findCategory(6);
        $this->assign('cate',$cate);
    }

}
