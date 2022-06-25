<?php namespace simplehtmldom;

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

include_once 'constants.php';
include_once 'Debug.php';

class HtmlNode
{
	const HDOM_TYPE_ELEMENT = 1;
	const HDOM_TYPE_COMMENT = 2;
	const HDOM_TYPE_TEXT = 3;
	const HDOM_TYPE_ROOT = 5;
	const HDOM_TYPE_UNKNOWN = 6;
	const HDOM_TYPE_CDATA = 7;

	const HDOM_QUOTE_DOUBLE = 0;
	const HDOM_QUOTE_SINGLE = 1;
	const HDOM_QUOTE_NO = 3;

	const HDOM_INFO_BEGIN = 0;
	const HDOM_INFO_END = 1;
	const HDOM_INFO_QUOTE = 2;
	const HDOM_INFO_SPACE = 3;
	const HDOM_INFO_TEXT = 4;
	const HDOM_INFO_INNER = 5;
	const HDOM_INFO_OUTER = 6;
	const HDOM_INFO_ENDSPACE = 7;

	public $nodetype = self::HDOM_TYPE_TEXT;
	public $tag = 'text';
	public $attr = array();
	public $children = array();
	public $nodes = array();
	public $parent = null;
	public $_ = array();
	private $dom = null;

	function __call($func, $args)
	{
		// Allow users to call methods with lower_case syntax
		switch($func)
		{
			case 'children':
				$actual_function = 'childNodes'; break;
			case 'first_child':
				$actual_function = 'firstChild'; break;
			case 'has_child':
				$actual_function = 'hasChildNodes'; break;
			case 'last_child':
				$actual_function = 'lastChild'; break;
			case 'next_sibling':
				$actual_function = 'nextSibling'; break;
			case 'prev_sibling':
				$actual_function = 'previousSibling'; break;
			default:
				trigger_error(
					'Call to undefined method ' . __CLASS__ . '::' . $func . '()',
					E_USER_ERROR
				);
		}

		// phpcs:ignore Generic.Files.LineLength
		Debug::log(__CLASS__ . '->' . $func . '() has been deprecated and will be removed in the next major version of simplehtmldom. Use ' . __CLASS__ . '->' . $actual_function . '() instead.');

		return call_user_func_array(array($this, $actual_function), $args);
	}

	function __construct($dom)
	{
		if ($dom === null) return $this;

		$this->dom = $dom;
		$dom->nodes[] = $this;
	}

	function __debugInfo()
	{
		// Translate node type to human-readable form
		switch($this->nodetype)
		{
			case self::HDOM_TYPE_ELEMENT:
				$nodetype = "HDOM_TYPE_ELEMENT ($this->nodetype)";
				break;
			case self::HDOM_TYPE_COMMENT:
				$nodetype = "HDOM_TYPE_COMMENT ($this->nodetype)";
				break;
			case self::HDOM_TYPE_TEXT:
				$nodetype = "HDOM_TYPE_TEXT ($this->nodetype)";
				break;
			case self::HDOM_TYPE_ROOT:
				$nodetype = "HDOM_TYPE_ROOT ($this->nodetype)";
				break;
			case self::HDOM_TYPE_CDATA:
				$nodetype = "HDOM_TYPE_CDATA ($this->nodetype)";
				break;
			case self::HDOM_TYPE_UNKNOWN:
			default:
				$nodetype = "HDOM_TYPE_UNKNOWN ($this->nodetype)";
		}

		return array(
			'nodetype' => $nodetype,
			'tag' => $this->tag,
			'attributes' => empty($this->attr) ? 'none' : $this->attr,
			'nodes' => empty($this->nodes) ? 'none' : $this->nodes
		);
	}

	function __toString()
	{
		return $this->outertext();
	}

	function clear()
	{
		unset($this->dom); // Break link to origin
		unset($this->parent); // Break link to branch
	}

	/** @codeCoverageIgnore */
	function dump($show_attr = true, $depth = 0)
	{
		echo str_repeat("\t", $depth) . $this->tag;

		if ($show_attr && count($this->attr) > 0) {
			echo '(';
			foreach ($this->attr as $k => $v) {
				echo "[$k]=>\"$v\", ";
			}
			echo ')';
		}

		echo "\n";

		if ($this->nodes) {
			foreach ($this->nodes as $node) {
				$node->dump($show_attr, $depth + 1);
			}
		}
	}

	/** @codeCoverageIgnore */
	function dump_node($echo = true)
	{
		$string = $this->tag;

		if (count($this->attr) > 0) {
			$string .= '(';
			foreach ($this->attr as $k => $v) {
				$string .= "[$k]=>\"$v\", ";
			}
			$string .= ')';
		}

		if (count($this->_) > 0) {
			$string .= ' $_ (';
			foreach ($this->_ as $k => $v) {
				if (is_array($v)) {
					$string .= "[$k]=>(";
					foreach ($v as $k2 => $v2) {
						$string .= "[$k2]=>\"$v2\", ";
					}
					$string .= ')';
				} else {
					$string .= "[$k]=>\"$v\", ";
				}
			}
			$string .= ')';
		}

		if (isset($this->text)) {
			$string .= " text: ({$this->text})";
		}

		$string .= ' HDOM_INNER_INFO: ';

		if (isset($node->_[self::HDOM_INFO_INNER])) {
			$string .= "'" . $node->_[self::HDOM_INFO_INNER] . "'";
		} else {
			$string .= ' NULL ';
		}

		$string .= ' children: ' . count($this->children);
		$string .= ' nodes: ' . count($this->nodes);
		$string .= "\n";

		if ($echo) {
			echo $string;
			return;
		} else {
			return $string;
		}
	}

