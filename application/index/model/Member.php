<?php
namespace app\index\model;

use think\Model;
use think\DB;

class Member extends Model
{
    protected $table = 'tb_member';

    /**
     * 创建用户
     * @param array $data
     * @return bool
     */
    public function createMember(array $data){
        $res = Db::table($this->table)->insert($data);
        if( !$res ){
            return false;
        }
        $userId = Db::name('user')->getLastInsID();
        return $userId;
    }

    /**
     * 获取用户信息通过用户名
     * @param string $name
     * @return array
     */
    public function getUserByName( string $name ){
        $res = false;
        $userInfo = Db::table($this->table)->where('member_name', $name)->select();
        if( !empty($userInfo) ){
            return $userInfo[0] ?? $res;
        }
        return $res;
    }

}