<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * Common类，主要实现判断用户身份功能
 *
 *
 *
 */
class CommonController extends Controller {
    //定义属性
    protected $admin_id;

    public function __construct() {
        //调用父类的构造函数，否则会覆盖父类（Controller类）的构造函数，使得父类中某些方法无法执行
        parent::__construct();

        //开始判断用户身份
        if(!is_AdminLogin() and !is_OperLogin()) {
            $this -> error('您尚未登陆，请重新登陆','?m=Home&c=User&a=Login');
        }


    }


}
