# Codex.Editor server validation sample

This library allows you to use Codex.Editor server validation. 
You can easily make your client plugins for Codex Editor and then 
extend server Tool which will be able to clean dirty data or handle that.

# Installation

To install lib use composer:
```composer require codex-team/codex.editor:dev-master```

# Guide 

Add this line at the top of your PHP script

```use \CodexEditor\CodexEditor;`

this line allows you to get editors class that has such methods:

```getBlocks``` - returns block data as Array
```getData``` - return JSON string that can be recorded

# Basic usage

You can get data from editor and send as param to editor's server validator like
```$editor = new CodexEditor( $JSONData );\n $cleanData = $editor->getData(); ```

now ```$cleanData``` is ready to record. 

# Make Tools

If you made client plugin for Codex Editor then you should make your own Tool to validate on server-side.
Put your class with `tool` name in `CodexEditor\Tools\`

Your class should extend abstract `Base` class that has abstract methods as:
`initialize`, `validate`, `sanitize` - that must be defined in your tool

If you want your plugin to have an oportunity to use HTMLpurifier library,
your class must implement basic `HTMLPurifyable` interface


## Repository 
<a href="https://github.com/codex-team/codex.editor.backend/">https://github.com/codex-team/codex.editor.backend/</a>


## About CodeX
We are small team of Web-developing fans consisting of IFMO students and graduates located in St. Petersburg, Russia. 
Feel free to give us a feedback on <a href="mailto::team@ifmo.su">team@ifmo.su</a>