# Command: `object:debug`

Look up a DataObject by class name and either its ID or an interactive search-by-column.

If no ID is provided then an interactive prompt will ask for the column to search by, then autocomplete all available
values for that class's columns to choose from.

The default output format is JSON. You can add the `--output-table` option to output the results in a table instead.

By default the output will also be sorted by key. To prevent this, add the `--no-sort` option.

## Usage

```shell
$ ssconsole object:debug [options] [--] <object> [<id>]
```

## Options

| Type | Name | Required | Description | Options | Default |
| --- | --- | --- | --- | --- | --- |
| Argument | `object` | Yes | The object class name to look up | _string_ | _none_ |
| Argument | `id` | No | The object ID, if known | _string_ | _Search_ |
| Option | `--no-sort` | No | Do not sort the properties | _string_ | _none_ |
| Option | `--output-table` | No | Output to a table instead of JSON | _string_ | _none_ |

## Example

If you know the object ID:

```
$ ssconsole object:debug Page 1
Object: Page
ID: 1

{
    "CanEditType": "Inherit",
    "CanViewType": "Inherit",
    "ClassName": "Page",
    "Content": "<p>Welcome to SilverStripe! This is the default homepage. You can edit this page by opening <a href=\"admin\/\">the CMS<\/a>.<\/p><p>You can now access the <a href=\"http:\/\/docs.silverstripe.org\">developer documentation<\/a>, or begin the <a href=\"http:\/\/www.silverstripe.org\/learn\/lessons\">SilverStripe lessons<\/a>.<\/p>",
    "Created": "2016-12-23 12:18:16",
    "HasBrokenFile": 0,
    "HasBrokenLink": 0,
    "ID": 1,
    "LastEdited": "2016-12-23 12:18:16",
    "ParentID": 0,
    "RecordClassName": "Page",
    "ShowInMenus": 1,
    "ShowInSearch": 1,
    "Sort": 1,
    "Title": "Home",
    "URLSegment": "home",
    "Version": 1
}
```

If you don't know the object ID, you can search by one of its columns:

```
$ ssconsole object:debug "SilverStripe\Security\Member" --output-table
Choose a column to search by:
  [0 ] ID
  [1 ] ClassName
  [2 ] LastEdited
  [3 ] Created
  [4 ] FirstName
  [5 ] Surname
  [6 ] Email
  [7 ] TempIDHash
  [8 ] TempIDExpired
  [10] AutoLoginHash
  [11] AutoLoginExpired
  [13] Salt
  [15] LockedOutUntil
  [16] Locale
  [17] FailedLoginCount
  [18] DateFormat
  [19] TimeFormat
 > 6
Look up Member by Email: admin [#1]
Object: SilverStripe\Security\Member
ID: 1

+------------------+------------------------------------------+
| Key              | Value                                    |
+------------------+------------------------------------------+
| ClassName        | SilverStripe\Security\Member             |
| Created          | 2016-12-23 12:18:16                      |
| Email            | admin                                    |
| FailedLoginCount | 0                                        |
| FirstName        | Default Admin                            |
| ID               | 1                                        |
| LastEdited       | 2017-01-24 22:51:59                      |
| Locale           | en_US                                    |
| RecordClassName  | SilverStripe\Security\Member             |
| Salt             | 10$f7c0f16c92a9b3ee90893b                |
| TempIDExpired    | 2017-01-14 17:03:29                      |
| TempIDHash       | f3a3fdd979b1a94dd0c7d145e6ce12d18b409823 |
+------------------+------------------------------------------+
```
