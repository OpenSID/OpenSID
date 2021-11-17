# PHP Simple HTML DOM Parser

[![LICENSE](https://img.shields.io/github/license/simplehtmldom/simplehtmldom?logo=github&style=for-the-badge)](https://github.com/simplehtmldom/simplehtmldom/blob/master/LICENSE)
[![RELEASE](https://img.shields.io/github/v/tag/simplehtmldom/simplehtmldom?label=release&logo=github&style=for-the-badge)](https://sourceforge.com/projects/simplehtmldom/files/simplehtmldom/)
[![BUILD STATUS](https://img.shields.io/travis/com/simplehtmldom/simplehtmldom?logo=travis&style=for-the-badge)](https://travis-ci.com/simplehtmldom/simplehtmldom)
[![PACKAGIST](https://img.shields.io/packagist/v/simplehtmldom/simplehtmldom?logo=composer&style=for-the-badge)](https://packagist.org/packages/simplehtmldom/simplehtmldom)

simplehtmldom is a fast and reliable HTML DOM parser for PHP.

## Key features

* Purely PHP-based DOM parser (no XML extensions required).
* Works with well-formed and broken HTML documents.
* Loads webpages, local files and document strings.
* Supports CSS selectors.

## Requirements

simplehtmldom requires **PHP 5.6 or higher** with [ext-iconv](https://www.php.net/manual/en/book.iconv.php) enabled. Following extensions enable additional features of the parser:

* [ext-mbstring](https://secure.php.net/manual/en/book.mbstring.php) (recommended) \
Enables better detection for multi-byte documents.
* [ext-curl](https://secure.php.net/manual/en/book.curl.php) \
Enables cURL support for the class `HtmlWeb`.
* [ext-openssl](https://secure.php.net/manual/en/book.openssl.php) (recommended when using cURL) \
Enables SSL support for cURL.

## Installation

**Manually**:

Download the latest release from [SourceForge](https://sourceforge.net/projects/simplehtmldom/files/latest) and extract the files in the vendor folder of your project.

**Composer**:

```sh
composer require simplehtmldom/simplehtmldom
```

**Git**:

```
git clone git://git.code.sf.net/p/simplehtmldom/repository simplehtmldom
```

_Note_: The [GitHub repository](https://github.com/simplehtmldom/simplehtmldom) serves as a mirror for the SourceForge project. We currently accept pull requests and issues only via SourceForge.

## Usage

This example illustrates how to return the page title:

<details><summary>Manually</summary>

```
<?php
include_once 'HtmlWeb.php';
use simplehtmldom\HtmlWeb;

$client = new HtmlWeb();
$html = $client->load('https://www.google.com/search?q=simplehtmldom');

// Returns the page title
echo $html->find('title', 0)->plaintext . PHP_EOL;
```

</details>

<details><summary>Using composer</summary>

```
<?php
include_once 'vendor/autoload.php';
use simplehtmldom\HtmlWeb;

$client = new HtmlWeb();
$html = $client->load('https://www.google.com/search?q=simplehtmldom');

// Returns the page title
echo $html->find('title', 0)->plaintext . PHP_EOL;
```

</details>

Find more examples in the installation folder under `examples`.

## Documentation

The documentation for this library is hosted at [https://simplehtmldom.sourceforge.io/docs/](https://simplehtmldom.sourceforge.io/docs/)

## Getting involved

There are various ways for you to get involved with simplehtmldom. Here are a few:

* Share this project with your friends (Twitter, Facebook, ..._you name it_...).
* Report [bugs](https://sourceforge.net/p/simplehtmldom/bugs/) (SourceForge).
* Request [features](https://sourceforge.net/p/simplehtmldom/feature-requests/) (SourceForge).
* Discuss existing bugs, features and ideas.

If you want to contribute code to the project, please open a [feature request](https://sourceforge.net/p/simplehtmldom/feature-requests/) and include your patch with the message.

## Authors

 * [S.C. Chen](https://sourceforge.net/u/me578022/)
 * [John Schlick](https://sourceforge.net/u/john_schlick/)
 * [logmanoriginal](https://sourceforge.net/u/logmanoriginal/)
 * Rus Carroll
 * Yousuke Kumakura
 * Vadim Voituk

## License

The source code for simplehtmldom is licensed under the MIT license. For further information read the LICENSE file in the root directory (should be located next to this README file).

## Technical notes

simplehtmldom is a purely PHP-based DOM parser that doesn't rely on external libraries like [libxml](https://www.php.net/manual/en/book.libxml.php), [SimpleXML](https://www.php.net/manual/en/book.simplexml.php) or [PHP DOM](https://www.php.net/manual/en/book.dom.php). Doing so provides better control over the parsing algorithm and a much simpler API that even novice users can learn to use in a short amount of time.
