# Command: `object:children`

List all child classes of a given class, e.g. "Page", including the module that the child belongs to.

## Usage

```shell
$ ssconsole object:children <object>
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
| Argument | `object` | Yes | The object class name to look up | _string_ | _none_ |

## Example

```
$ ssconsole object:children Page
Child classes for Page:
+---------------------------------------+------------------+
| Class name                            | Module           |
+---------------------------------------+------------------+
| SilverStripe\CMS\Model\ErrorPage      | silverstripe/cms |
| SilverStripe\CMS\Model\RedirectorPage | silverstripe/cms |
| SilverStripe\CMS\Model\VirtualPage    | silverstripe/cms |
+---------------------------------------+------------------+
```
