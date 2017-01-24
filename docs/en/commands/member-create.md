# Command: `member:create`

Create a new member, and optionally add them to groups. The [`member:change-groups`](member-change-groups.md) command will be chained to the end if you ask for it.

## Usage

```shell
$ ssconsole member:create [<email>] [<username>] [<password>] [<firstname>] [<surname>]
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
| Argument | `email` | Yes | The email address for the member | _string_ | Prompt |
| Argument | `username` | No | The username for the member | _string_ | Prompt |
| Argument | `password` | Yes | A password for the member | _string_ | Prompt |
| Argument | `firstname` | No | The member's first name | _string_ | Prompt |
| Argument | `surname` | No | The member's surname | _string_ | Prompt |

## Example

```
$ ssconsole member:create my@user.com myusername opensesame John James
Member created.
Do you want to assign groups now? yes
Select the groups to add this Member to
  [0] content-authors
  [1] administrators
 > 1
Adding my@user.com to groups: administrators
Groups updated.
```
