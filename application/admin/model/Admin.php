<?php
namespace app\admin\model;
use think\Model;
use think\DB;
class Admin extends Model
{
	public function login($username, $password)
	{
		return Db::table('tb_admin')->where('admin_name', $username)->find();
	}
}