# Command: `dev:build`

Builds the SilverStripe database. This is equivalent to `sake dev/build` and `localhost/dev/build` in your browser.

**Note:** Add `--flush` to flush the cache and manifests as well.

## Usage

```shell
$ ssconsole dev:build [--flush]
```

## Options

None, however `--flush` is a globally available option.

## Example

```
$ ssconsole dev:build --flush

Building database foo_bar using SilverStripe\ORM\Connect\MySQL 5.6.34

CREATING DATABASE TABLES

 * SilverStripe\CronTask\CronTaskStatus
 * SilverStripe\SiteConfig\SiteConfig
 * SilverStripe\CMS\Model\SiteTree
 * Page

...
```

**Please note:** The `--quiet` command will not have effect at this time. The SilverStripe `dev/build` process outputs content as it goes, directly to `stdout`. We made the decision not to buffer this output, so that you would still see progress as it runs. There is an active RFC for implementing controllable input/output interfaces for `BuildTask` commands (and other areas where CLI output is required, generally). We will update this command to handle this command when it is available.
