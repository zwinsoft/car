<?php
namespace AndroidApi\Controller;
use Think\Controller;

/**
 * WaterController类，水源信息的接口
 * 
 */
class WaterController extends Controller {
    
    /**
     * NearbyWater方法：附近水源信息接口
     * @return Json
     */
    public function NearbyWater(){
        file_put_contents('water.txt','android设备调用了一次'."\r\n",FILE_APPEND);
        
        $water=M('water');
        //筛选参数
        $map=array();
        $map['confirm']=1; //只显示审核通过的水源
        $map['isDelete']=0;
        
        $waterList=$water->where($map)->select();
        $waterList=json_encode($waterList);
        echo $waterList;
    }

}
