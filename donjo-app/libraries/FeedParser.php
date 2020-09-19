<?php
	/*=======================================================================+
	|                       PHP Universal Feed Parser                        |   
	+------------------------------------------------------------------------/

	Author          : Anis uddin Ahmad <admin@ajaxray.com>
	Web             : http://www.ajaxray.com
	Publish Date    : March 24, 2008

LICENSE
----------------------------------------------------------------------
PHP Universal Feed Parser 1.0 - A PHP class to parse RSS 1.0, RSS 2.0 and ATOM 1.0 feed.
Copyright (C) 2008  Anis uddin Ahmad <admin@ajaxray.com>
	
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License (GPL)
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

To read the license please visit http://www.gnu.org/copyleft/gpl.html
=======================================================================

HOW TO USE
-----------------------------------------------------------------------
It's very easy to use. Just follow this 3 steps:
1. Include the file 
	include('FeedParser.php');
2. Create an object of FeedParser class
	$Parser = new FeedParser();
3. Parse the URL you want to featch
	$Parser->parse('http://www.sitepoint.com/rss.php');
	
Done. 
Now you can use this functions to get various information of parsed feed:
	1. $Parser->getChannels()        - To get all channel elements as array
	2. $Parser->getItems()           - To get all feed elements as array
	3. $Parser->getChannel($name)    - To get a channel element by name
	4. $Parser->getItem($index)      - To get a feed element as array by it's index
	5. $Parser->getTotalItems()      - To get the number of total feed elements
	6. $Parser->getFeedVersion()     - To get the detected version of parsed feed
	7. $Parser->getParsedUrl()       - To get the parsed feed URL  
	
======================================================================= 

IMPORTANT NOTES
-----------------------------------------------------------------------
1. All array keys are must be UPPERCASE
2. All dates are converted to timestamp
3. Attributes of a tag will be found under TAGNAME_ATTRS index
	example: Attributes of $item['GUID'] will be found as $item['GUID_ATTRS']
4. The tags which have subtags will be an array and sub tags will be found as it's element
	example: IMAGE tag in RSS 2.0
========================================================================

EXAMPLES
-----------------------------------------------------------------------
To see more details and examples, please visit:
	http://www.ajaxray.com/blog/2008/05/02/php-universal-feed-parser-lightweight-php-class-for-parsing-rss-and-atom-feeds/
========================================================================
*/

/**
* PHP Univarsel Feed Parser class
*
* Parses RSS 1.0, RSS2.0 and ATOM Feed
* 
* @license     GNU General Public License (GPL)                            
* @author      Anis uddin Ahmad <admin@ajaxray.com>
* @link        http://www.ajaxray.com/blog/2008/05/02/php-universal-feed-parser-lightweight-php-class-for-parsing-rss-and-atom-feeds/
*/
class FeedParser{
		
	private $xmlParser      = null;
	private $insideItem     = array();                  // Keep track of current position in tag tree
	private $currentTag     = null;                     // Last entered tag name      
	private $currentAttr    = null;                     // Attributes array of last entered tag
	
	private $namespaces     = array(
							'http://purl.org/rss/1.0/'                  => 'RSS 1.0',
							'http://purl.org/rss/1.0/modules/content/'  => 'RSS 2.0',
							'http://www.w3.org/2005/Atom'               => 'ATOM 1',
							);                          // Namespaces to detact feed version
	private $itemTags       = array('ITEM','ENTRY');    // List of tag names which holds a feed item
	private $channelTags    = array('CHANNEL','FEED');  // List of tag names which holds all channel elements
	private $dateTags       = array('UPDATED','PUBDATE','DC:DATE');  
	private $hasSubTags     = array('IMAGE','AUTHOR');  // List of tag names which have sub tags
	private $channels       = array();                  
	private $items          = array();
	private $itemIndex      = 0;

	private $url            = null;                     // The parsed url
	private $version        = null;                     // Detected feed version 
	
	   
	/**
	* Constructor - Initialize and set event handler functions to xmlParser
	*/    
	function __construct()
	{
		$this->xmlParser = xml_parser_create();
		
		xml_set_object($this->xmlParser, $this);
		xml_set_element_handler($this->xmlParser, "startElement", "endElement");
		xml_set_character_data_handler($this->xmlParser, "characterData");
	}   

	/*-----------------------------------------------------------------------+
	|  Public functions. Use to parse feed and get informations.             |   
	+-----------------------------------------------------------------------*/
   
	/**
	* Get all channel elements   
	* 
	* @access   public
	* @return   array   - All chennels as associative array
	*/
	public function getChannels()
	{
		return $this->channels;
	}
   
	/**
	* Get all feed items   
	* 
	* @access   public
	* @return   array   - All feed items as associative array
	*/
	public function getItems()
	{
		return $this->items;
	}

	/**
	* Get total number of feed items
	* 
	* @access   public
	* @return   number  
	*/   
	public function getTotalItems()
	{
		return count($this->items);
	}

