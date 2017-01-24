# Command: `object:extensions`

List all Extensions of a given Object, e.g. "Page". Included in the output will be the class name, the module it belongs to, the number of database fields that the extension adds, and whether or not it updates the CMS fields.

## Usage

```shell
$ ssconsole object:extensions <object>
```

## Options

None.

## Example

```
$ ssconsole object:extensions Page
Extensions for Page:
+---------------------------------------------+------------------------+-----------------+--------------------+
| Class name                                  | Module                 | Added DB fields | Updates CMS fields |
+---------------------------------------------+------------------------+-----------------+--------------------+
| SilverStripe\Assets\AssetControlExtension   | silverstripe/framework | 0               | Yes                |
| SilverStripe\CMS\Model\SiteTreeLinkTracking | silverstripe/cms       | 2               | Yes                |
| SilverStripe\ORM\Hierarchy\Hierarchy        | silverstripe/framework | 0               | Yes                |
| SilverStripe\ORM\Versioning\Versioned       | silverstripe/framework | 1               | Yes                |
+---------------------------------------------+------------------------+-----------------+--------------------+
```
