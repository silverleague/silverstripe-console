# Command: `object:lookup`

Shows which Object is returned from the Injector

## Usage

```shell
$ ssconsole object:lookup <object>
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
| Argument | `object` | Yes | The object class name or Injector alias to look up | _string_ | _none_ |

## Example

```shell
$ ssconsole object:lookup Logger
Logger resolves to Monolog\Logger
```