	function parent($parent = null)
	{
		// I am SURE that this doesn't work properly.
		// It fails to unset the current node from it's current parents nodes or
		// children list first.
		if ($parent !== null) {
			$this->parent = $parent;
			$this->parent->nodes[] = $this;
			$this->parent->children[] = $this;
		}

		return $this->parent;
	}

	function find_ancestor_tag($tag)
	{
		if ($this->parent === null) return null;

		$ancestor = $this->parent;

		while (!is_null($ancestor)) {
			if ($ancestor->tag === $tag) {
				break;
			}

			$ancestor = $ancestor->parent;
		}

		return $ancestor;
	}

	function innertext()
	{
		if (isset($this->_[self::HDOM_INFO_INNER])) {
			$ret = $this->_[self::HDOM_INFO_INNER];
		} elseif (isset($this->_[self::HDOM_INFO_TEXT])) {
			$ret = $this->_[self::HDOM_INFO_TEXT];
		} else {
			$ret = '';
		}

		foreach ($this->nodes as $n) {
			$ret .= $n->outertext();
		}

		return $this->convert_text($ret);
	}

	function outertext()
	{
		if ($this->tag === 'root') {
			return $this->innertext();
		}

		// todo: What is the use of this callback? Remove?
		if ($this->dom && $this->dom->callback !== null) {
			call_user_func_array($this->dom->callback, array($this));
		}

		if (isset($this->_[self::HDOM_INFO_OUTER])) {
			return $this->convert_text($this->_[self::HDOM_INFO_OUTER]);
		}

		if (isset($this->_[self::HDOM_INFO_TEXT])) {
			return $this->convert_text($this->_[self::HDOM_INFO_TEXT]);
		}

		$ret = '';

		if (isset($this->_[self::HDOM_INFO_BEGIN])) {
			$ret = $this->makeup();
		}

		if (isset($this->_[self::HDOM_INFO_INNER])) {
			// todo: <br> should either never have self::HDOM_INFO_INNER or always
			if ($this->tag !== 'br') {
				$ret .= $this->_[self::HDOM_INFO_INNER];
			}
		}

		if ($this->nodes) {
			foreach ($this->nodes as $n) {
				$ret .= $n->outertext();
			}
		}

		if (isset($this->_[self::HDOM_INFO_END]) && $this->_[self::HDOM_INFO_END] != 0) {
			$ret .= '</' . $this->tag . '>';
		}

		return $this->convert_text($ret);
	}

	/**
	 * Returns true if the provided element is a block level element
	 * @link https://www.w3resource.com/html/HTML-block-level-and-inline-elements.php
	 */
	protected function is_block_element($node)
	{
		// todo: When we have the utility class this should be moved there
		return in_array(strtolower($node->tag), array(
			'p',
			'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
			'ol', 'ul',
			'pre',
			'address',
			'blockquote',
			'dl',
			'div',
			'fieldset',
			'form',
			'hr',
			'noscript',
			'table'
		));
	}

	/**
	 * Returns true if the provided element is an inline level element
	 * @link https://www.w3resource.com/html/HTML-block-level-and-inline-elements.php
	 */
	protected function is_inline_element($node)
	{
		// todo: When we have the utility class this should be moved there
		return in_array(strtolower($node->tag), array(
			'b', 'big', 'i', 'small', 'tt',
			'abbr', 'acronym', 'cite', 'code', 'dfn', 'em', 'kbd', 'strong', 'samp', 'var',
			'a', 'bdo', 'br', 'img', 'map', 'object', 'q', 'script', 'span', 'sub', 'sup',
			'button', 'input', 'label', 'select', 'textarea'
		));
	}

