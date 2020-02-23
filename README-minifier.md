# A PHP port of the YUI CSS compressor

This port is based on version 2.4.8 (Jun 12, 2013) of the [YUI compressor](https://github.com/yui/yuicompressor).   
This port contains fixes & features not present in the original YUI compressor.

**Table of Contents**

1.  [Installation & requirements](#install)
2.  [How to use](#howtouse)
3.  [Tests](#tests)
4.  [API Reference](#api)
5.  [Who uses this?](#whousesit)
6.  [Changelog](#changelog)

<a name="install"></a>

## 1. Installation & requirements

### Installation

Use [Composer](http://getcomposer.org/) to include the library into your project:

    $ composer.phar require tubalmartin/cssmin

Require Composer's autoloader file:

```php
<?php

require './vendor/autoload.php';

// Use it!
$compressor = new Minifier;
```

### Requirements

* PHP 5.3.2 or newer with PCRE extension.

<a name="howtouse"></a>

## 2. How to use

There are three ways you can use this library:

1. [PHP](#php)

<a name="php"></a>

### PHP

```php
<?php

// Autoload libraries
require './vendor/autoload.php';

// Extract the CSS code you want to compress from your CSS files
$input_css = file_get_contents('test.css');

// Create a new CSSmin object.
// By default CSSmin will try to raise PHP settings.
// If you don't want CSSmin to raise the PHP settings pass FALSE to
// the constructor i.e. $compressor = new CSSmin(false);
$compressor = new Minifier;

// Set the compressor up before compressing (global setup):

// Keep sourcemap comment in the output.
// Default behavior removes it.
$compressor->keepSourceMapComment();

// Remove important comments from output.
$compressor->removeImportantComments();

// Split long lines in the output approximately every 1000 chars.
$compressor->setLineBreakPosition(1000);

// Override any PHP configuration options before calling run() (optional)
$compressor->setMemoryLimit('256M');
$compressor->setMaxExecutionTime(120);
$compressor->setPcreBacktrackLimit(3000000);
$compressor->setPcreRecursionLimit(150000);

// Compress the CSS code!
$output_css = $compressor->run($input_css);

// You can override any setup between runs without having to create another CSSmin object.
// Let's say you want to remove the sourcemap comment from the output and
// disable splitting long lines in the output.
// You can achieve that using the methods `keepSourceMap` and `setLineBreakPosition`:
$compressor->keepSourceMapComment(false);
$compressor->setLineBreakPosition(0);
$output_css = $compressor->run($input_css); 

// Do whatever you need with the compressed CSS code
echo $output_css;
```

<a name="tests"></a>

## 3. Tests

Tests from YUI compressor have been modified to fit this port.

How to run the test suite:

* Run `php composer.phar install` in project's root to install dependencies. `phpunit` will be installed locally.
* After that, run `phpunit` in the command line:

```
./vendor/bin/phpunit
```

PHPUnit diffing is too simple so when a test fails it's hard to see the actual diff, that's why I've created a 
test runner that displays inline coloured diffs for a failing test. Only one test can be run at a time.

Here's how to use it:

```
./tests/bin/runner -t <expectation-name> [-f <fixture-name>] [--keep-sourcemap] [--remove-important-comments] [--linebreak-position <pos>]
```

<a name="api"></a>

## 4. API Reference

### __construct ([ bool *$raisePhpLimits* ])

Class constructor, creates a new CSSmin object.

**Parameters**

*raisePhpLimits*

If TRUE, CSSmin will try to raise the values of some php configuration options.
Set to FALSE to keep the values of your php configuration options.
Defaults to TRUE.

### run (string *$css*)

Minifies a string of uncompressed CSS code.
`run()` may be called multiple times on a single CSSmin instance.

**Parameters**

*css*

A string of uncompressed CSS code.
CSSmin default value: `''` (empty string).

**Return Values**

A string of compressed CSS code or an empty string if no string is passed.

### keepSourceMapComment (bool *$keepSourceMap*)

Sets whether to keep sourcemap comment `/*# sourceMappingURL=<path> */`in the output.  
CSSmin default behavior: Sourcemap comment gets removed from output.

### removeImportantComments (bool *$removeImportantComments*)

Sets whether to remove important comments from output.  
CSSmin default behavior: Important comments outside declaration blocks are kept in the output.

### setLinebreakPosition (int *$position*)

Some source control tools don't like it when files containing lines longer than, say 8000 characters, are checked in.
The linebreak option is used in that case to split long lines after a specific column.

CSSmin default value: `0` (all CSS code in 1 long line).  
Minimum value supported: `1`.

### setMaxExecutionTime (int *$seconds*)

Sets the `max_execution_time` configuration option for this script

CSSmin default value: `60`  
Values & notes: [max_execution_time documentation](http://php.net/manual/en/info.configuration.php#ini.max-execution-time)

### setMemoryLimit (mixed *$limit*)

Sets the `memory_limit` configuration option for this script

CSSmin default value: `128M`  
Values & notes: [memory_limit documentation](http://php.net/manual/en/ini.core.php#ini.memory-limit)

### setPcreBacktrackLimit (int *$limit*)

Sets the `pcre.backtrack_limit` configuration option for this script

CSSmin default value: `1000000`  
Values & notes: [pcre.backtrack_limit documentation](http://php.net/manual/en/pcre.configuration.php#ini.pcre.backtrack-limit)

### setPcreRecursionLimit (int *$limit*)

Sets the `pcre.recursion_limit` configuration option for this script.

CSSmin default value: `500000`  
Values & notes: [pcre.recursion_limit documentation](http://php.net/manual/en/pcre.configuration.php#ini.pcre.recursion-limit)
