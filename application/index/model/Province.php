<?php
namespace app\index\model;

use think\Model;
use think\DB;

class Province extends Model
{
    protected $table = 'tb_province';
    /**
     * 获取省份列表
     * @return array
     */
    public function getProvinceList(){
        $res = [];
        $provinceData = Db::table($this->table)->select();
        if(!empty($provinceData)){
            foreach ($provinceData as $k => $v){
                $v['name'] = $v['province'];
                $v['code'] = $v['province_code'];
                $res[] = $v;
            }
        }
        return $res;
    }

    /**
     * 获取省列表 - 以code为key
     * @return array
     */
    public function provinceAll(){
        $res = [];
        $data = $this->getProvinceList();
        foreach ($data as $v){
            $res[$v['code']] = $v;
        }
        return $res;
    }
}