	function text($trim = true)
	{
		$ret = '';

		if (strtolower($this->tag) === 'script') {
			$ret = '';
		} elseif (strtolower($this->tag) === 'style') {
			$ret = '';
		} elseif ($this->nodetype === self::HDOM_TYPE_COMMENT) {
			$ret = '';
		} elseif ($this->nodetype === self::HDOM_TYPE_CDATA) {
			$ret = $this->_[self::HDOM_INFO_INNER];
		} elseif ($this->nodetype === self::HDOM_TYPE_UNKNOWN) {
			$ret = '';
		} elseif (isset($this->_[self::HDOM_INFO_INNER])) {
			$ret = $this->_[self::HDOM_INFO_INNER];
		} elseif ($this->nodetype === self::HDOM_TYPE_TEXT) {
			$ret = $this->_[self::HDOM_INFO_TEXT];
		}

		if (is_null($this->nodes)) {
			return '';
		}

		foreach ($this->nodes as $n) {
			if ($this->is_block_element($n)) {

				$block = ltrim($this->convert_text($n->text(false)));

				if (empty($block))
					continue;

				$ret = rtrim($ret) . "\n\n" . $block;

			} elseif ($this->is_inline_element($n)) {
				// todo: <br> introduces code smell because no space but \n
				if (strtolower($n->tag) === 'br') {
					$ret .= $this->dom->default_br_text ?: DEFAULT_BR_TEXT;
				} else {
					$inline = ltrim($this->convert_text($n->text(false)));

					if (empty($inline))
						continue;

					$ret = $ret . $this->convert_text($n->text(false));
				}
			} else {
				$ret .= $this->convert_text($n->text(false));
			}
		}

		// Reduce whitespace at start/end to a single (or none) space
		$ret = preg_replace('/[ \t\n\r\0\x0B\xC2\xA0]+$/u', $trim ? '' : ' ', $ret);
		$ret = preg_replace('/^[ \t\n\r\0\x0B\xC2\xA0]+/u', $trim ? '' : ' ', $ret);

		return $ret;
	}

	function xmltext()
	{
		$ret = $this->innertext();
		$ret = str_ireplace('<![CDATA[', '', $ret);
		$ret = str_replace(']]>', '', $ret);
		return $ret;
	}

	function makeup()
	{
		// text, comment, unknown
		if (isset($this->_[self::HDOM_INFO_TEXT])) {
			return $this->_[self::HDOM_INFO_TEXT];
		}

		$ret = '<' . $this->tag;

		foreach ($this->attr as $key => $val) {

			// skip removed attribute
			if ($val === null || $val === false) { continue; }

			if (isset($this->_[self::HDOM_INFO_SPACE][$key])) {
				$ret .= $this->_[self::HDOM_INFO_SPACE][$key][0];
			} else {
				$ret .= ' ';
			}

			//no value attr: nowrap, checked selected...
			if ($val === true) {
				$ret .= $key;
			} else {
				if (isset($this->_[self::HDOM_INFO_QUOTE][$key])) {
					$quote_type = $this->_[self::HDOM_INFO_QUOTE][$key];
				} else {
					$quote_type = self::HDOM_QUOTE_DOUBLE;
				}

				switch ($quote_type)
				{
					case self::HDOM_QUOTE_SINGLE:
						$quote = '\'';
						$val = htmlentities($val, ENT_QUOTES, $this->dom->target_charset);
						break;
					case self::HDOM_QUOTE_NO:
						$quote = '';
						break;
					case self::HDOM_QUOTE_DOUBLE:
					default:
						$quote = '"';
						$val = htmlentities($val, ENT_COMPAT, $this->dom->target_charset);
				}

				$ret .= $key
				. (isset($this->_[self::HDOM_INFO_SPACE][$key]) ? $this->_[self::HDOM_INFO_SPACE][$key][1] : '')
				. '='
				. (isset($this->_[self::HDOM_INFO_SPACE][$key]) ? $this->_[self::HDOM_INFO_SPACE][$key][2] : '')
				. $quote
				. $val
				. $quote;
			}
		}

		if(isset($this->_[self::HDOM_INFO_ENDSPACE])) {
			$ret .= $this->_[self::HDOM_INFO_ENDSPACE];
		}

		return $ret . '>';
	}

	function find($selector, $idx = null, $lowercase = false)
	{
		$selectors = $this->parse_selector($selector);
		if (($count = count($selectors)) === 0) { return array(); }
		$found_keys = array();

		// find each selector
		for ($c = 0; $c < $count; ++$c) {
			// The change on the below line was documented on the sourceforge
			// code tracker id 2788009
			// used to be: if (($levle=count($selectors[0]))===0) return array();
			if (($levle = count($selectors[$c])) === 0) {
				Debug::log_once('Empty selector (' . $selector . ') matches nothing.');
				return array();
			}

			if (!isset($this->_[self::HDOM_INFO_BEGIN])) {
				Debug::log_once('Invalid operation. The current node has no start tag.');
				return array();
			}

			$head = array($this->_[self::HDOM_INFO_BEGIN] => 1);
			$cmd = ' '; // Combinator

			// handle descendant selectors, no recursive!
			for ($l = 0; $l < $levle; ++$l) {
				$ret = array();

				foreach ($head as $k => $v) {
					$n = ($k === -1) ? $this->dom->root : $this->dom->nodes[$k];
					//PaperG - Pass this optional parameter on to the seek function.
					$n->seek($selectors[$c][$l], $ret, $cmd, $lowercase);
				}

				$head = $ret;
				$cmd = $selectors[$c][$l][6]; // Next Combinator
			}

			foreach ($head as $k => $v) {
				if (!isset($found_keys[$k])) {
					$found_keys[$k] = 1;
				}
			}
		}

		// sort keys
		ksort($found_keys);

		$found = array();
		foreach ($found_keys as $k => $v) {
			$found[] = $this->dom->nodes[$k];
		}

		// return nth-element or array
		if (is_null($idx)) { return $found; }
		elseif ($idx < 0) { $idx = count($found) + $idx; }
		return (isset($found[$idx])) ? $found[$idx] : null;
	}

