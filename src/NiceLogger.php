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
    // If the log entry contains variables, we must use the t() function to
    // automatically interpolate and escape them into the message.
    if ($logEntry['variables']) {
      $message = t($logEntry['message'], $logEntry['variables']);
    } else {
      $message = $logEntry['message'];
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
    $splittedTags = explode(' ', $logEntry['type']);
    // We want to follow Rails convention of specifying tags between brackets.
    return array_reduce($splittedTags, function ($carry, $tag) {
      return $carry . '['. strtoupper($tag) .']';
    });

  }
}
