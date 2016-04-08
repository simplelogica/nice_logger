<?php

class Log {

  public static function emergency($tag, $message = "") {
    self::invoke_watchdog($tag, $message, WATCHDOG_EMERGENCY);
  }

  public static function alert($tag, $message = "") {
    self::invoke_watchdog($tag, $message, WATCHDOG_ALERT);
  }

  public static function critical($tag, $message = "") {
    self::invoke_watchdog($tag, $message, WATCHDOG_CRITICAL);
  }

  public static function error($tag, $message = "") {
    self::invoke_watchdog($tag, $message, WATCHDOG_ERROR);
  }

  public static function warning($tag, $message = "") {
    self::invoke_watchdog($tag, $message, WATCHDOG_WARNING);
  }

  public static function notice($tag, $message = "") {
    self::invoke_watchdog($tag, $message, WATCHDOG_NOTICE);
  }

  public static function info($tag, $message = "") {
    self::invoke_watchdog($tag, $message, WATCHDOG_INFO);
  }

  public static function debug($tag, $message = "") {
    self::invoke_watchdog($tag, $message, WATCHDOG_DEBUG);
  }

  private static function invoke_watchdog($tag, $message, $severity) {
    if (!$message) { // Only message with no tags
      watchdog("", $tag, [], $severity);
    } else if (is_array($tag)) { // Tags specified as an array
      watchdog(implode($tag, ' '), $message, [], $severity);
    } else { // Tags specified as a string
      watchdog($tag, $message, [], $severity);
    }
  }
}