	/**
	* Get a feed item by index
	* 
	* @access   public
	* @param    number  index of feed item
	* @return   array   feed item as associative array of it's elements 
	*/   
	public function getItem($index)
	{
		if($index < $this->getTotalItems())
		{
			return $this->items[$index];
		}
		else
		{
			throw new Exception("Item index is learger then total items.");
			return false;
		}        
	}
   
	/**
	* Get a channel element by name
	* 
	* @access   public
	* @param    string  the name of channel tag
	* @return   string
	*/   
	public function getChannel($tagName)
	{ 
		if(array_key_exists(strtoupper($tagName), $this->channels))
		{
			return $this->channels[strtoupper($tagName)];
		}
		else
		{
			throw new Exception("Channel tag $tagName not found.");
			return false;
		}
	}
   
	/**
	* Get the parsed URL
	* 
	* @access   public
	* @return   string
	*/   
	public function getParsedUrl()
	{
		if(empty($this->url))
		{
			throw new Exception("Feed URL is not set yet.");
			return FALSE;
		}
		else
		{
			return $this->url;
		}
		
		
	}

	/**
	* Get the detected Feed version
	* 
	* @access   public
	* @return   string
	*/   
   public function getFeedVersion()
   {
		return $this->version;
   }
   
	/**
	* Parses a feed url
	* 
	* @access   public
	* @param    srting  teh feed url
	* @return   void
	*/   
	public function parse($url)
	{
		$this->url  = $url;
		$URLContent = $this->getUrlContent();
		
		if($URLContent)
		{   
			$segments   = str_split($URLContent, 4096);
			foreach($segments as $index=>$data)
			{
				$lastPiese = ((count($segments)-1) == $index)? true : false;
				$result = xml_parse($this->xmlParser, $data, $lastPiese);
				if (!$result)
				{
				   log_message('error', sprintf("XML error: %s at line %d",  
				   xml_error_string(xml_get_error_code($this->xmlParser)),  
				   xml_get_current_line_number($this->xmlParser)));
				   return false;
				}
			}
			xml_parser_free($this->xmlParser);   
		}
		else
		{
			log_message('error', 'Sorry! cannot load the feed url.');	
			return false;
		}
		
		if(empty($this->version))
		{
			log_message('error', 'Sorry! cannot detect the feed version.');
			return false;
		}
	}   
   
   // End public functions -------------------------------------------------
   
   /*-----------------------------------------------------------------------+
   | Private functions. Be careful to edit them.                            |   
   +-----------------------------------------------------------------------*/

   /**
	* Load the whole contents of a RSS/ATOM page
	* 
	* @access   private
	* @return   string
	*/ 
	private function getUrlContent()
	{
		if(empty($this->url))
		{
			throw new Exception("URL to parse is empty!.");
			return false;
		}
	
		if($content = @file_get_contents($this->url))
		{
			return $content;
		}
		else
		{
			$ch         = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $this->url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$content    = curl_exec($ch);
			$error      = curl_error($ch);
			
			curl_close($ch);
			
			if(empty($error))
			{
				return $content;	
			}
			else
			{
				throw new Exception("Erroe occured while loading url by cURL. <br />\n" . $error) ;
				return false;
			}
		}
	
	}
	
	/**
	* Handle the start event of a tag while parsing
	* 
	* @access   private
	* @param    object  the xmlParser object
	* @param    string  name of currently entering tag
	* @param    array   array of attributes
	* @return   void
	*/ 
	private function startElement($parser, $tagName, $attrs) 
	{
		if(!$this->version)
		{
			$this->findVersion($tagName, $attrs);
		}       
		
		array_push($this->insideItem, $tagName);
		
		$this->currentTag  = $tagName;
		$this->currentAttr = $attrs;
	}   

	/**
	* Handle the end event of a tag while parsing
	* 
	* @access   private
	* @param    object  the xmlParser object
	* @param    string  name of currently ending tag
	* @return   void
	*/    
	private function endElement($parser, $tagName) 
	{   
		if (in_array($tagName, $this->itemTags)) 
		{
		   $this->itemIndex++;
		}
		
		array_pop($this->insideItem);
		$this->currentTag = $this->insideItem[count($this->insideItem)-1];
	}   

	/**
	* Handle character data of a tag while parsing
	* 
	* @access   private
	* @param    object  the xmlParser object
	* @param    string  tag value
	* @return   void
	*/
	private function characterData($parser, $data) 
	{
		//Converting all date formats to timestamp
		if(in_array($this->currentTag, $this->dateTags)) 
		{
			$data = strtotime($data);
		}
				 
	   if($this->inChannel())
	   {
			// If has subtag, make current element an array and assign subtags as it's element
			if(in_array($this->getParentTag(), $this->hasSubTags))  
			{
				if(! is_array($this->channels[$this->getParentTag()]))
				{
					$this->channels[$this->getParentTag()] = array();
				}

				$this->channels[$this->getParentTag()][$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));
				return;
			}
			else
			{
				if(! in_array($this->currentTag, $this->hasSubTags))  
				{
					$this->channels[$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));
				}
			}
					   
