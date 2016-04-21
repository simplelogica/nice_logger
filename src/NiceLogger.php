<?php

/**
 * This class is responsable of writting the log messages to the log file.
 */
class NiceLogger {

  private $fileName;
  private $minLevel;

  function __construct($fileName, $minLevel) {
    $this->fileName = $fileName;
    $this->minLevel = $minLevel;
  }

  function log($logEntry) {
    if (!$this->shouldLog($logEntry)) {
      return;
    }
    $this->write($logEntry);
  }

  private function shouldLog($logEntry) {
    return $logEntry['severity'] <= $this->minLevel;
  }

  private function write($logEntry) {
    $logFile = fopen($this->fileName, 'a');
    fwrite($logFile, $this->formatLogEntry($logEntry));
    fclose($logFile);
  }

  private function formatLogEntry($logEntry) {
    $timestamp = $this->formatTimestamp($logEntry);
    $severity = $this->formatSeverity($logEntry);
    $tags = $this->formatTags($logEntry);
    $message = $this->formatMessage($logEntry);

    $formattedMsg = array_filter([$timestamp, $severity, "-- :", $tags, $message, "\n"]);
    return implode(' ', $formattedMsg);
  }

  private function formatTimestamp($logEntry) {
    return '['. date('c', $logEntry['timestamp']) .']';
  }

  private function formatMessage($logEntry) {
    // We protect ourselves from those modules that call `watchdog()` with the variables
    // set to null or any other thing that is not an array.
    if (!empty($logEntry['variables']) && is_array($logEntry['variables'])) {
      $message = format_string($logEntry['message'], $logEntry['variables']);
    } else {
      $message = format_string($logEntry['message'], []);
    }
    // Drupal likes to add HTML tags such as `<em>` or `<b>` into the log messages,
    // since our logging goes to a logfile and will not bee seen in a browser we
    // can remove all tags.
    return strip_tags($message);
  }

  private function formatTags($logEntry) {
    if (!$logEntry['type']) {
      return "";
    }
    // We want to follow Rails convention of specifying tags between brackets.
    // First we make sure to conver the tags to an array
    if (!is_array($logEntry['type'])) {
      $tags = [$logEntry['type']];
    } else {
      $tags = $logEntry['type'];
    }
    return array_reduce($tags, function ($carry, $tag) {
      return $carry . '['. strtoupper($tag) .']';
    });
  }

  private function formatSeverity($logEntry) {
    switch ($logEntry['severity']) {
      case WATCHDOG_EMERGENCY:
        $severity = "EMERGENCY";
        break;
      case WATCHDOG_ALERT:
        $severity = "ALERT";
        break;
      case WATCHDOG_CRITICAL:
        $severity = "CRITICAL";
        break;
      case WATCHDOG_ERROR:
        $severity = "ERROR";
        break;
      case WATCHDOG_WARNING:
        $severity = "WARNING";
        break;
      case WATCHDOG_NOTICE:
        $severity = "NOTICE";
        break;
      case WATCHDOG_INFO:
        $severity = "INFO";
        break;
      case WATCHDOG_DEBUG:
        $severity = "DEBUG";
        break;
    }
    // We want all log messages to be aligned, so the severities are space-padded
    // to take the same space.
    return str_pad($severity, 9, ' ', STR_PAD_LEFT);
  }
}