	function expect($selector, $idx = null, $lowercase = false)
	{
		return $this->find($selector, $idx, $lowercase) ?: null;
	}

	protected function seek($selector, &$ret, $parent_cmd, $lowercase = false)
	{
		list($ps_selector, $tag, $ps_element, $id, $class, $attributes, $cmb) = $selector;
		$nodes = array();

		if ($parent_cmd === ' ') { // Descendant Combinator
			// Find parent closing tag if the current element doesn't have a closing
			// tag (i.e. void element)
			$end = (!empty($this->_[self::HDOM_INFO_END])) ? $this->_[self::HDOM_INFO_END] : 0;
			if ($end == 0 && $this->parent) {
				$parent = $this->parent;
				while ($parent !== null && !isset($parent->_[self::HDOM_INFO_END])) {
					$end -= 1;
					$parent = $parent->parent;
				}
				$end += $parent->_[self::HDOM_INFO_END];
			}

			if ($end === 0) {
				$end = count($this->dom->nodes);
			}

			// Get list of target nodes
			$nodes_start = $this->_[self::HDOM_INFO_BEGIN] + 1;

			// remove() makes $this->dom->nodes non-contiguous; use what is left.
			$nodes = array_intersect_key(
				$this->dom->nodes,
				array_flip(range($nodes_start, $end))
			);
		} elseif ($parent_cmd === '>') { // Child Combinator
			$nodes = $this->children;
		} elseif ($parent_cmd === '+'
			&& $this->parent
			&& in_array($this, $this->parent->children)) { // Next-Sibling Combinator
				$index = array_search($this, $this->parent->children, true) + 1;
				if ($index < count($this->parent->children))
					$nodes[] = $this->parent->children[$index];
		} elseif ($parent_cmd === '~'
			&& $this->parent
			&& in_array($this, $this->parent->children)) { // Subsequent Sibling Combinator
				$index = array_search($this, $this->parent->children, true);
				$nodes = array_slice($this->parent->children, $index);
		}

		// Go throgh each element starting at this element until the end tag
		// Note: If this element is a void tag, any previous void element is
		// skipped.
		foreach($nodes as $node) {
			$pass = true;

			// Skip root nodes
			if(!$node->parent) {
				unset($node);
				continue;
			}

			// Handle 'text' selector
			if($pass && $tag === 'text') {

				if($node->tag === 'text') {
					$ret[array_search($node, $this->dom->nodes, true)] = 1;
				}

				if(isset($node->_[self::HDOM_INFO_INNER])) {
					$ret[$node->_[self::HDOM_INFO_BEGIN]] = 1;
				}

				unset($node);
				continue;

			}

			// Handle 'cdata' selector
			if($pass && $tag === 'cdata') {

				if($node->tag === 'cdata') {
					$ret[$node->_[self::HDOM_INFO_BEGIN]] = 1;
				}

				unset($node);
				continue;

			}

			// Handle 'comment'
			if($pass && $tag === 'comment' && $node->tag === 'comment') {
				$ret[$node->_[self::HDOM_INFO_BEGIN]] = 1;
				unset($node);
				continue;
			}

			// Skip if node isn't a child node (i.e. text nodes)
			if($pass && !in_array($node, $node->parent->children, true)) {
				unset($node);
				continue;
			}

			// Skip if tag doesn't match
			if ($pass && $tag !== '' && $tag !== $node->tag && $tag !== '*') {
				$pass = false;
			}

			// Skip if ID doesn't exist
			if ($pass && $id !== '' && !isset($node->attr['id'])) {
				$pass = false;
			}

			// Check if ID matches
			if ($pass && $id !== '' && isset($node->attr['id'])) {
				// Note: Only consider the first ID (as browsers do)
				$node_id = explode(' ', trim($node->attr['id']))[0];

				if($id !== $node_id) { $pass = false; }
			}

			// Check if all class(es) exist
			if ($pass && $class !== '' && is_array($class) && !empty($class)) {
				if (isset($node->attr['class'])) {
					// Apply the same rules for the pattern and attribute value
					// Attribute values must not contain control characters other than space
					// https://www.w3.org/TR/html/dom.html#text-content
					// https://www.w3.org/TR/html/syntax.html#attribute-values
					// https://www.w3.org/TR/xml/#AVNormalize
					$node_classes = preg_replace("/[\r\n\t\s]+/u", ' ', $node->attr['class']);
					$node_classes = trim($node_classes);
					$node_classes = explode(' ', $node_classes);

					if ($lowercase) {
						$node_classes = array_map('strtolower', $node_classes);
					}

					foreach($class as $c) {
						if(!in_array($c, $node_classes)) {
							$pass = false;
							break;
						}
					}
				} else {
					$pass = false;
				}
			}

			// Check attributes
			if ($pass
				&& $attributes !== ''
				&& is_array($attributes)
				&& !empty($attributes)) {
					foreach($attributes as $a) {
						list (
							$att_name,
							$att_expr,
							$att_val,
							$att_inv,
							$att_case_sensitivity
						) = $a;

						// Handle indexing attributes (i.e. "[2]")
						/**
						 * Note: This is not supported by the CSS Standard but adds
						 * the ability to select items compatible to XPath (i.e.
						 * the 3rd element within it's parent).
						 *
						 * Note: This doesn't conflict with the CSS Standard which
						 * doesn't work on numeric attributes anyway.
						 */
						if (is_numeric($att_name)
							&& $att_expr === ''
							&& $att_val === '') {
								$count = 0;

								// Find index of current element in parent
								foreach ($node->parent->children as $c) {
									if ($c->tag === $node->tag) ++$count;
									if ($c === $node) break;
								}

								// If this is the correct node, continue with next
								// attribute
								if ($count === (int)$att_name) continue;
						}

						// Check attribute availability
						if ($att_inv) { // Attribute should NOT be set
							if (isset($node->attr[$att_name])) {
								$pass = false;
								break;
							}
						} else { // Attribute should be set
							// todo: "plaintext" is not a valid CSS selector!
							if ($att_name !== 'plaintext'
								&& !isset($node->attr[$att_name])) {
									$pass = false;
									break;
							}
						}

						// Continue with next attribute if expression isn't defined
						if ($att_expr === '') continue;

						// If they have told us that this is a "plaintext"
						// search then we want the plaintext of the node - right?
						// todo "plaintext" is not a valid CSS selector!
						if ($att_name === 'plaintext') {
							$nodeKeyValue = $node->text();
						} else {
							$nodeKeyValue = $node->attr[$att_name];
						}

						// If lowercase is set, do a case insensitive test of
						// the value of the selector.
						if ($lowercase) {
							$check = $this->match(
								$att_expr,
								strtolower($att_val),
								strtolower($nodeKeyValue),
								$att_case_sensitivity
							);
						} else {
							$check = $this->match(
								$att_expr,
								$att_val,
								$nodeKeyValue,
								$att_case_sensitivity
							);
						}

						$check = $ps_element === 'not' ? !$check : $check;

						if (!$check) {
							$pass = false;
							break;
						}
					}
			}

			// Found a match. Add to list and clear node
			$pass = $ps_selector === 'not' ? !$pass : $pass;
			if ($pass) $ret[$node->_[self::HDOM_INFO_BEGIN]] = 1;
			unset($node);
		}
	}

