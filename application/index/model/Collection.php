<?php
namespace app\index\model;
use think\Model;
use think\DB;
class Collection extends Model
{
    protected $table = 'tb_collection';

    public function findOneBlog($blogId=0,$memberId=0)
    {
        return Db::table($this->table)
            ->where('blog_id',$blogId)
            ->where('member_id',$memberId)
            ->find();
    }

    public function cancelCollectionBlog($blogId,$memberId){
        return  Db::table($this->table)
            ->where('blog_id',$blogId)
            ->where('member_id',$memberId)
            ->delete();
    }

    public function addCollectionBlog($blogId,$memberId){
        $insertData = [
            'blog_id'=>$blogId,
            'member_id'=>$memberId,
            'create_time'=>date('Y-m-d H:i:s')
        ];
        return  Db::table($this->table)->insert($insertData);
    }


    public function findAllBlog($memberId=0)
    {
        return Db::table($this->table)
            ->alias('a')
            ->join('tb_blog b','a.blog_id = b.blog_id')
            ->where('a.member_id',$memberId)
            //->fetchSql(true)
            ->select();
    }

}