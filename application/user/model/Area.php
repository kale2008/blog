<?php
namespace app\index\model;

use think\Model;
use think\DB;

class Area extends Model
{
    protected $table = 'tb_area';
    public function getAreaListByCityCode($code){
        $res = [];
        $cityData = Db::table($this->table)->where('parent_code', $code)->select();
        if(!empty($cityData)){
            foreach ($cityData as $k => $v){
                $v['name'] = $v['area'];
                $v['code'] = $v['area_code'];
                $res[] = $v;
            }
        }
        return $res;
    }
    
}