	protected function match($exp, $pattern, $value, $case_sensitivity)
	{
		if ($case_sensitivity === 'i') {
			$pattern = strtolower($pattern);
			$value = strtolower($value);
		}

		// Apply the same rules for the pattern and attribute value
		// Attribute values must not contain control characters other than space
		// https://www.w3.org/TR/html/dom.html#text-content
		// https://www.w3.org/TR/html/syntax.html#attribute-values
		// https://www.w3.org/TR/xml/#AVNormalize
		$pattern = preg_replace("/[\r\n\t\s]+/u", ' ', $pattern);
		$pattern = trim($pattern);

		$value = preg_replace("/[\r\n\t\s]+/u", ' ', $value);
		$value = trim($value);

		switch ($exp) {
			case '=':
				return ($value === $pattern);
			case '!=':
				return ($value !== $pattern);
			case '^=':
				return preg_match('/^' . preg_quote($pattern, '/') . '/', $value);
			case '$=':
				return preg_match('/' . preg_quote($pattern, '/') . '$/', $value);
			case '*=':
				return preg_match('/' . preg_quote($pattern, '/') . '/', $value);
			case '|=':
				/**
				 * [att|=val]
				 *
				 * Represents an element with the att attribute, its value
				 * either being exactly "val" or beginning with "val"
				 * immediately followed by "-" (U+002D).
				 */
				return strpos($value, $pattern) === 0;
			case '~=':
				/**
				 * [att~=val]
				 *
				 * Represents an element with the att attribute whose value is a
				 * whitespace-separated list of words, one of which is exactly
				 * "val". If "val" contains whitespace, it will never represent
				 * anything (since the words are separated by spaces). Also if
				 * "val" is the empty string, it will never represent anything.
				 */
				return in_array($pattern, explode(' ', trim($value)), true);
		}

		Debug::log('Unhandled attribute selector: ' . $exp . '!');
		return false;
	}

