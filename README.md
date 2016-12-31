# silverleague/silverstripe-console

A better console for SilverStripe applications.

## Requirements

* PHP 5.6 or above
* SilverStripe 4.x or above
* Composer

## Installation

It is recommended to install this module globally with composer:

```shell
composer global require silverleague/silverstripe-console
```

Ensure your composer's `bin` folder has been added to your system path.

## Usage

### Menu

To show the console menu, run `ssconsole` from your terminal.

### Running commands

To run a command, choose the desired command from the menu and add it as an argument:

```shell
ssconsole dev:tasks:CleanupTestDatabasesTask
```

## License

This module is licensed under the [MIT license](LICENSE.md).
