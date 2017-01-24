# Command: `config:dump`

Dumps all of the processed configuration properties and their values.

You can optionally filter the type to control the source of data, for example use "yaml" to only return configuration values that were defined in YAML configuration files.

You can also add the `--filter` option with a search value to narrow the results.

## Usage

```shell
$ ssconsole config:dump [<type>] [--filter TERM]
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
| Argument | `type` | No | The configuration type to return | all, yaml, static, overrides | all |
| Option | `--filter` | No | Filter the config by a search term | _string_ | _none_ |

## Example

```
$ ssconsole config:dump --filter default_authenticator
+-------------------------------------+-----------------------+-----+-------------------------------------------+
| Class name                          | Property              | Key | Value                                     |
+-------------------------------------+-----------------------+-----+-------------------------------------------+
| SilverStripe\Security\Authenticator | default_authenticator |     | SilverStripe\Security\MemberAuthenticator |
+-------------------------------------+-----------------------+-----+-------------------------------------------+
```