	protected function parse_selector($selector_string)
	{
		/**
		 * Pattern of CSS selectors, modified from mootools (https://mootools.net/)
		 *
		 * Paperg: Add the colon to the attribute, so that it properly finds
		 * <tag attr:ibute="something" > like google does.
		 *
		 * Note: if you try to look at this attribute, you MUST use getAttribute
		 * since $dom->x:y will fail the php syntax check.
		 *
		 * Notice the \[ starting the attribute? and the @? following? This
		 * implies that an attribute can begin with an @ sign that is not
		 * captured. This implies that an html attribute specifier may start
		 * with an @ sign that is NOT captured by the expression. Farther study
		 * is required to determine of this should be documented or removed.
		 *
		 * Matches selectors in this order:
		 *
		 * [0] - full match
		 *
		 * [1] - pseudo selector
		 *     (?:\:(\w+)\()?
		 *     Matches the pseudo selector (optional)
		 *
		 * [2] - tag name
		 *     ([\w:\*-]*)
		 *     Matches the tag name consisting of zero or more words, colons,
		 *     asterisks and hyphens.
		 *
		 * [3] - pseudo selector
		 *     (?:\:(\w+)\()?
		 *     Matches the pseudo selector (optional)
		 *
		 * [4] - id name
		 *     (?:\#([\w-]+))
		 *     Optionally matches a id name, consisting of an "#" followed by
		 *     the id name (one or more words and hyphens).
		 *
		 * [5] - class names (including dots)
		 *     (?:\.([\w\.-]+))?
		 *     Optionally matches a list of classs, consisting of an "."
		 *     followed by the class name (one or more words and hyphens)
		 *     where multiple classes can be chained (i.e. ".foo.bar.baz")
		 *
		 * [6] - attributes
		 *     ((?:\[@?(?:!?[\w:-]+)(?:(?:[!*^$|~]?=)[\"']?(?:.*?)[\"']?)?(?:\s*?(?:[iIsS])?)?\])+)?
		 *     Optionally matches the attributes list
		 *
		 * [7] - separator
		 *     ([\/, >+~]+)
		 *     Matches the selector list separator
		 */
		// phpcs:ignore Generic.Files.LineLength
		$pattern = "/(?:\:(\w+)\()?([\w:\*-]*)(?:\:(\w+)\()?(?:\#([\w-]+))?(?:|\.([\w\.-]+))?((?:\[@?(?:!?[\w:-]+)(?:(?:[!*^$|~]?=)[\"']?(?:.*?)[\"']?)?(?:\s*?(?:[iIsS])?)?\])+)?(?:\))?(?:\))?([\/, >+~]+)/is";

		preg_match_all(
			$pattern,
			trim($selector_string) . ' ', // Add final ' ' as pseudo separator
			$matches,
			PREG_SET_ORDER
		);

		$selectors = array();
		$result = array();

		foreach ($matches as $m) {
			$m[0] = trim($m[0]);

			// Skip NoOps
			if ($m[0] === '' || $m[0] === '/' || $m[0] === '//') { continue; }

			array_shift($m);

			// Convert to lowercase
			if ($this->dom->lowercase) {
				$m[1] = strtolower($m[1]);
			}

			// Extract classes
			if ($m[4] !== '') { $m[4] = explode('.', $m[4]); }

			/* Extract attributes (pattern based on the pattern above!)

			 * [0] - full match
			 * [1] - attribute name
			 * [2] - attribute expression
			 * [3] - attribute value
			 * [4] - case sensitivity
			 *
			 * Note: Attributes can be negated with a "!" prefix to their name
			 */
			if($m[5] !== '') {
				preg_match_all(
					"/\[@?(!?[\w:-]+)(?:([!*^$|~]?=)[\"']?(.*?)[\"']?)?(?:\s+?([iIsS])?)?\]/is",
					trim($m[5]),
					$attributes,
					PREG_SET_ORDER
				);

				// Replace element by array
				$m[5] = array();

				foreach($attributes as $att) {
					// Skip empty matches
					if(trim($att[0]) === '') { continue; }

					$inverted = (isset($att[1][0]) && $att[1][0] === '!');
					$m[5][] = array(
						$inverted ? substr($att[1], 1) : $att[1], // Name
						(isset($att[2])) ? $att[2] : '', // Expression
						(isset($att[3])) ? $att[3] : '', // Value
						$inverted, // Inverted Flag
						(isset($att[4])) ? strtolower($att[4]) : '', // Case-Sensitivity
					);
				}
			}

			// Sanitize Separator
			if ($m[6] !== '' && trim($m[6]) === '') { // Descendant Separator
				$m[6] = ' ';
			} else { // Other Separator
				$m[6] = trim($m[6]);
			}

			// Clear Separator if it's a Selector List
			if ($is_list = ($m[6] === ',')) { $m[6] = ''; }

			$result[] = $m;

			if ($is_list) { // Selector List
				$selectors[] = $result;
				$result = array();
			}
		}

		if (count($result) > 0) { $selectors[] = $result; }
		return $selectors;
	}

	function __get($name)
	{
		if (isset($this->attr[$name])) {
			return $this->convert_text($this->attr[$name]);
		}

		switch ($name) {
			case 'outertext': return $this->outertext();
			case 'innertext': return $this->innertext();
			case 'plaintext': return $this->text();
			case 'xmltext': return $this->xmltext();
		}

		return false;
	}

