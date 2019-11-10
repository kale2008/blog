<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Column;
use think\facade\Cookie;

class Base extends Controller
{
    protected function initialize()
    {
        $indexUser = '';
        $indexAvatar = '';
        $indexUserId = '';
        // 判断用户名是否存在
        if (Cookie::has('index_user')) {
            $indexUser = Cookie::get('index_user');
            $indexAvatar = Cookie::get('index_avatar');
            $indexUserId = Cookie::get('index_user_id');
        }
        //获取博客栏目分类
        $col = new Column();
        $cate = $col->findCategory(6);
        $this->assign('cate',$cate);

        $this->assign('index_user',$indexUser);
        $this->assign('index_avatar',$indexAvatar);
        $this->assign('index_user_id',$indexUserId);
    }

}
