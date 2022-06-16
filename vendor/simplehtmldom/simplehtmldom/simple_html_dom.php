<?php

/**
 * Website: http://sourceforge.net/projects/simplehtmldom/
 * Acknowledge: Jose Solorzano (https://sourceforge.net/projects/php-html/)
 *
 * Licensed under The MIT License
 * See the LICENSE file in the project root for more information.
 *
 * Authors:
 *   S.C. Chen
 *   John Schlick
 *   Rus Carroll
 *   logmanoriginal
 *
 * Contributors:
 *   Yousuke Kumakura
 *   Vadim Voituk
 *   Antcs
 *
 * Version $Rev$
 */

if (defined('DEFAULT_TARGET_CHARSET')) {
	define('\simplehtmldom\DEFAULT_TARGET_CHARSET', DEFAULT_TARGET_CHARSET);
}

if (defined('DEFAULT_BR_TEXT')) {
	define('\simplehtmldom\DEFAULT_BR_TEXT', DEFAULT_BR_TEXT);
}

if (defined('DEFAULT_SPAN_TEXT')) {
	define('\simplehtmldom\DEFAULT_SPAN_TEXT', DEFAULT_SPAN_TEXT);
}

if (defined('MAX_FILE_SIZE')) {
	define('\simplehtmldom\MAX_FILE_SIZE', MAX_FILE_SIZE);
}

include_once 'HtmlDocument.php';
include_once 'HtmlNode.php';

if (!defined('DEFAULT_TARGET_CHARSET')) {
	define('DEFAULT_TARGET_CHARSET', \simplehtmldom\DEFAULT_TARGET_CHARSET);
}

if (!defined('DEFAULT_BR_TEXT')) {
	define('DEFAULT_BR_TEXT', \simplehtmldom\DEFAULT_BR_TEXT);
}

if (!defined('DEFAULT_SPAN_TEXT')) {
	define('DEFAULT_SPAN_TEXT', \simplehtmldom\DEFAULT_SPAN_TEXT);
}

if (!defined('MAX_FILE_SIZE')) {
	define('MAX_FILE_SIZE', \simplehtmldom\MAX_FILE_SIZE);
}

define('HDOM_TYPE_ELEMENT', \simplehtmldom\HtmlNode::HDOM_TYPE_ELEMENT);
define('HDOM_TYPE_COMMENT', \simplehtmldom\HtmlNode::HDOM_TYPE_COMMENT);
define('HDOM_TYPE_TEXT', \simplehtmldom\HtmlNode::HDOM_TYPE_TEXT);
define('HDOM_TYPE_ROOT', \simplehtmldom\HtmlNode::HDOM_TYPE_ROOT);
define('HDOM_TYPE_UNKNOWN', \simplehtmldom\HtmlNode::HDOM_TYPE_UNKNOWN);
define('HDOM_QUOTE_DOUBLE', \simplehtmldom\HtmlNode::HDOM_QUOTE_DOUBLE);
define('HDOM_QUOTE_SINGLE', \simplehtmldom\HtmlNode::HDOM_QUOTE_SINGLE);
define('HDOM_QUOTE_NO', \simplehtmldom\HtmlNode::HDOM_QUOTE_NO);
define('HDOM_INFO_BEGIN', \simplehtmldom\HtmlNode::HDOM_INFO_BEGIN);
define('HDOM_INFO_END', \simplehtmldom\HtmlNode::HDOM_INFO_END);
define('HDOM_INFO_QUOTE', \simplehtmldom\HtmlNode::HDOM_INFO_QUOTE);
define('HDOM_INFO_SPACE', \simplehtmldom\HtmlNode::HDOM_INFO_SPACE);
define('HDOM_INFO_TEXT', \simplehtmldom\HtmlNode::HDOM_INFO_TEXT);
define('HDOM_INFO_INNER', \simplehtmldom\HtmlNode::HDOM_INFO_INNER);
define('HDOM_INFO_OUTER', \simplehtmldom\HtmlNode::HDOM_INFO_OUTER);
define('HDOM_INFO_ENDSPACE', \simplehtmldom\HtmlNode::HDOM_INFO_ENDSPACE);

define('HDOM_SMARTY_AS_TEXT', \simplehtmldom\HDOM_SMARTY_AS_TEXT);

class_alias('\simplehtmldom\HtmlDocument', 'simple_html_dom', true);
class_alias('\simplehtmldom\HtmlNode', 'simple_html_dom_node', true);

function file_get_html(
	$url,
	$use_include_path = false,
	$context = null,
	$offset = 0,
	$maxLen = -1,
	$lowercase = true,
	$forceTagsClosed = true,
	$target_charset = DEFAULT_TARGET_CHARSET,
	$stripRN = true,
	$defaultBRText = DEFAULT_BR_TEXT,
	$defaultSpanText = DEFAULT_SPAN_TEXT)
{
	if($maxLen <= 0) { $maxLen = MAX_FILE_SIZE; }

	$dom = new simple_html_dom(
		null,
		$lowercase,
		$forceTagsClosed,
		$target_charset,
		$stripRN,
		$defaultBRText,
		$defaultSpanText
	);

	$contents = file_get_contents(
		$url,
		$use_include_path,
		$context,
		$offset,
		$maxLen + 1 // Load extra byte for limit check
	);

	if (empty($contents) || strlen($contents) > $maxLen) {
		$dom->clear();
		return false;
	}

	return $dom->load($contents, $lowercase, $stripRN);
}

function str_get_html(
	$str,
	$lowercase = true,
	$forceTagsClosed = true,
	$target_charset = DEFAULT_TARGET_CHARSET,
	$stripRN = true,
	$defaultBRText = DEFAULT_BR_TEXT,
	$defaultSpanText = DEFAULT_SPAN_TEXT)
{
	$dom = new simple_html_dom(
		null,
		$lowercase,
		$forceTagsClosed,
		$target_charset,
		$stripRN,
		$defaultBRText,
		$defaultSpanText
	);

	if (empty($str) || strlen($str) > MAX_FILE_SIZE) {
		$dom->clear();
		return false;
	}

	return $dom->load($str, $lowercase, $stripRN);
}

/** @codeCoverageIgnore */
function dump_html_tree($node, $show_attr = true, $deep = 0)
{
	$node->dump($node);
}
