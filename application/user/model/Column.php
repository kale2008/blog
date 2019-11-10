<?php
namespace app\index\model;
use think\Model;
use think\DB;
class Column extends Model
{
    protected $table = 'tb_column';
	public function findCategory($limit=6){
        return Db::table($this->table)
            ->where('column_status', 1)
            ->field('column_id,column_name')
            ->limit($limit)
            ->select();
    }

}