<?php
namespace app\index\model;

use think\Model;
use think\DB;

class City extends Model
{
    protected $table = 'tb_city';
    public function getCityListByProvinceCode($code){
        $res = [];
        $cityData = Db::table($this->table)->where('parent_code', $code)->select();
        if(!empty($cityData)){
            foreach ($cityData as $k => $v){
                $v['name'] = $v['city'];
                $v['code'] = $v['city_code'];
                $res[] = $v;
            }
        }
        return $res;
    }

    /**
     * 获取市列表 - 以code为key
     * @return array
     */
    public function cityAll(){
        $res = [];
        $data = $this->all()->toArray();
        foreach ($data as $v){
            $v['code'] = $v['city_code'];
            $v['name'] = $v['city'];
            $res[$v['city_code']] = $v;
        }
        return $res;
    }
}