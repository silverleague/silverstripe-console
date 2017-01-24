# silverstripe/ssconsole

A useful command line interface for SilverStripe developers.

This documentation section contains instructions and examples of the commands bundled with this console, and how to use them.

## General

* [Installation](installation.md)
* [Generic commands and options](generic.md)

## Commands

###  Configuration

* [`config:dump`](commands/config-dump.md): Dumps all of the processed configuration properties and their values
* [`config:get`](commands/config-get.md): Look up a specific configuration value

### Development

* [`dev:build`](commands/dev-build.md): Builds the SilverStripe database
* [`dev:task:{task-name}`](commands/dev-tasks.md): Run a native SilverStripe task (automatically populated)

### Membership

* [`member:change-groups`](commands/member-change-groups.md): Change a member's groups
* [`member:change-password`](commands/member-change-password.md): Change a member's password
* [`member:create`](commands/member-create.md): Create a new member, and optionally add them to groups

### Object debugging

* [`object:children`](commands/object-children.md): List all child classes of a given class, e.g. "Page"
* [`object:debug`](commands/object-debug.md): Outputs a visual representation of a DataObject
* [`object:extensions`](commands/object-extensions.md): List all Extensions of a given Object, e.g. "Page"
* [`object:lookup`](commands/object-lookup.md): Shows which Object is returned from the Injector