	function __set($name, $value)
	{
		switch ($name) {
			case 'outertext':
				$this->_[self::HDOM_INFO_OUTER] = $value;
				break;
			case 'innertext':
				if (isset($this->_[self::HDOM_INFO_TEXT])) {
					$this->_[self::HDOM_INFO_TEXT] = '';
				}
				$this->_[self::HDOM_INFO_INNER] = $value;
				break;
			default: $this->attr[$name] = $value;
		}
	}

	function __isset($name)
	{
		switch ($name) {
			case 'outertext': return true;
			case 'innertext': return true;
			case 'plaintext': return true;
		}

		return isset($this->attr[$name]);
	}

	function __unset($name)
	{
		if (isset($this->attr[$name])) { unset($this->attr[$name]); }
	}

	function convert_text($text)
	{
		$converted_text = $text;

		$sourceCharset = '';
		$targetCharset = '';

		if ($this->dom) {
			$sourceCharset = strtoupper($this->dom->_charset);
			$targetCharset = strtoupper($this->dom->_target_charset);
		}

		if (!empty($sourceCharset) && !empty($targetCharset)) {
			if (strtoupper($sourceCharset) === strtoupper($targetCharset)) {
				$converted_text = $text;
			} elseif ((strtoupper($targetCharset) === 'UTF-8') && (self::is_utf8($text))) {
				Debug::log_once('The source charset was incorrectly detected as ' . $sourceCharset . ' but should have been UTF-8');
				$converted_text = $text;
			} else {
				$converted_text = iconv($sourceCharset, $targetCharset, $text);
			}
		}

		// Lets make sure that we don't have that silly BOM issue with any of the utf-8 text we output.
		if ($targetCharset === 'UTF-8') {
			if (substr($converted_text, 0, 3) === "\xef\xbb\xbf") {
				$converted_text = substr($converted_text, 3);
			}

			if (substr($converted_text, -3) === "\xef\xbb\xbf") {
				$converted_text = substr($converted_text, 0, -3);
			}
		}

		return $converted_text;
	}

	static function is_utf8($str)
	{
		$c = 0; $b = 0;
		$bits = 0;
		$len = strlen($str);
		for($i = 0; $i < $len; $i++) {
			$c = ord($str[$i]);
			if($c > 128) {
				if(($c >= 254)) { return false; }
				elseif($c >= 252) { $bits = 6; }
				elseif($c >= 248) { $bits = 5; }
				elseif($c >= 240) { $bits = 4; }
				elseif($c >= 224) { $bits = 3; }
				elseif($c >= 192) { $bits = 2; }
				else { return false; }
				if(($i + $bits) > $len) { return false; }
				while($bits > 1) {
					$i++;
					$b = ord($str[$i]);
					if($b < 128 || $b > 191) { return false; }
					$bits--;
				}
			}
		}
		return true;
	}

	function get_display_size()
	{
		$width = -1;
		$height = -1;

		if ($this->tag !== 'img') {
			return false;
		}

		// See if there is aheight or width attribute in the tag itself.
		if (isset($this->attr['width'])) {
			$width = $this->attr['width'];
		}

		if (isset($this->attr['height'])) {
			$height = $this->attr['height'];
		}

		// Now look for an inline style.
		if (isset($this->attr['style'])) {
			// Thanks to user gnarf from stackoverflow for this regular expression.
			$attributes = array();

			preg_match_all(
				'/([\w-]+)\s*:\s*([^;]+)\s*;?/',
				$this->attr['style'],
				$matches,
				PREG_SET_ORDER
			);

			foreach ($matches as $match) {
				$attributes[$match[1]] = $match[2];
			}

			// If there is a width in the style attributes:
			if (isset($attributes['width']) && $width == -1) {
				// check that the last two characters are px (pixels)
				if (strtolower(substr($attributes['width'], -2)) === 'px') {
					$proposed_width = substr($attributes['width'], 0, -2);
					// Now make sure that it's an integer and not something stupid.
					if (filter_var($proposed_width, FILTER_VALIDATE_INT)) {
						$width = $proposed_width;
					}
				}
			}

			// If there is a width in the style attributes:
			if (isset($attributes['height']) && $height == -1) {
				// check that the last two characters are px (pixels)
				if (strtolower(substr($attributes['height'], -2)) == 'px') {
					$proposed_height = substr($attributes['height'], 0, -2);
					// Now make sure that it's an integer and not something stupid.
					if (filter_var($proposed_height, FILTER_VALIDATE_INT)) {
						$height = $proposed_height;
					}
				}
			}

		}

		// Future enhancement:
		// Look in the tag to see if there is a class or id specified that has
		// a height or width attribute to it.

		// Far future enhancement
		// Look at all the parent tags of this image to see if they specify a
		// class or id that has an img selector that specifies a height or width
		// Note that in this case, the class or id will have the img subselector
		// for it to apply to the image.

		// ridiculously far future development
		// If the class or id is specified in a SEPARATE css file thats not on
		// the page, go get it and do what we were just doing for the ones on
		// the page.

		$result = array(
			'height' => $height,
			'width' => $width
		);

		return $result;
	}

