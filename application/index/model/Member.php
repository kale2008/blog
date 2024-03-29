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
     * 修改用户信息
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateMemberById($data, $id){
        try {
            Db::table($this->table)->where('member_id', $id)->update($data);
        }catch (\Exception $e){
            return false;
        }
        return true;
    }

    /**
     * 获取用户信息通过用户名
     * @param string $name
     * @return array
     */
    public function getUserByName($name){
        return Db::table($this->table)
            ->where('member_name', $name)
            ->where('member_status',1)  //用户状态，1：正常  2：封停
            ->find();
    }

}