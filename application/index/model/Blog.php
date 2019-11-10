<?php
namespace app\index\model;
use think\Model;
use think\DB;
class Blog extends Model
{
    protected $table = 'tb_blog';
    private $fields = 'blog_id,column_id,blog_title,blog_content,blog_fabulous,create_time,update_time';
    private $hot_fields = 'blog_id,blog_title,blog_fabulous,column_id';
    private $blog_status = 1;//表示博文已发布
    private $blog_delete = 2;//表示博文未删除

    //默认获取所有博文列表，如果传入有cid（栏目id）则根据栏目id获取博文列表
	public function findBlog($cid=0)
	{
	    $model = Db::table($this->table);
	    if ($cid >0){
            $model = $model->where('column_id',$cid);
        }
	    //获取博文列表
        return $model->where('blog_status',$this->blog_status)
            ->where('blog_delete',$this->blog_delete)
            ->order('update_time', 'desc')
            ->field($this->fields)
            //->fetchSql(true)
            ->select();
	}

    //获取热点博文列表
	public function findHotBlog($cid=0,$num = 5){
        $model = Db::table($this->table);
        if ($cid >0){
            $model = $model->where('column_id',$cid);
        }
        //获取热点博文列表
        return $model->where('blog_status',$this->blog_status)
            ->where('blog_delete',$this->blog_delete)
            ->order('blog_fabulous', 'desc')
            ->order('create_time', 'desc')
            ->field($this->hot_fields)
            ->limit($num)
            ->select();
    }


    public function findOneBlog($blogId=0)
    {
        return Db::table($this->table)
            ->where('blog_id',$blogId)
            ->find();
    }

    //收藏数加1
    public function setIncFabulous($blogId){
        return Db::table($this->table)
            ->where('blog_id', $blogId)
            ->setInc('blog_fabulous');
    }

    //收藏数减1
    public function setDecFabulous($blogId){
        return Db::table($this->table)
            ->where('blog_id', $blogId)
            ->setDec('blog_fabulous');
    }

}