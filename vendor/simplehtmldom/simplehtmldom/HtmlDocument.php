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
include_once 'HtmlNode.php';
include_once 'Debug.php';

class HtmlDocument
{
	public $root = null;
	public $nodes = array();
	public $callback = null;
	public $lowercase = false;
	public $original_size;
	public $size;

	protected $pos;
	protected $doc;
	protected $char;

	protected $cursor;
	protected $parent;
	protected $noise = array();
	protected $token_blank = " \t\r\n";
	protected $token_equal = ' =/>';
	protected $token_slash = " />\r\n\t";
	protected $token_attr = ' >';

	public $_charset = '';
	public $_target_charset = '';

	public $default_br_text = '';
	public $default_span_text = '';

	protected $self_closing_tags = array(
		'area' => 1,
		'base' => 1,
		'br' => 1,
		'col' => 1,
		'embed' => 1,
		'hr' => 1,
		'img' => 1,
		'input' => 1,
		'link' => 1,
		'meta' => 1,
		'param' => 1,
		'source' => 1,
		'track' => 1,
		'wbr' => 1
	);
	protected $block_tags = array(
		'body' => 1,
		'div' => 1,
		'form' => 1,
		'root' => 1,
		'span' => 1,
		'table' => 1
	);
	protected $optional_closing_tags = array(
		// Not optional, see
		// https://www.w3.org/TR/html/textlevel-semantics.html#the-b-element
		'b' => array('b' => 1),
		'dd' => array('dd' => 1, 'dt' => 1),
		// Not optional, see
		// https://www.w3.org/TR/html/grouping-content.html#the-dl-element
		'dl' => array('dd' => 1, 'dt' => 1),
		'dt' => array('dd' => 1, 'dt' => 1),
		'li' => array('li' => 1),
		'optgroup' => array('optgroup' => 1, 'option' => 1),
		'option' => array('optgroup' => 1, 'option' => 1),
		'p' => array('p' => 1),
		'rp' => array('rp' => 1, 'rt' => 1),
		'rt' => array('rp' => 1, 'rt' => 1),
		'td' => array('td' => 1, 'th' => 1),
		'th' => array('td' => 1, 'th' => 1),
		'tr' => array('td' => 1, 'th' => 1, 'tr' => 1),
	);

