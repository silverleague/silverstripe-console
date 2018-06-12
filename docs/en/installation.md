# Installation

## With Composer

### Globally

It is recommended to install this module globally with composer:

```shell
composer global require silverleague/ssconsole
```

Ensure your composer's `bin` folder has been added to your system path.

### As a project dependency

You can still require this module as a project dependency if you don't want to install it globally, of course:

```shell
composer require --dev silverleague/ssconsole
$ vendor/bin/ssconsole
```

## From source

If you wish to install this module from source, you can clone the repository and symlink `bin/ssconsole` into your system path, for example:

```bash
git clone git@github.com:silverleague/silverstripe-console.git
cd silverstripe-console
chmod u+x bin/console
ln -s "$(pwd)/bin/ssconsole" /usr/local/bin/ssconsole
```
