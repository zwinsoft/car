<?php
return array(
	//'配置项'=>'配置值'
	    /* 模块相关配置 */
     'DEFAULT_MODULE'     => 'Home',
    //'MODULE_ALLOW_LIST'  => array('Home','Admin'),

    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => 'LmCx:2E+z[!kD"1N>U~3cB{f9WSqZt]Tl/V&PM|0', //默认数据加密KEY

    /* 调试配置 */
    'SHOW_PAGE_TRACE' => false,

    /* 用户相关设置 */
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数

    /* 数据库配置 */

    //服务器端配置
    'DB_TYPE'   => 'mysqli', // 数据库类型
    'DB_HOST'   => '115.28.160.248', // 服务器地址
    'DB_NAME'   => 'car', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'kevinluo519',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'car_', // 数据库表前缀

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
 
    ),

    /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'tjac_fengxian_', //session前缀
    'COOKIE_PREFIX'  => 'tjac_fengxian_', // Cookie前缀 避免冲突
    'WEB_SITE_TITLE'=>'天津车辆远程定损系统',


);
