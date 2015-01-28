<?php

/**
 * Represents a single font emoticon (together with all of its text representations).
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

    public function __construct($name, $textRepresentations)
    {
        $this->m_regex = '';
        $is_first    = true;
        foreach ($textRepresentations as $smiley)
        {
            if ($is_first)
            {
                $is_first = false;
            }
            else
            {
                $this->m_regex .= '|';
            }
            $this->m_regex .= preg_quote($smiley, '/');
        }

        $this->m_regex = '/(\s+)(?:' . $this->m_regex . ')(\s+)/U';

        $this->m_htmlCode = '\\1<span class="' . self::EMOTS_BASE_CLASS_NAME . $name . '"/>\\2';
    }

    public function replaceTextEmots($postText)
    {
        return preg_replace($this->m_regex, $this->m_htmlCode, $postText);
    }
}
