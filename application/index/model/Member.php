<?php
namespace app\index\model;

use think\Model;
use think\DB;

class Member extends Model
{
    protected $table = 'tb_member';
    /**
     * 查询用户是否存在
     * @param $username
     * @param $password
     * @return array|null|\PDOStatement|string|Model
     */
    public function findUserInfo($username,$password){

        return Db::table($this->table)
            ->where('member_name',$username)
            ->where('member_password',$password)
            ->where('member_status',1)  //用户状态，1：正常  2：封停
            ->find();
    }
    
}