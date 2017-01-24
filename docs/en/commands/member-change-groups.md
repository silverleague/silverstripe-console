# Command: `member:change-groups`

Changes the groups for a member. This command will interactively ask for the groups to assign to the user, which will be _added_ but will not replace existing groups.

## Usage

```shell
$ ssconsole member:change-groups [<email>]
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
| Argument | `email` | Yes | The email address for the member | _string_ | Prompt |

## Example

```
$ ssconsole member:change-groups admin
Member admin is already in the following groups (will be overwritten):
   administrators

Select the groups to add this Member to
  [0] content-authors
  [1] administrators
 > 0
Adding admin to groups: content-authors
Groups updated.
```
