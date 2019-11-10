<?php
namespace app\index\model;
use think\Model;
use think\DB;
class Fabulous extends Model
{
    protected $table = 'tb_fabulous';

    public function findOneBlog($blogId=0,$member_id=0)
    {
        return Db::table($this->table)
            ->where('blog_id',$blogId)
            ->where('member_id',$member_id)
            ->find();
    }

    public function cancelFabulousBlog($blogId,$memberId){
        return  Db::table($this->table)
            ->where('blog_id',$blogId)
            ->where('member_id',$memberId)
            ->delete();
    }

    public function addFabulousBlog($blogId,$memberId){
        $insertData = [
            'blog_id'=>$blogId,
            'member_id'=>$memberId,
            'create_time'=>date('Y-m-d H:i:s')
        ];
        return  Db::table($this->table)->insert($insertData);
    }


}