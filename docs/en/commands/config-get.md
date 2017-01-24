# Command: `config:get`

Look up a specific configuration value and output it directly.

This command can be used for build processes, automated scripts, quick checks etc where raw output is required outside of the SilverStripe application.

It will output in the form of PHP's `export_var`.

## Usage

```shell
$ ssconsole config:get [<class>] [<property>]
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
| Argument | `class` | Yes | Class/parent holding configuration | _string_ | _none_ |
| Argument | `property` | Yes | Property to retrieve | _string_ | _none_ |

## Example

```
$ ssconsole config:get "SilverStripe\Security\Member" session_regenerate_id
true
```
