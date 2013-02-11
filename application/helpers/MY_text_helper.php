<?php
/**
 * Extended Text Helper
 *
 * @package		Extended Text Helper
 * @subpackage	Helper
 * @author		Brennan Novak
 * @link		http://social-igniter.com
 *
 */

if (!function_exists('real_character_limiter'))
{
	function real_character_limiter($str, $n = 500, $end_char = '&#8230;')
	{
		if (strlen($str) < $n)
		{
			return $str;
		}

		$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

		if (strlen($str) <= $n)
		{
			return $str;
		}

		$out = substr($str, 0, $n);
		return $out.$end_char;
	}
}


/**
 *	~ THE TRUNCENATOR ~ by Barnaby Walters ~ https://github.com/indieweb/php-truncenator/
 *	Takes a string (tweet-like note) and some config params, produces a truncated version to spec
 *	
 *	@param string	$string		The string to be truncated
 *	@param int		$length		The maximum length of the output
 *	@param string	$ellipsis	The string to append in the case of truncation
 *	@param string	$uri		The canonical URI of the post, to be added to the end
 *	@param int		$urilen		Treat any URLs as if they were this length
 *	@param bool		$parens		If trucation is not required, surround the canon. link with parens (())
 *	@param int		$hashtags	The number of hashtags present in the text to preserve if trucation occurs
 */
function truncator($string, $length=140, $uri=null, $urilen=null, $parens=true, $ellipsis='...', $hastags=1)
{
	mb_internal_encoding('UTF-8');

	// Figure out total append length if truncation occurs
	$append = $ellipsis;
	if (!empty($uri)) $append .= ' ' . $uri;

	// if $urilen is set, create array of URIs within the text and replace them with dummy text @ $urilen chars
	if (is_int($urilen))
	{
		$uris = array();
		foreach (truncator_find_urls($string, $tidy=false) as $key => $url)
		{
			$dummy = 'URL' . $key;
			$dummy .= str_repeat('X', $urilen - mb_strlen($dummy));
			$uris[$dummy] = $url;
			$string = str_replace($url, $dummy, $string);
		}
	}

	// Truncate string to nearest WB below that length
	$matches = array();
	$words = array();
	preg_match_all('/\b\w+\b/', $string, $matches, PREG_OFFSET_CAPTURE);
	foreach ($matches[0] as $match)
	{
		// For each match
		$words[] = array($match[1], $match[0]);
	}
	// $words = {[offset, 'string'], [offset, 'string'] •••}

	$maxplainlen = $length - truncator_uri_mb_strlen($append, $urilen);

	// See if truncation will happen
	if (truncator_uri_mb_strlen($string, $urilen) > $maxplainlen)
	{
		foreach ($words as $key => $word)
		{
			// Is the current word the first to cross $maxplainlen?
			if ($word[0] > $maxplainlen or $word[0] + mb_strlen($word[1]) > $maxplainlen)
			{
				// Yes. The current word and all words after it must be removed
				$plaintargetlen = $words[$key-1][0] + mb_strlen($words[$key-1][1]);
				break;
			}
		}

		if (!isset($plaintargetlen)) $plaintargetlen = $maxplainlen;

		// Truncate string
		$truncatedplain = mb_substr($string, 0, $plaintargetlen);

		// Add the append
		$trunc = $truncatedplain . $append;
	}
	else
	{
		// If no trucation required, just append the URL
		// TODO: if adding the space and brackets will push over the edge, remove enough words to compensate
		// TODO: write edge-case test to cover that scenario
		$trunc = $string . ' (' . $uri . ')';
	}

	// if $urilen set, expand dummies into full URIs
	if (is_int($urilen))
	{
		foreach ($uris as $dummy => $uri)
		{
			$trunc = str_replace($dummy, $uri, $trunc);
		}
	}

	return $trunc;
}

/**
 *	Given a string potentially with <img> elements in, this function will return the same 
 *	string but with any <img> replaced with the contents of it’s @href
 */
function truncator_expand_img($str)
{
	// find all img elements, replace them with the value of their @href
	return preg_replace('/<img .*src\=\"(\S*)\"+ .* ?\/?>/i', '$1', $str);
}

/**
 *	Given a string, returns the length that string would be if all URIs within were $urilen chars long
 */
function truncator_uri_mb_strlen($string, $urilen)
{
	// Find all urls
	$urls = truncator_find_urls($string, $tidy=false);

	// Replace them with $urllen chars
	if (is_int($urilen))
	{
		foreach ($urls as $url)
		{
			$string = str_replace($url, str_repeat('X', $urilen), $string);
		}
	}

	// Return strlen
	return mb_strlen($string, 'UTF-8');
}

/**
 *	Returns an array of any URLs in the given text
 */
function truncator_find_urls($text, $tidy=true)
{
	// Pattern is from 1 cassis.js (thanks Tantek!), slightly modified to not look for twitter names
	// E.G. beforehand it would return @tantek for @tantek.com. This function is just interested in addresses, not twitter names
	$pattern = '/(?:(?:(?:(?:http|https|irc)?:\\/\\/(?:(?:[!$&-.0-9;=?A-Z_a-z]|(?:\\%[a-fA-F0-9]{2}))+(?:\\:(?:[!$&-.0-9;=?A-Z_a-z]|(?:\\%[a-fA-F0-9]{2}))+)?\\@)?)?(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*\\.)+(?:(?:aero|arpa|asia|a[cdefgilmnoqrstuwxz])|(?:biz|b[abdefghijmnorstvwyz])|(?:cat|com|coop|c[acdfghiklmnoruvxyz])|d[ejkmoz]|(?:edu|e[cegrstu])|f[ijkmor]|(?:gov|g[abdefghilmnpqrstuwy])|h[kmnrtu]|(?:info|int|i[delmnoqrst])|j[emop]|k[eghimnrwyz]|l[abcikrstuvy]|(?:mil|museum|m[acdeghklmnopqrstuvwxyz])|(?:name|net|n[acefgilopruz])|(?:org|om)|(?:pro|p[aefghklmnrstwy])|qa|r[eouw]|s[abcdeghijklmnortuvyz]|(?:tel|travel|t[cdfghjklmnoprtvwz])|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw]))|(?:(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9])\\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[0-9])\\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[0-9])\\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[0-9])))(?:\\:\\d{1,5})?)(?:\\/(?:(?:[!#&-;=?-Z_a-z~])|(?:\\%[a-fA-F0-9]{2}))*)?)(?=\\b|\\s|$)/';

	$c = preg_match_all($pattern, $text, $m);

	if($c)
	{
		// Normalise
		$links = array_values($m[0]);

		ob_start();
		$links = array_map(function($value) use ($tidy) {
			return $tidy ? web_address_to_uri($value, true) : $value;
		}, $links);
		ob_end_clean();

		// $links = ['http://someurl.tld', •••]

		return $links;
	}

	return array();
}
