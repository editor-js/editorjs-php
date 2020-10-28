# Editor.js PHP

Server-side implementation sample for the Editor.js. It contains data validation, HTML sanitization and converts output from Editor.js to the Block objects.

# Installation

To install lib use composer:
```
composer require codex-team/editor.js:dev-master
```

# Guide 

Add this line at the top of your PHP script

```php
use \EditorJS\EditorJS;
```

this line allows you to get editors class that has the following method:

`getBlocks` - return array of sanitized blocks

# Basic usage

You can get data from editor and send as param to editor's server validator like

```php
try {
    // Initialize Editor backend and validate structure
    $editor = new EditorJS( $data, $configuration );
    
    // Get sanitized blocks (according to the rules from configuration)
    $blocks = $editor->getBlocks();
    
} catch (\EditorJSException $e) {
    // process exception
}
```

Editor.js constructor has the following arguments:

`$data` — JSON string with data from CodeX Editor frontend.

`$configuration` — JSON string with CodeX Editor tools configuration (see an example in the following paragraph).

# Configuration file

You can manually configure validation rules for different types of Editor.js tools (header, paragraph, list, quote and other).
You can also extend configuration with new tools.

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
        "canBeOnly": [2, 3, 4]
      }
    }
  }
}
```

Where:

`tools` — array of supported Editor.js tools.

`header` — defines `header` tool settings.

`text` and `level` — parameters in `header` tool structure.
 
`text` is a **required** *string*, which will be sanitized except *b*, *i* and *a[href]* tags.  

`level` is an **optional** *integer* that can be only 0, 1 or 2.

`allowedTags` param should follow [HTMLPurifier](https://github.com/ezyang/htmlpurifier) format.

#### There are three common parameters for every block:

1. `type` (**required**) — type of the block

|value|description|
|---|---|
|`string`|field with string value|
|`int`/`integer`|field with integer value|
|`bool`/`boolean`|field with boolean value|
|`array`|field with nested fields|

2. `allowedTags` (optional) — HTML tags in string that won't be removed
 
 |value|default|description|
|---|---|---|
|`empty`|yes|all tags will be removed|
|`*`|no|all tags are allowed|

Other values are allowed according to the  [HTMLPurifier](https://github.com/ezyang/htmlpurifier) format.

Example:
```
"paragraph": {
    "text": {
        "type": "string",
        "allowedTags": "i,b,u,a[href]"
    }
}
```

3. `canBeOnly` (optional) — define set of allowed values

Example:
```
"quote": {
      "text": {
        "type": "string"
      },
      "caption": {
        "type": "string"
      },
      "alignment": {
        "type": "string",
        "canBeOnly": ["left", "center"]
      }
    }
```

### Short settings syntax

Some syntax sugar has been added.

Tool settings can be a `string`. It defines tool's type with default settings.
```json
"header": {
  "text": "string",
  "level": "int"
}
```

It evaluates to:
```json
"header": {
  "text": {
    "type": "string",
    "allowedTags": "",
    "required": true
  },
  "level": {
    "type": "int",
    "allowedTags": "",
    "required": true
  }
}
```

Tool settings can be an `array`. It defines a set of allowed values without sanitizing.
```json
"quote": {
  "alignment": ["left", "center"],
  "caption": "string"
}
```

It evaluates to:
```json
"quote": {
  "alignment": {
    "type": "string",
    "canBeOnly": ["left", "center"]
  },
  "caption": {
      "type": "string",
      "allowedTags": "",
      "required": true
    }
}
```

Another configuration example: [/tests/samples/syntax-sugar.json](/tests/samples/syntax-sugar.json)

### Nested tools

Tools can contain nested values. It is possible with the `array` type.

Let the JSON input be the following:
```
{
    "blocks": [
        "type": list,
        "data": {
            "items": [
                "first", "second", "third"
            ],
            "style": {
                "background-color": "red",
                "font-color": "black"
            }
        }
    ]
}
```

We can define validation rules for this input in the config:
```
"list": {
  "items": {
    "type": "array",
    "data": {
      "-": {
        "type": "string",
        "allowedTags": "i,b,u"
      }
    }
  },
  "style": {
      "type": "array",
      "data": {
        "background-color": {
            "type": "string",
            "canBeOnly": ["red", "blue", "green"]
        },
        "font-color": {
            "type": "string",
            "canBeOnly": ["black", "white"]
        }
      }
  }
}
```

where `data` is the container for values of the array and `-` is the special shortcut for values if the array is sequential.



Another configuration example: [/tests/samples/test-config.json](/tests/samples/test-config.json)

# Exceptions

### EditorJS class
| Exception text                | Cause
| ----------------------------- | ------------------------------------------------
| JSON is empty                 | EditorJS initiated with empty `$json` argument
| Wrong JSON format: `error`    | `json_decode` failed during `$json` processing
| Input is null                 | `json_decode` returned null `$data` object
| Input array is empty          | `$data` is an empty array
| Field \`blocks\` is missing   | `$data` doesn't contain 'blocks' key
| Blocks is not an array        | `$data['blocks']` is not an array
| Block must be an Array        | one element in `$data['blocks']` is not an array

### BlockHandler class
| Exception text        | Cause
| --------------------- | -----------------------------------------------
| Tool \`**TOOL_NAME**\` not found in the configuration         | Configuration file doesn't contain **TOOL_NAME** in `tools{}` dictionary
| Not found required param \`**key**\`                          | **key** tool param exists in configuration but doesn't exist in input data. *(Params are always required by default unless `required: false` is set)*
| Found extra param \`**key**\`                                 | Param **key** exists in input data but doesn't defined in configuration
| Option \`**key**\` with value \`**value**\` has invalid value. Check canBeOnly param. | Parameter must have one of the values from **canBeOnly** array in tool configuration
| Option \`**key**\` with value \`**value**\` must be **TYPE**  | Param must have type which is defined in tool configuration *(string, integer, boolean)*
| Unhandled type \`**elementType**\`                            | Param type in configuration is invalid

### ConfigLoader class
| Exception text                | Cause
| ----------------------------- | ------------------------------------------------
| Configuration data is empty                       | EditorJS initiated with empty `$configuration` argument
| Tools not found in configuration                  | Configuration file doesn't contain `tools` key
| Duplicate tool \`**toolName**\` in configuration  | Configuration file has different tools with the same name

# Make Tools

If you connect a new Tool on the frontend-side, then you should create a configuration rule for that Tool to validate it on server-side.

## Repository 
<a href="https://github.com/codex-editor/editorjs-php/">https://github.com/codex-editor/editorjs-php/</a>


## About CodeX
We are small team of Web-developing fans consisting of IFMO students and graduates located in St. Petersburg, Russia. 
Feel free to give us a feedback on <a href="mailto::team@ifmo.su">team@ifmo.su</a>