	function __call($func, $args)
	{
		// Allow users to call methods with lower_case syntax
		switch($func)
		{
			case 'load_file':
				$actual_function = 'loadFile'; break;
			case 'clear': return; /* no-op */
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

	function __construct(
		$str = null,
		$lowercase = true,
		$forceTagsClosed = true,
		$target_charset = DEFAULT_TARGET_CHARSET,
		$stripRN = true,
		$defaultBRText = DEFAULT_BR_TEXT,
		$defaultSpanText = DEFAULT_SPAN_TEXT,
		$options = 0)
	{
		if ($str) {
			if (preg_match('/^http:\/\//i', $str) || is_file($str)) {
				$this->load_file($str);
			} else {
				$this->load(
					$str,
					$lowercase,
					$stripRN,
					$defaultBRText,
					$defaultSpanText,
					$options
				);
			}
		} else {
			$this->prepare($str, $lowercase, $defaultBRText, $defaultSpanText);
		}
		// Forcing tags to be closed implies that we don't trust the html, but
		// it can lead to parsing errors if we SHOULD trust the html.
		if (!$forceTagsClosed) {
			$this->optional_closing_array = array();
		}

		$this->_target_charset = $target_charset;
	}

	function __debugInfo()
	{
		return array(
			'root' => $this->root,
			'noise' => empty($this->noise) ? 'none' : $this->noise,
			'charset' => $this->_charset,
			'target charset' => $this->_target_charset,
			'original size' => $this->original_size
		);
	}

	function __destruct()
	{
		if (isset($this->nodes)) {
			foreach ($this->nodes as $n) {
				$n->clear();
			}
		}
	}

	function load(
		$str,
		$lowercase = true,
		$stripRN = true,
		$defaultBRText = DEFAULT_BR_TEXT,
		$defaultSpanText = DEFAULT_SPAN_TEXT,
		$options = 0)
	{
		// prepare
		$this->prepare($str, $lowercase, $defaultBRText, $defaultSpanText);

		if ($stripRN) {
			// Temporarily remove any element that shouldn't loose whitespace
			$this->remove_noise("'<\s*script[^>]*>(.*?)<\s*/\s*script\s*>'is");
			$this->remove_noise("'<!\[CDATA\[(.*?)\]\]>'is");
			$this->remove_noise("'<!--(.*?)-->'is");
			$this->remove_noise("'<\s*style[^>]*>(.*?)<\s*/\s*style\s*>'is");
			$this->remove_noise("'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is");

			// Remove whitespace and newlines between tags
			$this->doc = preg_replace('/\>([\t\s]*[\r\n]^[\t\s]*)\</m', '><', $this->doc);

			// Remove whitespace and newlines in text
			$this->doc = preg_replace('/([\t\s]*[\r\n]^[\t\s]*)/m', ' ', $this->doc);

			// Restore temporarily removed elements and calculate new size
			$this->doc = $this->restore_noise($this->doc);
			$this->size = strlen($this->doc);
		}

		$this->remove_noise("'(<\?)(.*?)(\?>)'s", true); // server-side script
		if (count($this->noise)) {
			// phpcs:ignore Generic.Files.LineLength
			Debug::log('Support for server-side scripts has been deprecated and will be removed in the next major version of simplehtmldom.');
		}

		if($options & HDOM_SMARTY_AS_TEXT) { // Strip Smarty scripts
			$this->remove_noise("'(\{\w)(.*?)(\})'s", true);
			// phpcs:ignore Generic.Files.LineLength
			Debug::log('Support for Smarty scripts has been deprecated and will be removed in the next major version of simplehtmldom.');
		}

		// parsing
		$this->parse($stripRN);
		// end
		$this->root->_[HtmlNode::HDOM_INFO_END] = $this->cursor;
		$this->parse_charset();
		$this->decode();
		unset($this->doc);

		// make load function chainable
		return $this;
	}

	function set_callback($function_name)
	{
		$this->callback = $function_name;
	}

	function remove_callback()
	{
		$this->callback = null;
	}

	function save($filepath = '')
	{
		$ret = $this->root->innertext();
		if ($filepath !== '') { file_put_contents($filepath, $ret, LOCK_EX); }
		return $ret;
	}

	function find($selector, $idx = null, $lowercase = false)
	{
		return $this->root->find($selector, $idx, $lowercase);
	}

	function expect($selector, $idx = null, $lowercase = false)
	{
		return $this->root->expect($selector, $idx, $lowercase);
	}

	/** @codeCoverageIgnore */
	function dump($show_attr = true)
	{
		$this->root->dump($show_attr);
	}

	protected function prepare(
		$str, $lowercase = true,
		$defaultBRText = DEFAULT_BR_TEXT,
		$defaultSpanText = DEFAULT_SPAN_TEXT)
	{
		$this->clear();

		$this->doc = trim($str);
		$this->size = strlen($this->doc);
		$this->original_size = $this->size; // original size of the html
		$this->pos = 0;
		$this->cursor = 1;
		$this->noise = array();
		$this->nodes = array();
		$this->lowercase = $lowercase;
		$this->default_br_text = $defaultBRText;
		$this->default_span_text = $defaultSpanText;
		$this->root = new HtmlNode($this);
		$this->root->tag = 'root';
		$this->root->_[HtmlNode::HDOM_INFO_BEGIN] = -1;
		$this->root->nodetype = HtmlNode::HDOM_TYPE_ROOT;
		$this->parent = $this->root;
		if ($this->size > 0) { $this->char = $this->doc[0]; }
	}

	protected function decode()
	{
		foreach($this->nodes as $node) {
			if (isset($node->_[HtmlNode::HDOM_INFO_TEXT])) {
				$node->_[HtmlNode::HDOM_INFO_TEXT] = html_entity_decode(
					$this->restore_noise($node->_[HtmlNode::HDOM_INFO_TEXT]),
					ENT_QUOTES | ENT_HTML5,
					$this->_target_charset
				);
			}
			if (isset($node->_[HtmlNode::HDOM_INFO_INNER])) {
				$node->_[HtmlNode::HDOM_INFO_INNER] = html_entity_decode(
					$this->restore_noise($node->_[HtmlNode::HDOM_INFO_INNER]),
					ENT_QUOTES | ENT_HTML5,
					$this->_target_charset
				);
			}
			if (isset($node->attr) && is_array($node->attr)) {
				foreach($node->attr as $a => $v) {
					if ($v === true) continue;
					$node->attr[$a] = html_entity_decode(
						$v,
						ENT_QUOTES | ENT_HTML5,
						$this->_target_charset
					);
				}
			}
		}
	}

	protected function parse($trim = false)
	{
		while (true) {

			if ($this->char !== '<') {
				$content = $this->copy_until_char('<');

				if ($content !== '') {

					// Skip whitespace between tags? (</a> <b>)
					if ($trim && trim($content) === '') {
						continue;
					}

					$node = new HtmlNode($this);
					++$this->cursor;
					$node->_[HtmlNode::HDOM_INFO_TEXT] = $content;
					$this->link_nodes($node, false);

				}
			}

			if($this->read_tag($trim) === false) {
				break;
			}
		}
	}

	protected function parse_charset()
	{
		$charset = null;

		if (function_exists('get_last_retrieve_url_contents_content_type')) {
			$contentTypeHeader = get_last_retrieve_url_contents_content_type();
			$success = preg_match('/charset=(.+)/', $contentTypeHeader, $matches);
			if ($success) {
				$charset = $matches[1];
			}

			// phpcs:ignore Generic.Files.LineLength
			Debug::log('Determining charset using get_last_retrieve_url_contents_content_type() ' . ($success ? 'successful' : 'failed'));
		}

		if (empty($charset)) {
			// https://www.w3.org/TR/html/document-metadata.html#statedef-http-equiv-content-type
			$el = $this->root->find('meta[http-equiv=Content-Type]', 0, true);

			if (!empty($el)) {
				$fullvalue = $el->content;

				if (!empty($fullvalue)) {
					$success = preg_match(
						'/charset=(.+)/i',
						$fullvalue,
						$matches
					);

					if ($success) {
						$charset = $matches[1];
					}
				}
			}
		}

		if (empty($charset)) {
			// https://www.w3.org/TR/html/document-metadata.html#character-encoding-declaration
			if ($meta = $this->root->find('meta[charset]', 0)) {
				$charset = $meta->charset;
			}
		}

		if (empty($charset)) {
			// Try to guess the charset based on the content
			// Requires Multibyte String (mbstring) support (optional)
			if (function_exists('mb_detect_encoding')) {
				/**
				 * mb_detect_encoding() is not intended to distinguish between
				 * charsets, especially single-byte charsets. Its primary
				 * purpose is to detect which multibyte encoding is in use,
				 * i.e. UTF-8, UTF-16, shift-JIS, etc.
				 *
				 * -- https://bugs.php.net/bug.php?id=38138
				 *
				 * Adding both CP1251/ISO-8859-5 and CP1252/ISO-8859-1 will
				 * always result in CP1251/ISO-8859-5 and vice versa.
				 *
				 * Thus, only detect if it's either UTF-8 or CP1252/ISO-8859-1
				 * to stay compatible.
				 */
				$encoding = mb_detect_encoding(
					$this->doc,
					array( 'UTF-8', 'CP1252', 'ISO-8859-1' )
				);

				if ($encoding === 'CP1252' || $encoding === 'ISO-8859-1') {
					// Due to a limitation of mb_detect_encoding
					// 'CP1251'/'ISO-8859-5' will be detected as
					// 'CP1252'/'ISO-8859-1'. This will cause iconv to fail, in
					// which case we can simply assume it is the other charset.
					if (!@iconv('CP1252', 'UTF-8', $this->doc)) {
						$encoding = 'CP1251';
					}
				}

				if ($encoding !== false) {
					$charset = $encoding;
				}
			}
		}

		if (empty($charset)) {
			Debug::log('Unable to determine charset from source document. Assuming UTF-8');
			$charset = 'UTF-8';
		}

		// Since CP1252 is a superset, if we get one of it's subsets, we want
		// it instead.
		if ((strtolower($charset) == 'iso-8859-1')
			|| (strtolower($charset) == 'latin1')
			|| (strtolower($charset) == 'latin-1')) {
			$charset = 'CP1252';
		}

		return $this->_charset = $charset;
	}

	protected function read_tag($trim)
	{
		if ($this->char !== '<') { // End Of File
			$this->root->_[HtmlNode::HDOM_INFO_END] = $this->cursor;

			// We might be in a nest of unclosed elements for which the end tags
			// can be omitted. Close them for faster seek operations.
			do {
				if (isset($this->optional_closing_tags[strtolower($this->parent->tag)])) {
					$this->parent->_[HtmlNode::HDOM_INFO_END] = $this->cursor;
				}
			} while ($this->parent = $this->parent->parent);

			return false;
		}

		$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next

		if ($trim) { // "<   /html>"
			$this->skip($this->token_blank);
		}

		// End tag: https://dev.w3.org/html5/pf-summary/syntax.html#end-tags
		if ($this->char === '/') {
			$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next

			$tag = $this->copy_until_char('>');
			$tag = $trim ? ltrim($tag, $this->token_blank) : $tag;

			// Skip attributes and whitespace in end tags
			if ($trim && ($pos = strpos($tag, ' ')) !== false) {
				// phpcs:ignore Generic.Files.LineLength
				Debug::log_once('Source document contains superfluous whitespace in end tags (</html   >).');
				$tag = substr($tag, 0, $pos);
			}

			if (strcasecmp($this->parent->tag, $tag)) { // Parent is not start tag
				$parent_lower = strtolower($this->parent->tag);
				$tag_lower = strtolower($tag);
				if (isset($this->optional_closing_tags[$parent_lower]) && isset($this->block_tags[$tag_lower])) {
					$org_parent = $this->parent;

					// Look for the start tag
					while (($this->parent->parent) && strtolower($this->parent->tag) !== $tag_lower){
						// Close any unclosed element with optional end tags
						if (isset($this->optional_closing_tags[strtolower($this->parent->tag)]))
							$this->parent->_[HtmlNode::HDOM_INFO_END] = $this->cursor;
						$this->parent = $this->parent->parent;
					}

					// No start tag, close grandparent
					if (strtolower($this->parent->tag) !== $tag_lower) {
						$this->parent = $org_parent;

						if ($this->parent->parent) {
							$this->parent = $this->parent->parent;
						}

						$this->parent->_[HtmlNode::HDOM_INFO_END] = $this->cursor;
						return $this->as_text_node($tag);
					}
				} elseif (($this->parent->parent) && isset($this->block_tags[$tag_lower])) {
					// grandparent exists + current is block tag
					// Parent has no end tag
					$this->parent->_[HtmlNode::HDOM_INFO_END] = 0;
					$org_parent = $this->parent;

					// Find start tag
					while (($this->parent->parent) && strtolower($this->parent->tag) !== $tag_lower) {
						$this->parent = $this->parent->parent;
					}

					// No start tag, close parent
					if (strtolower($this->parent->tag) !== $tag_lower) {
						$this->parent = $org_parent; // restore origonal parent
						$this->parent->_[HtmlNode::HDOM_INFO_END] = $this->cursor;
						return $this->as_text_node($tag);
					}
				} elseif (($this->parent->parent) && strtolower($this->parent->parent->tag) === $tag_lower) {
					// Grandparent exists and current tag closes it
					$this->parent->_[HtmlNode::HDOM_INFO_END] = 0;
					$this->parent = $this->parent->parent;
				} else { // Random tag, add as text node
					return $this->as_text_node($tag);
				}
			}

			// Link with start tag
			$this->parent->_[HtmlNode::HDOM_INFO_END] = $this->cursor;

			if ($this->parent->parent) {
				$this->parent = $this->parent->parent;
			}

			$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
			return true;
		}

		// Start tag: https://dev.w3.org/html5/pf-summary/syntax.html#start-tags
		$node = new HtmlNode($this);
		$node->_[HtmlNode::HDOM_INFO_BEGIN] = $this->cursor++;

		// Tag name
		$tag = $this->copy_until($this->token_slash);

		if (isset($tag[0]) && $tag[0] === '!') { // Doctype, CData, Comment
			if (isset($tag[2]) && $tag[1] === '-' && $tag[2] === '-') { // Comment ("<!--")

				// Go back until $tag only contains start of comment "!--".
				while (strlen($tag) > 3) {
					$this->char = $this->doc[--$this->pos]; // previous
					$tag = substr($tag, 0, strlen($tag) - 1);
				}

				$node->nodetype = HtmlNode::HDOM_TYPE_COMMENT;
				$node->tag = 'comment';

				$data = '';

				// There is a rare chance of empty comment: "<!---->"
				// In which case the current char is the first "-" of the end tag
				// But the comment could also just be a dash: "<!----->"
				while(true) {
					// Copy until first char of end tag
					$data .= $this->copy_until_char('-');

					// Look ahead in the document, maybe we are at the end
					if (($this->pos + 3) > $this->size) { // End of document
						Debug::log('Source document ended unexpectedly!');
						break;
					} elseif (substr($this->doc, $this->pos, 3) === '-->') { // end
						$data .= $this->copy_until_char('>');
						break;
					}

					$data .= $this->char;
					$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				}

				$tag .= $data;
				$tag = $this->restore_noise($tag);

				// Comment starts after "!--" and ends before "--" (5 chars total)
				$node->_[HtmlNode::HDOM_INFO_INNER] = substr($tag, 3, strlen($tag) - 5);
			} elseif (substr($tag, 1, 7) === '[CDATA[') {

				// Go back until $tag only contains start of cdata "![CDATA[".
				while (strlen($tag) > 8) {
					$this->char = $this->doc[--$this->pos]; // previous
					$tag = substr($tag, 0, strlen($tag) - 1);
				}

				// CDATA can contain HTML stuff, need to find closing tags first
				$node->nodetype = HtmlNode::HDOM_TYPE_CDATA;
				$node->tag = 'cdata';

				$data = '';

				// There is a rare chance of empty CDATA: "<[CDATA[]]>"
				// In which case the current char is the first "[" of the end tag
				// But the CDATA could also just be a bracket: "<[CDATA[]]]>"
				while(true) {
					// Copy until first char of end tag
					$data .= $this->copy_until_char(']');

					// Look ahead in the document, maybe we are at the end
					if (($this->pos + 3) > $this->size) { // End of document
						Debug::log('Source document ended unexpectedly!');
						break;
					} elseif (substr($this->doc, $this->pos, 3) === ']]>') { // end
						$data .= $this->copy_until_char('>');
						break;
					}

					$data .= $this->char;
					$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				}

				$tag .= $data;
				$tag = $this->restore_noise($tag);

				// CDATA starts after "![CDATA[" and ends before "]]" (10 chars total)
				$node->_[HtmlNode::HDOM_INFO_INNER] = substr($tag, 8, strlen($tag) - 10);
			} else { // Unknown
				Debug::log('Source document contains unknown declaration: <' . $tag);
				$node->nodetype = HtmlNode::HDOM_TYPE_UNKNOWN;
				$node->tag = 'unknown';
			}

			$node->_[HtmlNode::HDOM_INFO_TEXT] = '<' . $tag . $this->copy_until_char('>');

			if ($this->char === '>') {
				$node->_[HtmlNode::HDOM_INFO_TEXT] .= '>';
			}

			$this->link_nodes($node, true);
			$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
			return true;
		}

		if (!preg_match('/^\w[\w:-]*$/', $tag)) { // Invalid tag name
			$node->_[HtmlNode::HDOM_INFO_TEXT] = '<' . $tag . $this->copy_until('<>');

			if ($this->char === '>') { // End tag
				$node->_[HtmlNode::HDOM_INFO_TEXT] .= '>';
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
			}

			$this->link_nodes($node, false);
			Debug::log('Source document contains invalid tag name: ' . $node->_[HtmlNode::HDOM_INFO_TEXT]);
			return true;
		}

		// Valid tag name
		$node->nodetype = HtmlNode::HDOM_TYPE_ELEMENT;
		$tag_lower = strtolower($tag);
		$node->tag = ($this->lowercase) ? $tag_lower : $tag;

		if (isset($this->optional_closing_tags[$tag_lower])) { // Optional closing tag
			while (isset($this->optional_closing_tags[$tag_lower][strtolower($this->parent->tag)])) {
				// Previous element was the last element of ancestor
				$this->parent->_[HtmlNode::HDOM_INFO_END] = $node->_[HtmlNode::HDOM_INFO_BEGIN] - 1;
				$this->parent = $this->parent->parent;
			}
			$node->parent = $this->parent;
		}

		$guard = 0; // prevent infinity loop

		// [0] Space between tag and first attribute
		$space = array($this->copy_skip($this->token_blank), '', '');

		do { // Parse attributes
			$name = $this->copy_until($this->token_equal);

			if ($name === '' && $this->char !== null && $space[0] === '') {
				break;
			}

			if ($guard === $this->pos) { // Escape infinite loop
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				continue;
			}

			$guard = $this->pos;

			if ($this->pos >= $this->size - 1 && $this->char !== '>') { // End Of File
				Debug::log('Source document ended unexpectedly!');
				$node->nodetype = HtmlNode::HDOM_TYPE_TEXT;
				$node->_[HtmlNode::HDOM_INFO_END] = 0;
				$node->_[HtmlNode::HDOM_INFO_TEXT] = '<' . $tag . $space[0] . $name;
				$node->tag = 'text';
				$this->link_nodes($node, false);
				return true;
			}

			if ($name === '/' || $name === '') { // No more attributes
				break;
			}

			// [1] Whitespace after attribute name
			$space[1] = (strpos($this->token_blank, $this->char) === false) ? '' : $this->copy_skip($this->token_blank);

			$name = $this->restore_noise($name); // might be a noisy name

			if ($this->lowercase) {
				$name = strtolower($name);
			}

			if ($this->char === '=') { // Attribute with value
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				$this->parse_attr($node, $name, $space, $trim); // get attribute value
			} else { // Attribute without value
				$node->_[HtmlNode::HDOM_INFO_QUOTE][$name] = HtmlNode::HDOM_QUOTE_NO;
				$node->attr[$name] = true;
				if ($this->char !== '>') {
					$this->char = $this->doc[--$this->pos];
				} // prev
			}

			// Space before attribute and around equal sign
			if (!$trim && $space !== array(' ', '', '')) {
				// phpcs:ignore Generic.Files.LineLength
				Debug::log_once('Source document contains superfluous whitespace in attributes (<e    attribute  =  "value">). Enable trimming or fix attribute spacing for best performance.');
				$node->_[HtmlNode::HDOM_INFO_SPACE][$name] = $space;
			}

			// prepare for next attribute
			$space = array(
				((strpos($this->token_blank, $this->char) === false) ? '' : $this->copy_skip($this->token_blank)),
				'',
				''
			);
		} while ($this->char !== '>' && $this->char !== '/');

		$this->link_nodes($node, true);

		// Space after last attribute before closing the tag
		if (!$trim && $space[0] !== '') {
			// phpcs:ignore Generic.Files.LineLength
			Debug::log_once('Source document contains superfluous whitespace before the closing braket (<e attribute="value"     >). Enable trimming or remove spaces before closing brackets for best performance.');
			$node->_[HtmlNode::HDOM_INFO_ENDSPACE] = $space[0];
		}

		$rest = ($this->char === '>') ? '' : $this->copy_until_char('>');
		$rest = ($trim) ? trim($rest) : $rest; // <html   /   >

		$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next

		if (trim($rest) === '/') { // Void element
			if ($rest !== '') {
				if (isset($node->_[HtmlNode::HDOM_INFO_ENDSPACE])) {
					$node->_[HtmlNode::HDOM_INFO_ENDSPACE] .= $rest;
				} else {
					$node->_[HtmlNode::HDOM_INFO_ENDSPACE] = $rest;
				}
			}
			$node->_[HtmlNode::HDOM_INFO_END] = 0;
		} elseif (!isset($this->self_closing_tags[strtolower($node->tag)])) {
			$innertext = $this->copy_until_char('<');
			if ($innertext !== '') {
				$node->_[HtmlNode::HDOM_INFO_INNER] = $innertext;
			}
			$this->parent = $node;
		}

		if ($node->tag === 'br') {
			$node->_[HtmlNode::HDOM_INFO_INNER] = $this->default_br_text;
		} elseif ($node->tag === 'script') {
			$data = '';

			// There is a rare chance of empty script: "<script></script>"
			// In which case the current char is the start of the end tag
			// But the script could also just contain tags: "<script><div></script>"
			while(true) {
				// Copy until first char of end tag
				$data .= $this->copy_until_char('<');

				// Look ahead in the document, maybe we are at the end
				if (($this->pos + 9) > $this->size) { // End of document
					Debug::log('Source document ended unexpectedly!');
					break;
				} elseif (substr($this->doc, $this->pos, 8) === '</script') { // end
					$this->skip('>'); // don't include the end tag
					break;
				}

				// Note: A script tag may contain any other tag except </script>
				// which needs to be escaped as <\/script>

				$data .= $this->char;
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
			}

			$node = new HtmlNode($this);
			++$this->cursor;
			$node->_[HtmlNode::HDOM_INFO_TEXT] = $data;
			$this->link_nodes($node, false);
		}

		return true;
	}

	protected function parse_attr($node, $name, &$space, $trim)
	{
		$is_duplicate = isset($node->attr[$name]);

		if (!$is_duplicate) // Copy whitespace between "=" and value
			$space[2] = (strpos($this->token_blank, $this->char) === false) ? '' : $this->copy_skip($this->token_blank);

		switch ($this->char) {
			case '"':
				$quote_type = HtmlNode::HDOM_QUOTE_DOUBLE;
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				$value = $this->copy_until_char('"');
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				break;
			case '\'':
				// phpcs:ignore Generic.Files.LineLength
				Debug::log_once('Source document contains attribute values with single quotes (<e attribute=\'value\'>). Use double quotes for best performance.');
				$quote_type = HtmlNode::HDOM_QUOTE_SINGLE;
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				$value = $this->copy_until_char('\'');
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				break;
			default:
				// phpcs:ignore Generic.Files.LineLength
				Debug::log_once('Source document contains attribute values without quotes (<e attribute=value>). Use double quotes for best performance');
				$quote_type = HtmlNode::HDOM_QUOTE_NO;
				$value = $this->copy_until($this->token_attr);
		}

		$value = $this->restore_noise($value);

		if ($trim) {
			// Attribute values must not contain control characters other than space
			// https://www.w3.org/TR/html/dom.html#text-content
			// https://www.w3.org/TR/html/syntax.html#attribute-values
			// https://www.w3.org/TR/xml/#AVNormalize
			$value = preg_replace("/[\r\n\t\s]+/u", ' ', $value);
			$value = trim($value);
		}

		if (!$is_duplicate) {
			if ($quote_type !== HtmlNode::HDOM_QUOTE_DOUBLE) {
				$node->_[HtmlNode::HDOM_INFO_QUOTE][$name] = $quote_type;
			}
			$node->attr[$name] = $value;
		}
	}

	protected function link_nodes(&$node, $is_child)
	{
		$node->parent = $this->parent;
		$this->parent->nodes[] = $node;
		if ($is_child) {
			$this->parent->children[] = $node;
		}
	}

	protected function as_text_node($tag)
	{
		$node = new HtmlNode($this);
		++$this->cursor;
		$node->_[HtmlNode::HDOM_INFO_TEXT] = '</' . $tag . '>';
		$this->link_nodes($node, false);
		$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
		return true;
	}

	protected function skip($chars)
	{
		$this->pos += strspn($this->doc, $chars, $this->pos);
		$this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
	}

	protected function copy_skip($chars)
	{
		$pos = $this->pos;
		$len = strspn($this->doc, $chars, $pos);
		if ($len === 0) { return ''; }
		$this->pos += $len;
		$this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
		return substr($this->doc, $pos, $len);
	}

	protected function copy_until($chars)
	{
		$pos = $this->pos;
		$len = strcspn($this->doc, $chars, $pos);
		$this->pos += $len;
		$this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
		return substr($this->doc, $pos, $len);
	}

	protected function copy_until_char($char)
	{
		if ($this->char === null) { return ''; }

		if (($pos = strpos($this->doc, $char, $this->pos)) === false) {
			$ret = substr($this->doc, $this->pos, $this->size - $this->pos);
			$this->char = null;
			$this->pos = $this->size;
			return $ret;
		}

		if ($pos === $this->pos) { return ''; }

		$pos_old = $this->pos;
		$this->char = $this->doc[$pos];
		$this->pos = $pos;
		return substr($this->doc, $pos_old, $pos - $pos_old);
	}

	protected function remove_noise($pattern, $remove_tag = false)
	{
		$count = preg_match_all(
			$pattern,
			$this->doc,
			$matches,
			PREG_SET_ORDER | PREG_OFFSET_CAPTURE
		);

		for ($i = $count - 1; $i > -1; --$i) {
			$key = '___noise___' . sprintf('% 5d', count($this->noise) + 1000);

			$idx = ($remove_tag) ? 0 : 1; // 0 = entire match, 1 = submatch
			$this->noise[$key] = $matches[$i][$idx][0];
			$this->doc = substr_replace($this->doc, $key, $matches[$i][$idx][1], strlen($matches[$i][$idx][0]));
		}

		// reset the length of content
		$this->size = strlen($this->doc);

		if ($this->size > 0) {
			$this->char = $this->doc[0];
		}
	}

	function restore_noise($text)
	{
		if (empty($this->noise)) return $text; // nothing to restore
		$pos = 0;
		while (($pos = strpos($text, '___noise___', $pos)) !== false) {
			// Sometimes there is a broken piece of markup, and we don't GET the
			// pos+11 etc... token which indicates a problem outside of us...

			// todo: "___noise___1000" (or any number with four or more digits)
			// in the DOM causes an infinite loop which could be utilized by
			// malicious software
			if (strlen($text) > $pos + 15) {
				$key = '___noise___'
				. $text[$pos + 11]
				. $text[$pos + 12]
				. $text[$pos + 13]
				. $text[$pos + 14]
				. $text[$pos + 15];

				if (isset($this->noise[$key])) {
					$text = substr($text, 0, $pos)
					. $this->noise[$key]
					. substr($text, $pos + 16);

					unset($this->noise[$key]);
				} else {
					Debug::log_once('Noise restoration failed. DOM has been corrupted!');
					// do this to prevent an infinite loop.
					// FIXME: THis causes an infinite loop because the keyword ___NOISE___ is included in the key!
					$text = substr($text, 0, $pos)
					. 'UNDEFINED NOISE FOR KEY: '
					. $key
					. substr($text, $pos + 16);
				}
			} else {
				// There is no valid key being given back to us... We must get
				// rid of the ___noise___ or we will have a problem.
				Debug::log_once('Noise restoration failed. The provided key is incomplete: ' . $text);
				$text = substr($text, 0, $pos)
				. 'NO NUMERIC NOISE KEY'
				. substr($text, $pos + 11);
			}
		}
		return $text;
	}

	function search_noise($text)
	{
		foreach($this->noise as $noiseElement) {
			if (strpos($noiseElement, $text) !== false) {
				return $noiseElement;
			}
		}
	}

	function __toString()
	{
		return $this->root->innertext();
	}

	function __get($name)
	{
		switch ($name) {
			case 'outertext':
				return $this->root->innertext();
			case 'innertext':
				return $this->root->innertext();
			case 'plaintext':
				return $this->root->text();
			case 'charset':
				return $this->_charset;
			case 'target_charset':
				return $this->_target_charset;
		}
	}

	function childNodes($idx = -1)
	{
		return $this->root->childNodes($idx);
	}

	function firstChild()
	{
		return $this->root->firstChild();
	}

	function lastChild()
	{
		return $this->root->lastChild();
	}

	function createElement($name, $value = null)
	{
		$node = new HtmlNode(null);
		$node->nodetype = HtmlNode::HDOM_TYPE_ELEMENT;
		$node->_[HtmlNode::HDOM_INFO_BEGIN] = 1;
		$node->_[HtmlNode::HDOM_INFO_END] = 1;

		if ($value !== null) {
			$node->_[HtmlNode::HDOM_INFO_INNER] = $value;
		}

		$node->tag = $name;

		return $node;
	}

	function createTextNode($value)
	{
		$node = new HtmlNode($this);
		$node->nodetype = HtmlNode::HDOM_TYPE_TEXT;

		if ($value !== null) {
			$node->_[HtmlNode::HDOM_INFO_TEXT] = $value;
		}

		return $node;
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

	function loadFile($file)
	{
		$args = func_get_args();

		if(($doc = call_user_func_array('file_get_contents', $args)) !== false) {
			$this->load($doc, true);
		} else {
			return false;
		}
	}
}