	function save($filepath = '')
	{
		$ret = $this->outertext();

		if ($filepath !== '') {
			file_put_contents($filepath, $ret, LOCK_EX);
		}

		return $ret;
	}

	function addClass($class)
	{
		if (is_string($class)) {
			$class = explode(' ', $class);
		}

		if (is_array($class)) {
			foreach($class as $c) {
				if (isset($this->class)) {
					if ($this->hasClass($c)) {
						continue;
					} else {
						$this->class .= ' ' . $c;
					}
				} else {
					$this->class = $c;
				}
			}
		}
	}

	function hasClass($class)
	{
		if (is_string($class)) {
			if (isset($this->class)) {
				return in_array($class, explode(' ', $this->class), true);
			}
		}

		return false;
	}

	function removeClass($class = null)
	{
		if (!isset($this->class)) {
			return;
		}

		if (is_null($class)) {
			$this->removeAttribute('class');
			return;
		}

		if (is_string($class)) {
			$class = explode(' ', $class);
		}

		if (is_array($class)) {
			$class = array_diff(explode(' ', $this->class), $class);
			if (empty($class)) {
				$this->removeAttribute('class');
			} else {
				$this->class = implode(' ', $class);
			}
		}
	}

	function getAllAttributes()
	{
		return $this->attr;
	}

	function getAttribute($name)
	{
		return $this->$name;
	}

	function setAttribute($name, $value)
	{
		$this->$name = $value;
	}

	function hasAttribute($name)
	{
		return isset($this->$name);
	}

	function removeAttribute($name)
	{
		unset($this->$name);
	}

	function remove()
	{
		if ($this->parent) {
			$this->parent->removeChild($this);
		}
	}

	function removeChild($node)
	{
		foreach($node->children as $child) {
			$node->removeChild($child);
		}

		// No need to re-index node->children because it is about to be removed!

		foreach($node->nodes as $entity) {
			$enidx = array_search($entity, $node->nodes, true);
			$edidx = array_search($entity, $node->dom->nodes, true);

			if ($enidx !== false) {
				unset($node->nodes[$enidx]);
			}

			if ($edidx !== false) {
				unset($node->dom->nodes[$edidx]);
			}
		}

		// No need to re-index node->nodes because it is about to be removed!

		$nidx = array_search($node, $this->nodes, true);
		$cidx = array_search($node, $this->children, true);
		$didx = array_search($node, $this->dom->nodes, true);

		if ($nidx !== false) {
			unset($this->nodes[$nidx]);
		}

		$this->nodes = array_values($this->nodes);

		if ($cidx !== false) {
			unset($this->children[$cidx]);
		}

		$this->children = array_values($this->children);

		if ($didx !== false) {
			unset($this->dom->nodes[$didx]);
		}

		// Do not re-index dom->nodes because nodes point to other nodes in the
		// array explicitly!

		$node->clear();
	}

	function getElementById($id)
	{
		return $this->find("#$id", 0);
	}

	function getElementsById($id, $idx = null)
	{
		return $this->find("#$id", $idx);
	}

	function getElementByTagName($name)
	{
		return $this->find($name, 0);
	}

	function getElementsByTagName($name, $idx = null)
	{
		return $this->find($name, $idx);
	}

	function parentNode()
	{
		return $this->parent();
	}

	function childNodes($idx = -1)
	{
		if ($idx === -1) {
			return $this->children;
		}

		if (isset($this->children[$idx])) {
			return $this->children[$idx];
		}

		return null;
	}

	function firstChild()
	{
		if (count($this->children) > 0) {
			return $this->children[0];
		}
		return null;
	}

	function lastChild()
	{
		if (count($this->children) > 0) {
			return end($this->children);
		}
		return null;
	}

	function nextSibling()
	{
		if ($this->parent === null) {
			return null;
		}

		$idx = array_search($this, $this->parent->children, true);

		if ($idx !== false && isset($this->parent->children[$idx + 1])) {
			return $this->parent->children[$idx + 1];
		}

		return null;
	}

	function previousSibling()
	{
		if ($this->parent === null) {
			return null;
		}

		$idx = array_search($this, $this->parent->children, true);

		if ($idx !== false && $idx > 0) {
			return $this->parent->children[$idx - 1];
		}

		return null;

	}

	function hasChildNodes()
	{
		return !empty($this->children);
	}

	function nodeName()
	{
		return $this->tag;
	}

	function appendChild($node)
	{
		$node->parent = $this;
		$this->nodes[] = $node;
		$this->children[] = $node;

		if ($this->dom) { // Attach current node to DOM (recursively)
			$children = array($node);

			while($children) {
				$child = array_pop($children);
				$children = array_merge($children, $child->children);

				$this->dom->nodes[] = $child;
				$child->dom = $this->dom;
				$child->_[self::HDOM_INFO_BEGIN] = count($this->dom->nodes) - 1;
				$child->_[self::HDOM_INFO_END] = $child->_[self::HDOM_INFO_BEGIN];
			}

			$this->dom->root->_[self::HDOM_INFO_END] = count($this->dom->nodes) - 1;
		}

		return $this;
	}

}