			if(!empty($this->currentAttr))
			{
				$this->channels[$this->currentTag . '_ATTRS'] = $this->currentAttr;          
				
				//If the tag has no value
				if(strlen($this->channels[$this->currentTag]) < 2) 
				{
					//If there is only one attribute, assign the attribute value as channel value
					if(count($this->currentAttr) == 1)
					{
						foreach($this->currentAttr as $attrVal)
						{
							$this->channels[$this->currentTag] = $attrVal;
						}
					}
					//If there are multiple attributes, assign the attributs array as channel value
					else
					{
						$this->channels[$this->currentTag] = $this->currentAttr;
					}                        
				}
			}
	   }
	   elseif($this->inItem())
	   {
		   // If has subtag, make current element an array and assign subtags as it's elements
		   if(in_array($this->getParentTag(), $this->hasSubTags))  
			{
				if(! is_array($this->items[$this->itemIndex][$this->getParentTag()]))
				{
					$this->items[$this->itemIndex][$this->getParentTag()] = array();
				}

				$this->items[$this->itemIndex][$this->getParentTag()][$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));
				return;
			}
			else
			{
				if(! in_array($this->currentTag, $this->hasSubTags))  
				{
					$this->items[$this->itemIndex][$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));
				}
			}
			
			 
			if(!empty($this->currentAttr))
			{
				$this->items[$this->itemIndex][$this->currentTag . '_ATTRS'] = $this->currentAttr;          
				
				//If the tag has no value
				
				if(strlen($this->items[$this->itemIndex][$this->currentTag]) < 2)
				{
					//If there is only one attribute, assign the attribute value as feed element's value
					if(count($this->currentAttr) == 1)
					{
						foreach($this->currentAttr as $attrVal)
						{
						   $this->items[$this->itemIndex][$this->currentTag] = $attrVal;
						}
					}
					//If there are multiple attributes, assign the attribute array as feed element's value
					else
					{
					   $this->items[$this->itemIndex][$this->currentTag] = $this->currentAttr;
					}                        
				}
			}
	   }
	}

	/**
	* Find out the feed version
	* 
	* @access   private
	* @param    string  name of current tag
	* @param    array   array of attributes
	* @return   void
	*/   
	private function findVersion($tagName, $attrs)
	{
		// Ambil versi RSS kalau ada
		if ($tagName == 'RSS')
		{
			foreach ($attrs as $attr => $value)
			{
				if ($attr == 'VERSION')
				{
					$this->version = 'RSS '.$value;
					return;
				}
			}
		}

		$namespace = array_values($attrs);
		foreach($this->namespaces as $value =>$version)
		{
			if(in_array($value, $namespace))
			{
				$this->version = $version;
				return;
			}    
		}
	}
	
	private function getParentTag()
	{
		return $this->insideItem[count($this->insideItem) - 2];
	}

	/**
	* Detect if current position is in channel element
	* 
	* @access   private
	* @return   bool
	*/   
	private function inChannel()
	{
		if($this->version == 'RSS 1.0')
		{
			if(in_array('CHANNEL', $this->insideItem) && $this->currentTag != 'CHANNEL')
			return TRUE;
		}
		elseif($this->version == 'RSS 2.0')
		{
			if(in_array('CHANNEL', $this->insideItem) && !in_array('ITEM', $this->insideItem) && $this->currentTag != 'CHANNEL')
			return TRUE;    
		}
		elseif($this->version == 'ATOM 1')
		{
			if(in_array('FEED', $this->insideItem) && !in_array('ENTRY', $this->insideItem) && $this->currentTag != 'FEED')
			return TRUE;    
		}
		
		return FALSE;
	}

	/**
	* Detect if current position is in Item element
	* 
	* @access   private
	* @return   bool
	*/    
	private function inItem()
	{
		if($this->version == 'RSS 1.0' || $this->version == 'RSS 2.0')
		{
			if(in_array('ITEM', $this->insideItem) && $this->currentTag != 'ITEM')
			return TRUE;
		}
		elseif($this->version == 'ATOM 1')
		{
			if(in_array('ENTRY', $this->insideItem) && $this->currentTag != 'ENTRY')
			return TRUE;    
		}
		
		return FALSE;
	}   

	//This function is taken from lastRSS
	/**
	* Replace HTML entities &something; by real characters
	* 
	* 
	* @access   private
	* @author   Vojtech Semecky <webmaster@oslab.net>
	* @link     http://lastrss.oslab.net/
	* @param    string
	* @return   string
	*/   
	private function unhtmlentities($string) 
	{
		// Get HTML entities table
		$trans_tbl = get_html_translation_table (HTML_ENTITIES, ENT_QUOTES);
		// Flip keys<==>values
		$trans_tbl = array_flip ($trans_tbl);
		// Add support for &apos; entity (missing in HTML_ENTITIES)
		$trans_tbl += array('&apos;' => "'");
		// Replace entities by values
		return strtr ($string, $trans_tbl);
	}
} //End class FeedParser
?>
