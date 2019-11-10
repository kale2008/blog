<?php
namespace app\admin\model;
use think\Model;
use think\DB;
class Column extends Model
{
	public function showColumn($limit)
	{
		return Db::table('tb_column')->limit($limit)->select();
	}
	public function totalCount()
	{
		return Db::table('tb_column')->count();
	}

	public function publishColumn()
	{
		return Db::table('tb_column')->where('column_status', 1)->select();
	}
	public function allColumn()
	{
		return Db::table('tb_column')->select();
	}
	public function searchCount($column_name, $column_status)
	{

		$where = '1 = 1';
		if ($column_status) {
			$where = "column_status = $column_status";
		} 
		return Db::table('tb_column')
				->where($where)
				->where('column_name', 'like' ,"%$column_name%")
				->count();
	}
	public function searchColumn($limit, $column_name, $column_status)
	{
		$where = '1 = 1';
		if ($column_status) {
			$where = "column_status = $column_status";
		}
		return Db::table('tb_column')
				->where($where)
				->where('column_name', 'like' ,"%$column_name%")
				->limit($limit)
				->select();
		
	}
	public function findColumn($column_name)
	{
		return Db::table('tb_column')->where('column_name', $column_name)->find();
	}
	public function addColumn($data)
	{
		
		return Db::table('tb_column')->data($data)->insert();
	}

	public function updateStatus($id)
	{

		$status = Db::table('tb_column')->field('column_status')->where('column_id', $id)->find();
		$column_status = 1;
		$time = date("Y-m-d H:i:s", time());
		if ($status['column_status'] == 1) {
			$column_status = 2;
		}
		$data = [
			'column_status'=>$column_status,
			'update_time'=>$time
		];
		return Db::table('tb_column')->where('column_id', $id)->update($data);

	}
	public function updateColumn($column_id, $column_name)
	{
		$time = date("Y-m-d H:i:s", time());
		$data = [
			'column_name'=>$column_name,
			'update_time'=>$time
		];
		return Db::table('tb_column')->where('column_id', $column_id)->update($data);
	}
	public function deleteColumn($id)
	{
		return Db::table('tb_column')->delete($id);
	}

}