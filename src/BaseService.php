<?php
// +----------------------------------------------------------------------
// | 功能介绍 
// +----------------------------------------------------------------------
// | @author PanWenHao
// +----------------------------------------------------------------------
// | @copyright PanWenHao Inc.
// +----------------------------------------------------------------------

namespace Pangongzi\Algorithm;


class BaseService
{

  public static $instance = null;

  public function __construct() {}


  // 单列 模式
  public static function getInstance(...$params)
  {
    $className = get_called_class();

    // 如果 $instance 为空或者 $instance 不是当前类的实例
    if (self::$instance === null || !is_a(self::$instance, $className)) {

      // 创建当前类的新实例并赋值给 $instance
      self::$instance = new $className(...$params);
    }

    // 返回单例实例
    return self::$instance;
  }
}
