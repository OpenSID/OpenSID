# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [2.0-RC2] - 2019-11-09

**Important**: This is a release **candidate**, which means some features might not yet be stable or emit unexpected behavior. Please don't hesitate to report broken or unstable features.

### Added
- Added a `README` file.
- Added a `composer` file.
- Added `.travis.yml` for automated unit tests with `Travis-CI`.
- Added the magic method `__debugInfo` to `HtmlDocument` and `HtmlNode` in order to reduce the memory footprint and to prevent recursion errors when using `print_r` and `var_dump`.
- Added the magic method `__call` to `HtmlDocument` and `HtmlNode` as a wrapper for deprecated methods using the lowercase calling convention (see below).
- Added unit tests `attribute_test.php`, `callback_test.php`, `debug_info_test.php`, `doctype_test.php`, `script_test.php`, `server_side_script_test.php`, `style_test.php` and `dom_manipulation_test.php`.
- Added and extended unit tests for `cdata_test.php` and `comment_test.php`.
- Added a new `Debug` class to inform users about deprecated functions, malformed documents and parsing issues.
- Added full support for `script` element parsing.
### Changed
- Renamed unit test `simple_html_dom_test.php` to `htmldocument_test.php`.
- Renamed unit test `simple_html_dom_node_test.php` to `htmlnode_test.php`.
- Changed the implementation of destructors for better garbage collection.
- Changed how literal elements (`script`, `style`, `cdata`, "comment" and `code`) are handled by `HtmlDocument`.
### Deprecated
- `HtmlDocument::clear()` has been deprecated and will be removed in the next major version of simplehtmldom. Use `unset()` instead.
- `HtmlDocument::load_file()` has been deprecated and will be removed in the next major version of simplehtmldom. Use `HtmlDocument::loadFile()` instead.
- `HtmlNode::children()` has been deprecated and will be removed in the next major version of simplehtmldom. Use `HtmlNode::childNodes()` instead.
- `HtmlNode::first_child()` has been deprecated and will be removed in the next major version of simplehtmldom. Use `HtmlNode::firstChild()` instead.
- `HtmlNode::has_child()` has been deprecated and will be removed in the next major version of simplehtmldom. Use `HtmlNode::hasChild()` instead.
- `HtmlNode::last_child()` has been deprecated and will be removed in the next major version of simplehtmldom. Use `HtmlNode::lastChild()` instead.
- `HtmlNode::next_sibling()` has been deprecated and will be removed in the next major version of simplehtmldom. Use `HtmlNode::nextSibling()` instead.
- `HtmlNode::prev_sibling()` has been deprecated and will be removed in the next major version of simplehtmldom. Use `HtmlNode::previousSibling()` instead.
- Support for Smarty scripts has been deprecated and will be removed in the next major version of simplehtmldom.
- Support for server-side scripts has been deprecated and will be removed in the next major version of simplehtmldom.
### Removed
- Removed the `testcase/` folder as all tests are covered by unit tests inside `tests/`.
### Fixed
- Fixed a bug with boolean attributes that were incorrectly represented with a value of "1" when saving the DOM.
- Fixed a bug with comment and CDATA parsing that could cause an infinite loop if any of these elements contained `script`, `style`, `code`, server-side php or Smarty tags.
- Fixed a bug with comment and CDATA parsing that resulted in whitespace and newlines being removed when loading a document with `$stripRN = true` (default setting).
- Fixed a bug with attribute values that resulted in incorrectly encoded content when using `outertext()`, `innertext()` or `save()`.
- Fixed a bug with charset encoding that resulted in partially encoded documents depending on the use of `outertext()` and `innertext()` [#178](https://sourceforge.net/p/simplehtmldom/bugs/178/)
- Fixed multiple bugs related to DOM manipulation when using `HtmlDocument::createElement()`, `HtmlDocument::createTextNode()` and `HtmlNode::appendChild()`.

## [2.0-RC1] - 2019-10-20

**Important**: This is a release **candidate**, which means some features might not yet be stable or emit unexpected behavior. Please don't hesitate to report broken or unstable features.

### Added
- Added unit tests
  - Added tests for whitespace handling.
  - Added tests for entity decoding.
  - Added tests for node functions after calling remove().
  - Added tests for `maxLen` in `file_get_html`.
  - Added tests for `simple_html_dom_node`.
  - Added tests for `HtmlWeb`.
  - Added test for bug [#172](https://sourceforge.net/p/simplehtmldom/bugs/172/)
- Added optional argument `$trim = true` to `$node->text()`
- Added attribute value normalization
  - https://www.w3.org/TR/html/syntax.html#attribute-values
  - https://www.w3.org/TR/xml/#AVNormalize
- Added automatic HTML entity decoding when loading documents [feature:#52]
- Added [the negation pseudo-class](https://www.w3.org/TR/selectors-3/#negation)
- Added `simple_html_dom::expect()`.
- Added `simple_html_dom_node::expect()`.
- Added the ability to parse CDATA sections.
- Added `HtmlWeb` to directly load webpages via cURL or fopen as DOM.
- Added `HtmlDocument`, `HtmlNode`, `HtmlWeb` and `constants` to namespace `simplehtmldom`.
- Added a new element type `HDOM_TYPE_CDATA` for CDATA sections.
- Added full support for parsing comments and CDATA sections.
### Changed
- `simple_html_dom::doc` is now unset after loading the DOM.
- `simple_html_dom::restore_noise()` now clears restored elements.
- `simple_html_dom_node::_[HDOM_INFO_ENDSPACE]` now only exists if needed.
- `simple_html_dom_node::_[HDOM_INFO_SPACE]`
  - Now stores elements by attribute names.
  - Now only exists if needed (defaults to `array(' ', '', '')`).
- `simple_html_dom_node::_[HDOM_INFO_QUOTE]`
  - Now stores elements by attribute names.
  - Now only exists if needed (defaults to `HDOM_QUOTE_DOUBLE`).
- `simple_html_dom_node::text()` now supports all block and inline level elements.
- `simple_html_dom_node::text()` now skips empty block elements.
- `simple_html_dom_node::text()` now properly handles `&nbsp` characters.
- `simple_html_dom_node::removeChild()` now removes all types of childs.
- Increased `MAX_FILE_SIZE` from 0.6 MB (600000 Bytes) to 2.5 MiB (2621440 Bytes)
- `HDOM_INFO_INNER` (innertext) is now stored as part of the owning element.
- Moved and renamed `simple_html_dom` to `HtmlDocument`.
- Moved and renamed `simple_html_dom_node` to `HtmlNode`.
- Moved constants to `constants.php`
- Moved `HDOM_TYPE_*`, `HDOM_INFO_*` and `HDOM_QUOTE_*` constants into `HtmlNode`.
### Removed
- Removed `/example/scraping/example_scraping_general.php`.
- Removed `/example/simple_html_dom_utility.php`.
- Removed `/app`.
- Removed `/testcase/reader`.
- Removed `simple_html_dom_node::tag_start`.
### Fixed
- Fixed fatal error when removing nodes from the DOM (#172)
- Fixed `simple_html_dom::parse()` to work after removing elements from the DOM.
- Fixed `simple_html_dom_node::text()` to properly handle UTF-8 characters.
- Fixed all scripts in the example folder.
- Fixed `file_get_html` to return false if the file size is larger than `maxLen`.
- Fixed a bug that caused the parser to convert UTF-8 to UTF-8 on mistake.
- Fixed `simple_html_dom::loadFile` to properly forward arguments to `simple_html_dom::load_file`.
- Fixed handling of optional closing tags to end on the last element.
- Fixed broken support for `text` nodes when using `find` (#175).

## [1.9] - 2019-05-30
### Added
- Added unit test for bug reports
  - Added test for bug [#153](https://sourceforge.net/p/simplehtmldom/bugs/153/)
  - Added test for bug [#163](https://sourceforge.net/p/simplehtmldom/bugs/163/)
  - Added test for bug [#166](https://sourceforge.net/p/simplehtmldom/bugs/166/)
  - Added test for bug [#169](https://sourceforge.net/p/simplehtmldom/bugs/169/)
- Added unit test for character sets UTF-8, CP1251 and CP1252 (#142)
- Added support for meta charset to parse_charset
- Added detection for CP1251 to parse_charset, using iconv
- Added LICENSE file (MIT) to the project root
- Added functions to `simple_html_dom_node`
  - `remove`: Removes the current node recursively from the DOM tree
  - `removeChild`: Removes a child node recursively from the DOM tree
  - `hasClass`: Checks if the current node has the specified class name
  - `addClass`: Adds one or more classes to the current node
  - `removeClass`: Removes one or more classes from the current node
  - `save`: Saves the current node to disk
### Changed
- Changed manual from custom implementation to MkDocs (https://www.mkdocs.org/)
### Fixed
- Fixed warning when trying to clear() the DOM on a null nodes list (#153)
- Fixed missing whitespace when returning plaintext (#163)
- Fixed broken detection of duplicate attributes (#166)
- Fixed broken detection of CP1252 (ISO-8859-1) documents (#142)
- Fixed error using next-sibling combinator ('E + F') on last child
- Fixed selector parsing for attribute selectors ending on "s" or "i" (#169)

## [1.8.1] - 2019-01-13
### Fixed
- Fixed various bugs related to parsing classes and ids

## [1.8] - 2019-01-13
### Added
- Added documentation for `simple_html_dom_node::find`
- Added documentation for `simple_html_dom_node::parse_selector`
- Added documentation for `simple_html_dom_node::seek`
- Added documentation for `simple_html_dom_node::match`
- Added unit tests for bug reports
  - Added test for bug [#62](https://sourceforge.net/p/simplehtmldom/bugs/62/)
  - Added test for bug [#79](https://sourceforge.net/p/simplehtmldom/bugs/79/)
  - Added test for bug [#144](https://sourceforge.net/p/simplehtmldom/bugs/144/)
- Added unit tests for CSS selectors
- Added ability to define constants before simple_html_dom does
  - 'DEFAULT_TARGET_CHARSET'
  - 'DEFAULT_BR_TEXT'
  - 'DEFAULT_SPAN_TEXT'
  - 'MAX_FILE_SIZE'
- Added support for CSS combinators
  - Added support for Child Combinator (`>`)
  - Added support for Next Sibling Combinator (`+`)
  - Added support for Subsequent Sibling Combinator (`~`)
- Added support for multiclass selectors (`.class.class.class`)
- Added support for multiattribute selectors (`[attr1][attr2][attribute3]`)
- Added support for attribute selectors
  - Added support for pipe selectors (`|=`)
  - Added support for tilde selectors (`~=`)
  - Added support for case sensitivity selectors (`i` and `s`)
- Added unit tests for PHP compatibility to PHP 5.6+
- Added coding standard using PHP_CodeSniffer
### Changed
- Removed automatic filtering of 'tbody' selectors (#79)
  > Remove 'tbody' from all selectors to maintain the previous state!
- Coding standard using PHP_CodeSniffer
### Fixed
- Fixed broken CSS selector attributes with value "0" (#62)
- Fixed broken simple_html_dom::load_file
- Fixed forward slashes in CSS selector breaks value matching using '*=' (#144)
- Fixed Universal Selectors

## [1.7] - 2018-12-10
### Added
- Added code documentation to improve readability
- Added unit tests for `simple_html_dom::$self_closing_tags`
- Added unit tests for `simple_html_dom::$optional_closing_tags`
- Added unit tests for bug reports
  - Added test for bug [#56](https://sourceforge.net/p/simplehtmldom/bugs/56/)
  - Added test for bug [#97](https://sourceforge.net/p/simplehtmldom/bugs/97/)
  - Added test for bug [#116](https://sourceforge.net/p/simplehtmldom/bugs/116/)
  - Added test for bug [#121](https://sourceforge.net/p/simplehtmldom/bugs/127/)
  - Added test for bug [#127](https://sourceforge.net/p/simplehtmldom/bugs/127/)
  - Added test for bug [#154](https://sourceforge.net/p/simplehtmldom/bugs/154/)
  - Added test for bug [#160](https://sourceforge.net/p/simplehtmldom/bugs/160/)
- Added unit tests for memory management of the parser
- Added bit flags to `simple_html_dom::load()`
  - Added bit flag `HDOM_SMARTY_AS_TEXT` to optionally filter Smarty scripts (#154)\
  **Note**: Smarty scripts are no longer filtered by default!\
- Added build script to automate releases
- Added support for attributes without whitespace to separate them
### Changed
- Improved documentation and readability for `$self_closing_tags`
- Improved documentation and readability for `$block_tags`
- Improved documentation and readability for `$optional_closing_tags`
- Updated list of `simple_html_dom::$self_closing_tags`
  - Removed 'spacer' (obsolete)
  - Added 'area'
  - Added 'col'
  - Added 'meta'
  - Added 'param'
  - Added 'source'
  - Added 'track'
  - Added 'wbr'
- Updated list of `simple_html_dom::$optional_closing_tags`
  - Removed "nobr" (obsolete)
  - Added 'th' as closable element to 'td'
  - Added 'td' as closable element to 'th'
  - Added 'optgroup' with 'optgroup' and 'option' as closable elements
  - Added 'optgroup' as closable element to 'option'
  - Added 'rp' with 'rp' and 'rt' as closable elements
  - Added 'rt' with 'rt' and 'rp' as closable elements
- Clarified meaning of `simple_html_dom->parent`
- Changed default `$offset` for `file_get_html()` from -1 to 0 (#161)
- Changed `simple_html_dom::load()` to remove script tags before replacing newline characters
- `simple_html_dom_node::text()` no longer adds whitespace to top level span elements (only to sub-elements)
- `simple_html_dom_node::text()` adds blank lines between paragraphs
- Normalized line endings in the repository to LF via `.gitattributes`
- Improved performance of `simple_html_dom::parse_charset()` by approximately 25%
- Improved performance of `simple_html_dom::parse()` by approximately 10%
### Deprecated
- `str_get_html()` is deprecated and should be replaced by `new simple_html_dom()`
### Removed
- Removed protected function `simple_html_dom::copy_until_char_escaped()`
### Fixed
- Fixed compatibility issues with PHP 7.3
- Fixed typo (#147)
- Fixed handling of incorrectly escaped text (#160)
- Restore functionality of `$maxLen` in `file_get_html()`
- Fixed load_file breaks if an error ocurred in another script

## [1.6] - 2014-05-28
### Added
- Added some ability to insert and create nodes
- Add ability to search the "noise" array

## [1.5] - 2012-09-10
### Added
- Added flag: LOCK_EX while calling "file_put_contents()"
- Added support for detecting the source html character set. This is used to convert characters when plaintext is requested.
- Other little fixes and features, too numerous to categorize
### Changed
- Error of "file_get_contents()" will be thrown as an exception
### Fixed
- Fixed the typo of "token_blank_t"
- Memory leak fixed

## [1.11] - 2008-12-14
### Added
- Supports xpath generated from Firebug
- New method "dump" of "simple_html_dom_node"
- New attribute "xmltext" of "simple_html_dom_node"
### Changed
- Remove preg_quote on selector match function: `[attribute*=value]`
- Element "Comment" will treat as children
### Fixed
- Fixed the problem with `<pre>`
- Fixed bug #2207477 (does not load some pages properly)
- Fixed bug #2315853 (Error with character after < sign)

## [1.10] - 2008-10-25
### Changed
- Negative indexes supports of "find" method, thanks for Vadim Voituk
- Constructor with automatically load contents either text or file/url, thanks for Antcs
- Fully supports wildcard in selectors
### Fixed
- Fixed bug of confusing by the < symbol inside the text
- Fixed bug of dash in selectors
- Fixed bug of `<nobr>`
- Fixed bug #2155883 (Nested List Parses Incorrectly)
- Fixed bug #2155113 (error with unclosed html tags)

## [1.00] - 2008-09-05
### Added
- New method "getAllAttributes" of "simple_html_dom_node"
- Supports full javascript string in selector: `$e->find("a[onclick=alert('hello')]")`
### Changed
- Changed selector "*=" to case-insentive
### Fixed
- Fixed the bug of selector in some critical conditions
- Fixed the bug of striping php tags
- Fixed the bug of remove_noise()
- Fixed the bug of noise in attributes

## [0.99] - 2008-08-03
### Changed
- Performance tuning (boost 10%)
- Memory requirement reduced by 25%
- Changed function name from "file_get_dom()" to "file_get_html()"
- Changed function name from "str_get_dom()" to "str_get_html()"
### Fixed
- Fixed bug #2011286 (Error with unclosed html tags)
- Fixed bug #2012551 (Error parsing divs)
- Fixed bug #2020924 (Error for missed tag)
- Fixed bug (problem with `<body>` tag's innertext)

## [0.98] - 2008-06-24
### Added
- Supports "multiple class" selector feature: `<div class="a b c"></div>`
- New "callback function" feature
- New "multiple selectors" feature: $dom->find('p,a,b')
- New examples
- Supports extract contents from HTML features:  $dom->plaintext
### Changed
- Performance tuning (boost 20%)
- Changed simple_html_dom_node method name from "text()" to "makeup()"
### Fixed
- Fixed the bug of $dom->clear()
- Fixed the bug of text nodes' innertext
- Fixed the bug of comment nodes' innertext
- Fixed the bug of decendent selector with optional tags

## [0.97] - 2008-05-09
### Added
- New node type "comment" (eg. $dom->find('comment'))
- Add self-closing tags: 'base', 'spacer'
- New example "simple_html_dom_utility.php"
### Changed
- File and class name changed (html_dom_parser->simple_html_dom)
### Removed
- ($dom->save_file) will not support anymore
- Remove example "example_customize_parser.php"
### Fixed
- Fixed the bug of outertext (th)
- Fixed the bug of regular expression escaping chars ($dom->find)
- Fixed the bug while line-breaker and "\t" in tags

## [0.96] - 2008-04-27
### Added
- Reference section in manual
- Added traverse section in manual
- Added the solution while server behind proxy in FAQ (Thanks to Yousuke Shaggy)
- New method to remove attribute.
- New DOM operations(first_child, last_child, next_sibling, previous_sibling) (Request #1936000)
### Changed
- Now file_get_dom supports full file_get_contents parameters
### Fixed
- Fixed the bug of self-closing tags in the end of file
- Fixed the bug of blanks in the end of tag
- Fixed some typo of testcase

## [0.95] - 2008-04-13
### Added
- Supports tag name with namespace
### Changed
- New attribute filters (Thanks to Yousuke Kumakura)
- Refine structure of testcase
### Fixed
- Fix the bug of optional-closing tags
- Fix the bug of parsing the line break next to the tag's name

## [0.94] - 2008-04-06
### Added
- Add FAQ section in manual
### Fixed
- Fixed infinity loop while the source content is BAD HTML
- Fixed the bug of adding new attributes to self closing tags
- Fixed the bug of customize parser without $dom->remove_noise()