# nice_logger

*Nice Logger* allows you to substitute ugly calls to [Drupal watchdog](https://api.drupal.org/api/drupal/includes%21bootstrap.inc/function/watchdog/7) with a much more concise and expressive interface.

## Installing

Currently *Nice Logger* is not on [Drupal.org](https://www.drupal.org/) yet. Until then, the recommended installation procedure is to clone the repo in your `modules` folder and enable the module with `drush`:

```sh
  drush en -y nice_logger
```

## Features

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

### Message tagging

Want to add a tag to your message? No problem.

```php
  <?php
  Log::error("Hi I'm an error message", "super fancy tag");
```

### Expressiveness

Global constants are almost never a great idea. *Nice Logger* allows you to use methods instead of constants to specify the severity of the message.

- Use `Log::info()` instead of `WATCHDOG_INFO`
- Use `Log::error()` instead of `WATCHDOG_ERROR`
- Use `Log::warning()` instead of `WATCHDOG_WARNING`
- and so on... Every watchdog severity has its corresponding method.

### Namespaces

*Nice logger* is namespaced using the power of [xautoload](https://www.drupal.org/project/xautoload). This avoids name collisions and allows you to alias the `Log` class however you want.

To use the *Nice logger* namespace take a look below:

```php
  <?php
  use Drupal\nice_logger\Log;
  Log::info("Whoooo, I'm being logged!!")

  // But why write 3 characters when you can write only 1?
  use Drupal\nice_logger\Log as L;
  L::info("Whoooo, I'm being logged again!!")
```

## Missing Features

The following two features of Drupal `watchdog` have been kept out of *Nice Logger*:

- **Translatable messages**. Log messages are meant to live in logs. When we search a log using `grep`, `tail`, `less`, etc. it is useful to know what are you looking for.  If a certain log message is translated we have to look for it in multiple languages, which is neither friendly or practical.
- **Linkable messages**. `watchdog()` allows to attach a link to a certain log message. Although this could be easily implemented by *Nice logger*, the KISS principle dominates, and it will not be implemented until a proper use-case appears.
