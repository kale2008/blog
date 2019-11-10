<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\Column;
use app\index\model\Member;

class User extends Controller
{
    private $error;

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

    /**
     * @return string|void
     */
    public function regSave(){
        $params = $_REQUEST;
        $params['avatar'] = 'https://img-blog.csdnimg.cn/20190927151101105.png';
        if( !$this->_checkRegData($params)){
            echo $this->error;
            return;
        };
        $data = [
            'member_name'        => $params['username'],
            'member_password'    => md5($params['password']),
            'member_province'    => $params['province'],
            'member_city'        => $params['city'],
            'member_county'      => $params['county'],
            'member_address'     => $params['address'],
            'member_avatar'      => $params['avatar'],
            'member_lastlogin'   => date('Y-m-d H:i:s'),
        ];
        $userObj = new Member();
        $res = $userObj->createMember($data);
        if($res){
            return 'suc';
        }else{
            return '注册失败，请重新注册';
        }
    }

    /**
     * 校验注册数据
     * @param $data
     * @return bool
     */
    private function _checkRegData($data){
        $usernameTitle = '用户名';
        $passwordTitle = '密码';
        $repasswordTitle = '重复密码';
        $provinceTitle = '省';
        $cityTitle = '市';
        $countyTitle = '区';
        $addressTitle = '具体地址';
        $avatarTitle = '上传头像';
        $emptyTitle = '不能为空';
        $regex = '/[0-9a-zA-Z_-]+/';

        // 校验用户名
        if( empty($data['username']) ){
            $this->error = $usernameTitle.$emptyTitle;
            return false;
        }
        if( !preg_match($regex, $data['username'])){
            $this->error = $usernameTitle.'必须是字母数字下划线横杠';
            return false;
        }
        // 校验用户名唯一
        $userObj = new Member();
        if( $userObj->getUserByName($data['username']) ){
            $this->error = $usernameTitle.'已经存在';
            return false;
        }

        // 校验密码
        if( empty($data['password']) ){
            $this->error = $passwordTitle.$emptyTitle;
            return false;
        }
        if( !preg_match($regex, $data['password'])){
            $this->error = $passwordTitle.'必须是字母数字下划线横杠';
            return false;
        }

        // 校验重复密码
        if( empty($data['repassword']) ){
            $this->error = $repasswordTitle.$emptyTitle;
            return false;
        }
        if( $data['repassword'] != $data['password']){
            $this->error = $repasswordTitle.'和'.$passwordTitle.'必须相同';
            return false;
        }

        // 校验省/市/县
        if( empty($data['province']) ){
            $this->error = $provinceTitle.$emptyTitle;
            return false;
        }
        if( empty($data['city']) ){
            $this->error = $cityTitle.$emptyTitle;
            return false;
        }
        if( empty($data['county']) ){
            $this->error = $countyTitle.$emptyTitle;
            return false;
        }

        // 校验具体地址
        if( empty($data['address']) ){
            $this->error = $addressTitle.$emptyTitle;
            return false;
        }
        // 校验上传头像
        if( empty($data['avatar']) ){
            $this->error = $avatarTitle.$emptyTitle;
            return false;
        }
        return true;
    }
}
