<?php
namespace app\index\model;

use think\Model;

class Province extends Model
{
    /**
     * @return array
     */
    public function getProvinceList(){
        $res = [];
        $provinceData = $this->all()->toArray();
        if(!empty($provinceData)){
            foreach ($provinceData as $k => $v){
                $v['name'] = $v['province'];
                $v['code'] = $v['province_code'];
                $res[] = $v;
            }
        }
        return $res;
    }


}