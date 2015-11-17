<?php

namespace Drupal\nice_logger;

class Log {

  public static function emergency($message, $tag = "") {
    self::log($message, $tag, WATCHDOG_EMERGENCY);
  }
  
  public static function alert($message, $tag = "") {
    self::log($message, $tag, WATCHDOG_ALERT);
  }
  
  public static function critical($message, $tag = "") {
    self::log($message, $tag, WATCHDOG_CRITICAL);
  }
  
  public static function error($message, $tag = "") {
    self::log($message, $tag, WATCHDOG_ERROR);
  }
  
  public static function warning($message, $tag = "") {
    self::log($message, $tag, WATCHDOG_WARNING);
  }
  
  public static function notice($message, $tag = "") {
    self::log($message, $tag, WATCHDOG_NOTICE);
  }
  
  public static function info($message, $tag = "") {
    self::log($message, $tag, WATCHDOG_INFO);
  }
  
  public static function debug($message, $tag = "") {
    self::log($message, $tag, WATCHDOG_DEBUG);
  }
  
  private static function log($message, $tag, $severity) {
    watchdog($tag, $message, [], $severity);
  }
}