<?php
// +----------------------------------------------------------------------
// | 功能介绍 
// +----------------------------------------------------------------------
// | @author PanWenHao
// +----------------------------------------------------------------------
// | @copyright PanWenHao Inc.
// +----------------------------------------------------------------------

namespace Pangongzi\Algorithm\Snowflake;

use Pangongzi\Algorithm\BaseService;

class SnowflakeService extends BaseService
{
  // 起始时间戳，通常是 2021年1月1日
  private const EPOCH = 1609459200000;

  // 机器ID所占的位数
  private const MACHINE_ID_BITS = 10;

  // 序列号所占的位数
  private const SEQUENCE_BITS = 12;

  // Base62 字符表，包括数字、大写字母和小写字母
  private const ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

  // 位移常量
  private $machineIdShift;
  private $timestampShift;
  private $sequenceMask;
  private $machineIdMask;

  // 机器ID
  private $machineId;
  // 序列号
  private $sequence = 0;
  // 上次生成ID的时间戳
  private $lastTimestamp = -1;

  /**
   * 构造函数
   * @param int $machineId 机器ID
   * @throws \InvalidArgumentException
   */
  public function __construct($machineId = 1)
  {
    parent::__construct();

    // 检查机器ID范围
    $maxMachineId = -1 ^ (-1 << self::MACHINE_ID_BITS);
    if ($machineId < 0 || $machineId > $maxMachineId) {
      throw new \InvalidArgumentException("机器ID必须在0到{$maxMachineId}之间");
    }

    // 序列号左移位数
    $this->machineIdShift = self::SEQUENCE_BITS;
    // 时间戳左移位数
    $this->timestampShift = self::MACHINE_ID_BITS + $this->machineIdShift;
    // 序列号掩码，用于与序列号进行与运算以保证序列号不超过最大值
    $this->sequenceMask = -1 ^ (-1 << self::SEQUENCE_BITS);
    // 机器ID掩码，用于与机器ID进行与运算以保证机器ID不超过最大值
    $this->machineIdMask = -1 ^ (-1 << self::MACHINE_ID_BITS);
    // 机器ID与掩码进行与运算
    $this->machineId = $machineId & $this->machineIdMask;
  }

  /**
   * 生成唯一ID
   * @return int 生成的唯一ID
   */
  public function generate()
  {
    // 获取当前时间戳（毫秒级）
    $timestamp = $this->timeGen();

    // 如果是同一毫秒，且序列号还未用完，增加序列号
    if ($timestamp == $this->lastTimestamp) {
      $this->sequence = ($this->sequence + 1) & $this->sequenceMask;
      if ($this->sequence == 0) {
        // 如果序列号已用完，等待下一毫秒
        $timestamp = $this->waitNextMillis($this->lastTimestamp);
      }
    } else {
      // 如果不是同一毫秒，重置序列号
      $this->sequence = 0;
    }

    // 更新最后时间戳
    $this->lastTimestamp = $timestamp;

    // 生成最终 ID
    return (($timestamp - self::EPOCH) << $this->timestampShift) |
      ($this->machineId << $this->machineIdShift) |
      $this->sequence;
  }

  /**
   * 等待下一毫秒
   * @param int $lastTimestamp 上次生成ID的时间戳
   * @return int 当前时间戳
   */
  private function waitNextMillis($lastTimestamp)
  {
    $timestamp = $this->timeGen();
    while ($timestamp <= $lastTimestamp) {
      $timestamp = $this->timeGen();
    }
    return $timestamp;
  }

  /**
   * 获取当前时间戳（毫秒级）
   * @return int 当前时间戳
   */
  private function timeGen()
  {
    return (int) (microtime(true) * 1000);
  }

  /**
   * 将数字编码为 Base62 字符串
   * @param int $number 要编码的数字
   * @return string 编码后的 Base62 字符串
   */
  public static function encode($number)
  {
    $base = strlen(self::ALPHABET); // Base62 的基数
    $result = ''; // 存储编码结果的字符串

    // 循环将数字转换为 Base62 字符串
    while ($number > 0) {
      $result = self::ALPHABET[$number % $base] . $result; // 获取当前位的字符并添加到结果前面
      $number = intdiv($number, $base); // 更新数字，去掉已经编码的部分
    }

    return $result; // 返回编码后的 Base62 字符串
  }
}
