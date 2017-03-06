# Command: `config:dump`

Dumps all of the processed configuration properties and their values. You can optionally add the `--filter` option 
with a search value to narrow the results.

## Usage

```shell
$ ssconsole config:dump [--filter TERM]
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
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
