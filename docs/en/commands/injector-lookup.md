# Command: `injector:lookup`

Shows which class is returned from an Injector reference.

## Usage

```shell
$ ssconsole injector:lookup <className>
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
| Argument | `className` | Yes | The class name or Injector alias to look up | _string_ | _none_ |

## Example

```shell
$ ssconsole injector:lookup "Psr\Log\LoggerInterface"
Psr\Log\LoggerInterface resolves to Monolog\Logger
Module: monolog/monolog
```

## Aliases

For backwards compatibility, `object:lookup` can also be used.
