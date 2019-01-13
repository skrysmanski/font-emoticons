<?php

/**
 * Represents information about a single font emoticon.
 */
class FontEmoticonInfo
{
    const EMOTS_BASE_CLASS_NAME = 'wp-font-emots-';

    /**
     * The regex matching this emoticon.
     * @var string
     */
    private $m_regex;

    /**
     * The regex replacement HTML code of this emoticon.
     * @var string
     */
    private $m_htmlCode;

    /**
     * The name of the smiley.
     * @var string
     */
    private $m_name;

    /**
     * A concatenation of regex-escaped smiley representations.
     * @var string
     */
    private $m_smileyRegex;

    public function __construct($name, $textRepresentations)
    {
        $this->m_name = $name;

        // Ensure that each ASCII representation is quoted separately.
        $this->m_smileyRegex = join( '|', array_map( function( $smiley ) {
            return preg_quote( $smiley, '/' );
        }, $textRepresentations ) );
    }

    public function replaceTextEmots($postText)
    {
        return preg_replace( $this->getRegex(), $this->getHtmlCode(), $postText);
    }

    /**
     * Retrieves the regular expression for replacing ASCII smileys with our font emoticons.
     *
     * @return string
     */
    private function getRegex()
    {
    	if ( empty( $this->m_regex ) )
        {
    		// NOTE: We need to use lookahead and lookbehind here (instead of capturing the leading and
    		//       trailing whitespace) so that multiple consecutive emoticons are detected correctly (see issue #5).
    		// NOTE 2: All HTML tags have been escaped at this point (with "DELIM_CHARS" being the marker).
    		$regex = '/(?<=\s|^|' . FontEmoticonsPlugin::DELIM_CHARS . ')'
    				. '(?:' . $this->m_smileyRegex . ')'
    				. '(?=\s|$|' . FontEmoticonsPlugin::DELIM_CHARS . ')/U';

			/**
			 * Filters the regular expression used to replace smileys with font-based emoticons.
			 *
			 * @param string $regex        The complete regular expression to filter.
			 * @param string $name         The name of the smiley.
			 * @param string $delimiter    The delimiter characters for escaped HTML tags.
			 * @param string $smiley_regex The concatenated smiley regular expressions.
			 */
			$this->m_regex = apply_filters( 'wp_font_emots_regex', $regex, $this->m_name, FontEmoticonsPlugin::DELIM_CHARS, $this->m_smileyRegex );
        }

    	return $this->m_regex;
    }

    /**
     * Retrieves the replacement markup for this emoticon.
     *
     * @return string
     */
    private function getHtmlCode()
    {
        if ( empty( $this->m_htmlCode ) )
        {
        	$html = '<span class="' . self::EMOTS_BASE_CLASS_NAME . $this->m_name . '"></span>';

    		/**
    		 * Filters the HTML markup for the font-based emoticon.
    		 *
    		 * @param string $html         The HTML markup for the emoticon.
    		 * @param string $name         The name of the emoticon.
    		 * @param string $class_prefix The common prefix for the class attribute.
    		 */
    		$this->m_htmlCode = apply_filters( 'wp_font_emots_html', $html, $this->m_name, self::EMOTS_BASE_CLASS_NAME );
    	}

    	return $this->m_htmlCode;
     }
}
