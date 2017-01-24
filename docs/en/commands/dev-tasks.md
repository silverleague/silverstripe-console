# Command: `dev:task:*`

The `dev:task:*` commands are automatically scaffolded from those available in your SilverStripe project.

Those listed will depend on the modules you have installed.

The task name will be a lowercased, dashed version of the name you're used to using in sake. For example, `sake dev/tasks/MyBananaTask` would be `ssconsole dev:tasks:my-banana` in the console. The "Task" suffix is removed, because you've already typed it once!

## Usage

```shell
$ ssconsole dev:tasks:[<task-name>]
```

## Options

None.

## Example

```
$ ssconsole dev:tasks:cleanup-test-databases

dev:tasks:cleanup-test-databases: Deletes all temporary test databases
======================================================================

Running...
----------

Dropped database "ss_tmpdb2688369"
Dropped database "ss_tmpdb6605539"
Dropped database "ss_tmpdb8297369"
Dropped database "ss_tmpdb8323366"
```

**Please note:** As noted in the [`dev-build`](dev-build.md) example, output is not buffered to allow visual progress as the command runs. This means that the `--quiet` command has no effect at this time.
