<?php

/**
 * RSS for PHP - small and easy-to-use library for consuming an RSS Feed
 *
 * @copyright  Copyright (c) 2008 David Grudl
 * @license    New BSD License
 * @version    1.2
 */

class Feed
{
  
	/** @var int */
	public static $cacheExpire = '1 day';

	/** @var string */
	public static $cacheDir;

	/** @var SimpleXMLElement */
	protected $xml;


	/**
	 * Loads RSS or Atom feed.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return Feed
	 * @throws FeedException
	 */
	public static function load($url, $user = NULL, $pass = NULL)
	{
		$xml = self::loadXml($url, $user, $pass);
		if ($xml->channel) {
			return self::fromRss($xml);
		} else {
			return self::fromAtom($xml);
		}
	}


	/**
	 * Loads RSS feed.
	 * @param  string  RSS feed URL
	 * @param  string  optional user name
	 * @param  string  optional password
	 * @return Feed
	 * @throws FeedException
	 */
	public static function loadRss($url, $user = NULL, $pass = NULL)
	{
	    ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0) Gecko/20100101 Firefox/9.0');
		return self::fromRss(self::loadXml($url, $user, $pass));
	}


	/**
	 * Loads Atom feed.
	 * @param  string  Atom feed URL
	 * @param  string  optional user name
	 * @param  string  optional password
	 * @return Feed
	 * @throws FeedException
	 */
	public static function loadAtom($url, $user = NULL, $pass = NULL)
	{
	  ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0) Gecko/20100101 Firefox/9.0');
		return self::fromAtom(self::loadXml($url, $user, $pass));
	}


	public static function fromRss(SimpleXMLElement $xml)
	{
		if (!$xml->channel) {
			throw new FeedException('Invalid feed.');
		}

		self::adjustNamespaces($xml);
		
		//foreach ($xml->channel as $channel) {
		//if ($xml->channel) {
		    $channel = $xml->channel;
		
			self::adjustNamespaces($channel);
			
			if (isset($channel->{'now:title'})) {
				$channel->nowTitle = (string)$channel->{'now:title'};
			}
			
			if (isset($channel->{'now:link'})) {
				$channel->nowLink = (string)$channel->{'now:link'};
			}
			
			if (isset($channel->{'now:content'})) {
				$channel->nowContent = (string)$channel->{'now:content'};
			}
			
			if (isset($channel->{'now:markdown'})) {
				$channel->nowMarkdown = (string)$channel->{'now:markdown'};
			}
			
			if (isset($channel->{'now:timestamp'})) {
				$channel->nowTimestamp = (string)$channel->{'now:timestamp'};
			}
		//}

		foreach ($xml->channel->item as $item) {
			// converts namespaces to dotted tags
			self::adjustNamespaces($item);
			
			// generate 'timestamp' tag
			if (isset($item->{'dc:date'})) {
				$item->timestamp = strtotime($item->{'dc:date'});
			} elseif (isset($item->pubDate)) {
				$item->timestamp = strtotime($item->pubDate);
			}
			
			// check for markdown
			if (isset($item->{'source:markdown'})) {
				$item->markdown = $item->{'source:markdown'};
			}
		}
		$feed = new self;
		$feed->xml = $xml->channel;
		return $feed;
	}


	public static function fromAtom(SimpleXMLElement $xml)
	{
		if (!in_array('http://www.w3.org/2005/Atom', $xml->getDocNamespaces(), TRUE)
			&& !in_array('http://purl.org/atom/ns#', $xml->getDocNamespaces(), TRUE)
		) {
			throw new FeedException('Invalid feed.');
		}

		// generate 'timestamp' tag
		foreach ($xml->entry as $entry) {
			$entry->timestamp = strtotime($entry->updated);
		}
		
		$feed = new self;
		$feed->xml = $xml;
		return $feed;
	}


	/**
	 * Returns property value. Do not call directly.
	 * @param  string  tag name
	 * @return SimpleXMLElement
	 */
	public function __get($name)
	{
		return $this->xml->{$name};
	}


	/**
	 * Sets value of a property. Do not call directly.
	 * @param  string  property name
	 * @param  mixed   property value
	 * @return void
	 */
	public function __set($name, $value)
	{
		throw new Exception("Cannot assign to a read-only property '$name'.");
	}


	/**
	 * Converts a SimpleXMLElement into an array.
	 * @param  SimpleXMLElement
	 * @return array
	 */
	public function toArray(SimpleXMLElement $xml = NULL)
	{
		if ($xml === NULL) {
			$xml = $this->xml;
		}

		if (!$xml->children()) {
			return (string) $xml;
		}

		$arr = array();
		foreach ($xml->children() as $tag => $child) {
			if (count($xml->$tag) === 1) {
				$arr[$tag] = $this->toArray($child);
			} else {
				$arr[$tag][] = $this->toArray($child);
			}
		}

		return $arr;
	}


	/**
	 * Load XML from cache or HTTP.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return SimpleXMLElement
	 * @throws FeedException
	 */
	private static function loadXml($url, $user, $pass)
	{
		//ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0) Gecko/20100101 Firefox/9.0');
		$e = self::$cacheExpire;
		$cacheFile = self::$cacheDir . '/feed.' . md5(serialize(func_get_args())) . '.xml';

		if (self::$cacheDir
			&& (time() - @filemtime($cacheFile) <= (is_string($e) ? strtotime($e) - time() : $e))
			&& $data = @file_get_contents($cacheFile)
		) {
			// ok
		} elseif ($data = trim(self::httpRequest($url, $user, $pass))) {
			if (self::$cacheDir) {
				file_put_contents($cacheFile, $data);
			}
		} elseif (self::$cacheDir && $data = @file_get_contents($cacheFile)) {
			// ok
		} else {
			throw new FeedException('Cannot load feed.');
		}

		return new SimpleXMLElement($data, LIBXML_NOWARNING | LIBXML_NOERROR);
	}


	/**
	 * Process HTTP request.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return string|FALSE
	 * @throws FeedException
	 */
	private static function httpRequest($url, $user, $pass)
	{
		if (extension_loaded('curl')) {
			
			$options = array(
				CURLOPT_TCP_FASTOPEN => TRUE,
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0) Gecko/20100101 Firefox/9.0',
				CURLOPT_ENCODING => '',
				CURLOPT_HEADER => FALSE,
				CURLOPT_CONNECTTIMEOUT => 10,
				CURLOPT_TIMEOUT => 20,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_FOLLOWLOCATION => TRUE, 
			);
			
			$port = parse_url($url, PHP_URL_PORT);
			if (!empty($port)) {
				$scheme = parse_url($url, PHP_URL_SCHEME);
				$host = parse_url($url, PHP_URL_HOST);
				$path = parse_url($url, PHP_URL_PATH);
				$url = $scheme.'://'.$host.$path;
				array_push($options,'CURLOPT_PORT => ' .$port);
			}
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt_array($curl, $options);
			
			
			/*
			curl_setopt($curl, CURLOPT_TCP_FASTOPEN, TRUE);
			curl_setopt($curl, CURLOPT_URL, $url);
			if (!empty($port)) {
				curl_setopt($curl, CURLOPT_PORT, $port);
			}
			curl_setopt($curl,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0) Gecko/20100101 Firefox/9.0");
			curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
			curl_setopt($curl, CURLOPT_ENCODING,  '');
			if ($user !== NULL || $pass !== NULL) {
				curl_setopt($curl, CURLOPT_USERPWD, "$user:$pass");
			}
			curl_setopt($curl, CURLOPT_HEADER, FALSE);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($curl, CURLOPT_TIMEOUT, 20);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // no echo, just return result
			curl_setopt($curl, CURLOPT_FILETIME, true);
			if (!ini_get('open_basedir')) {
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); // sometime is useful :)
			}
			*/
			
			$result = curl_exec($curl);
			return curl_errno($curl) === 0 && curl_getinfo($curl, CURLINFO_HTTP_CODE) === 200
				? $result
				: FALSE;

		} elseif ($user === NULL && $pass === NULL) {
			return file_get_contents($url);

		} else {
			throw new FeedException('PHP extension CURL is not loaded.');
		}
	}


	/**
	 * Generates better accessible namespaced tags.
	 * @param  SimpleXMLElement
	 * @return void
	 */
	private static function adjustNamespaces($el)
	{
		foreach ($el->getNamespaces(TRUE) as $prefix => $ns) {
			$children = $el->children($ns);
			foreach ($children as $tag => $content) {
				$el->{$prefix . ':' . $tag} = $content;
			}
		}
	}

}



/**
 * An exception generated by Feed.
 */
class FeedException extends Exception
{
}
