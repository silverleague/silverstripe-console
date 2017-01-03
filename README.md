# silverleague/silverstripe-console

[![Scrutinizer](https://img.shields.io/scrutinizer/g/silverleague/silverstripe-console.svg)](https://scrutinizer-ci.com/g/silverleague/silverstripe-console/)

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

### Commands

To show the console menu and list of commands, run `ssconsole` from your terminal.

### Running commands

To run a command, choose the desired command from the menu and add it as an argument:

```shell
# Runs a task
ssconsole dev:tasks:CleanupTestDatabasesTask

# Builds the database and flushed the manifest/cache
ssconsole dev:build --flush
```

### Flushing the manifest

Sometimes you need to flush SilverStripe's manifest/cache while running CLI tasks. For example, if you've added a new `BuildTask`, but it doesn't show up in the SilverStripe console yet.

You can add the `--flush` option to any `ssconsole` command to instruct SilverStripe to flush and rebuild its manifest.

## License

This module is licensed under the [MIT license](LICENSE.md).
