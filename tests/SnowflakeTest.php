<?php
// +----------------------------------------------------------------------
// | 功能介绍
// +----------------------------------------------------------------------
// | @author PanWenHao
// +----------------------------------------------------------------------
// | @copyright PanWenHao Inc.
// +----------------------------------------------------------------------
namespace Pangongzi\Algorithm\Tests;


require __DIR__ . '/../vendor/autoload.php';

use Pangongzi\Algorithm\Snowflake\SnowflakeService;



$test = new SnowflakeService(1);
$id = $test->generate();


var_dump($id);
var_dump($test->encode($id));



$test = SnowflakeService::getInstance(1);
$id = $test->generate();


var_dump($id);
var_dump($test->encode($id));
