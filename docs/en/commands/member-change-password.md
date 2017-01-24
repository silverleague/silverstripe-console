# Command: `member:change-password`

Change a member's password.
## Usage

```shell
$ ssconsole member:change-password [<email>] [<password>]
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
| Argument | `email` | Yes | The email address for the member | _string_ | Prompt |
| Argument | `password` | Yes | The new password to set | _string_ | Prompt |

## Example

```
$ ssconsole member:change-password
Enter email address: admin
Enter password:
Password updated.
```

```
$ ssconsole member:change-password admin opensesame
Password updated.
```

**Note:** If you do not provide the password argument, you will be prompted to enter it. In this instance the password will be hidden on entry. This would be preferable if changing someone's password in person.
