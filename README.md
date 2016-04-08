# nice_logger

*Nice Logger* improves default Drupal logging by using a logfile instead the database as logging backend and by providing a much more concise and expressive interface over [Drupal watchdog](https://api.drupal.org/api/drupal/includes%21bootstrap.inc/function/watchdog/7).

## Installing

Currently *Nice Logger* is not available on [Drupal.org](https://www.drupal.org/) yet. Until then, the recommended installation procedure is to clone the repo in your `modules/devel` folder and enable the module with `drush`:

```sh
  drush en -y nice_logger
```

## Features

### Write logs where they belong to

By default Drupal uses de [Dblog module](https://www.drupal.org/documentation/modules/dblog) to store logs in the database. This approach makes the `watchdog` table grow over time and can slow down the database (and thus, the application) in moments of verbose logging.

An alternative provided in Drupal core is the [Syslog module](https://www.drupal.org/documentation/modules/syslog), which uses the Operating System's logging facility. As stated in the documentation this module is not suitable for shared hosting environments and requires a special system configuration.

Nice logger writes the logs into a file which can be stored where you want and inspected using `less`, `tail`, `grep` or any  text editor.

### Short, nice syntax

Drupal `watchdog` may receive up to five variables. Although every of them has its purpose, they make for a ugly interface. The result is something like this:

```php
  <?php
  watchdog("", "Hi I'm an error message", [], WATCHDOG_ERROR);
```

Which *Nice Logger* transforms in something like this:

```php
  <?php
  Log::error("Hi I'm an error message");
```

Both produce a line like this in the log file:
```
[2016-04-09T01:10:01+02:00]     ERROR -- : Hi I'm an error message
```

### Message tagging

Nice Logger allows you to tag log messages.  Tags can be specified using an array or space-separated in a string.  The tags are written between brakets (such as Ruby on Rails `ActiveSupport::TaggedLogging`) and converted to uppercase to make them stand out in logs.

For example, the following code

```php
  <?php
  Log::info("crm", "Requesting USER 24 email");
  Log::error("api login", "Could not authenticate user in available timeframe");
  Log::emergency(["api", "crm"], "Crm is DOWN");
```

will write the following lines in the log file:

```
[2016-04-09T01:10:23+02:00]      INFO -- : [CRM] Requesting USER 24 email
[2016-04-09T01:10:40+02:00]     ERROR -- : [API][LOGIN] Could not authenticate user in available timeframe
[2016-04-09T01:11:03+02:00] EMERGENCY -- : [API][CRM] Crm is DOWN
```

### Expressiveness

Global constants are almost never a great idea. *Nice Logger* allows you to use methods instead of constants to specify the severity of the message.

- Use `Log::info()` instead of `WATCHDOG_INFO`
- Use `Log::error()` instead of `WATCHDOG_ERROR`
- Use `Log::warning()` instead of `WATCHDOG_WARNING`
- and so on... Every watchdog severity has its corresponding method.

### Easy configuration

*Nice Logger* can be easy configured using `settings.php`.

To specify the log file, you must add the following line to your `settings.php`.

```php
<?php
$conf['nice_logger_file'] = 'logs/application.log';
```

Unless you specify it otherwise, *Nice Logger* will write logs to `logs/application.log`.

To specify a minimum log level, you must add the following line to your `settings.php`:

```php
<?php
$conf['nice_logger_level'] = WATCHDOG_INFO;
```

Unless you specify it otherwise, *Nice Logger* will use a minimum log level of DEBUG. Log messages with a level below the minimum specified will not be written in the log.
## Missing Features

The following two features of Drupal `watchdog` have been kept out of *Nice Logger*:

- **Translatable messages**. Log messages are meant to live in logs. When we search a log using `grep`, `tail`, `less`, etc. it is useful to know what are you looking for.  If a certain log message is translated we have to look for it in multiple languages, which is neither friendly or practical.
- **Linkable messages**. `watchdog()` allows to attach a link to a certain log message. Although this could be easily implemented by *Nice logger*, the KISS principle dominates, and it will not be implemented until a proper use-case appears.
