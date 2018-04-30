# Command: `member:lock`

Locks a member for the duration of `Member.lock_out_delay_mins` minutes.

## Usage

```shell
$ ssconsole member:lock [<email>]
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
| Argument | `email` | Yes | The email address for the member | _string_ | Prompt |

## Example

```
$ ssconsole member:lock my@user.com
Member my@user.com locked for 15 mins.
```
