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
    $message = $this->formatMessage($logEntry);
    if ($tags = $this->formatTags($logEntry)) {
      return "$timestamp $tags $message\n";
    } else {
      return "$timestamp $message\n";
    }
  }

  private function formatTimestamp($logEntry) {
    return date('c', $logEntry['timestamp']);
  }

  private function formatMessage($logEntry) {
    if ($logEntry['variables']) {
      $message = t($logEntry['message'], $logEntry['variables']);
    } else {
      $message = $logEntry['message'];
    }
    return strip_tags($message);
  }
  private function formatTags($logEntry) {
    if (!$logEntry['type']) {
      return "";
    }
    $splittedTags = explode(' ', $logEntry['type']);
    return array_reduce($splittedTags, function ($carry, $tag) {
      return $carry . '['. strtoupper($tag) .']';
    });

  }
}
