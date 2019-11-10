<?php
namespace app\admin\model;
use think\Model;
use think\DB;
class Blog extends Model
{
	public function addBlog($data)
	{
		return Db::table('tb_blog')->insert($data);
	}
	public function showBlog($limit)
	{
		return Db::table('tb_blog')
			->alias('b')
			->join('tb_column c', 'b.column_id=c.column_id')
			->field('blog_id,blog_title,column_name,column_status,blog_status,blog_fabulous')
			->where('b.blog_delete', 2)
			->limit($limit)
			->select();
	}
	public function searchCount($column_id, $blog_title)
	{

		$where = '1 = 1';
		if ($column_id) {
			$where = "c.column_id = $column_id";
		}
		return Db::table('tb_blog')
			->alias('b')
			->join('tb_column c', 'b.column_id=c.column_id')
			->field('blog_id,blog_title,column_name,column_status,blog_status,blog_fabulous')
			->where('b.blog_delete', 2)
			->where('b.blog_title', 'like', "%$blog_title%")
			->where($where)
			->count();
	}
	public function searchBlog($limit, $column_id, $blog_title)
	{
		$where = '1 = 1';
		if ($column_id) {
			$where = "c.column_id = $column_id";
		}
		return Db::table('tb_blog')
			->alias('b')
			->join('tb_column c', 'b.column_id=c.column_id')
			->field('blog_id,blog_title,column_name,column_status,blog_status,blog_fabulous')
			->where('b.blog_delete', 2)
			->where('b.blog_title', 'like', "%$blog_title%")
			->where($where)
			->limit($limit)
			->select();
		
	}
	public function totalCount()
	{
		return Db::table('tb_blog')->count();
	}
	public function noDeleteCount()
	{
		return Db::table('tb_blog')->where('blog_delete', 2)->count();
	}
	public function updateStatus($id)
	{

		$status = Db::table('tb_blog')->field('blog_status')->where('blog_id', $id)->find();
		$column_status = 1;
		$time = date("Y-m-d H:i:s", time());
		if ($status['blog_status'] == 1) {
			$column_status = 2;
		}
		$data = [
			'blog_status'=>$column_status,
			'update_time'=>$time
		];
		return Db::table('tb_blog')->where('blog_id', $id)->update($data);

	}
	public function deleteBlog($id)
	{

		return Db::table('tb_blog')->where(['blog_id'=>$id])->data(['blog_delete' => 1])->update();
		
	}
}