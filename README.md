# Codex.Editor server validation sample

This library allows you to use Codex.Editor server validation. 
You can easily make your client plugins for Codex Editor and then 
extend server Tool which will be able to clean dirty data or handle that.

# Installation

To install lib use composer:
```
composer require codex-team/codex.editor:dev-master
```

# Guide 

Add this line at the top of your PHP script

```php
use \CodexEditor\CodexEditor;
```

this line allows you to get editors class that has the following method:

`sanitize` - return JSON string that can be recorded

# Basic usage

You can get data from editor and send as param to editor's server validator like

```php
$editor = new CodexEditor( $JSONData, $configurationData );
$cleanData = $editor->sanitize();
```

now `$cleanData` is ready to record. 

# Configuration file

You can configure validation rules for different types of CodeX Editor tools.

Sample validation rule set:

```$json
{
  "tools": {
    "header": {
      "text": {
        "type": "string",
        "required": true,
        "allowedTags": "b,i,a[href]"
      },
      "level": {
        "type": "int",
        "canBeOnly: [0, 1, 2]
      }
    }
  }
}
```

Where:

`tools` — array of supported CodeX Editor tools.

`header` — defines `header` tool settings.

`text` and `level` — parameters in `header` tool structure.
 
`text` is a **required** *string*, which will be sanitized except b, i and a[href] tags.  

`level` is an **optional** *integer* that can be only 0, 1 or 2.

`allowedTags` param should follow [HTMLPurifier](https://github.com/ezyang/htmlpurifier]) format.

Another configuration example: [https://github.com/codex-team/codex.editor.backend/blob/beta-editor/tests/samples/test-config-allowed.json](https://github.com/codex-team/codex.editor.backend/blob/beta-editor/tests/samples/test-config-allowed.json)

# Make Tools

If you made client plugin for Codex Editor then you should create configuration rule for your own Tool to validate on server-side.

## Repository 
<a href="https://github.com/codex-team/codex.editor.backend/">https://github.com/codex-team/codex.editor.backend/</a>


## About CodeX
We are small team of Web-developing fans consisting of IFMO students and graduates located in St. Petersburg, Russia. 
Feel free to give us a feedback on <a href="mailto::team@ifmo.su">team@ifmo.su</a>
