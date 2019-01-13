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
     * FontEmoticonInfo constructor.
     *
     * @param string    $name
     * @param string[]  $textRepresentations text representations of this smiley/emoticon
     */
    public function __construct($name, $textRepresentations)
    {
        $joinedRegex = self::escapeAndJoinTextRepresentations($textRepresentations);

        // NOTE: We need to use lookahead and lookbehind here (instead of capturing the leading and
        //   trailing whitespace) so that multiple consecutive emoticons are detected correctly (see issue #5).
        // NOTE 2: All HTML tags have been escaped at this point (with "DELIM_CHARS" being the marker).
        $this->m_regex = '/(?<=\s|^|' . FontEmoticonsPlugin::DELIM_CHARS . ')'
                       . '(?:' . $joinedRegex . ')'
                       . '(?=\s|$|' . FontEmoticonsPlugin::DELIM_CHARS . ')/U';

        $this->m_htmlCode = '<span class="' . self::EMOTS_BASE_CLASS_NAME . $name . '"></span>';
    }

    /**
     * Escapes each text representation with "preg_quote()" and then joins all
     * of them together with "|" - resulting in a valid regex.
     *
     * @param string[] $textRepresentations text representations of this smiley/emoticon
     *
     * @return string the resulting regex
     */
    private static function escapeAndJoinTextRepresentations($textRepresentations)
    {
        $regex = '';
        $is_first = true;

        foreach ($textRepresentations as $smiley)
        {
            if ($is_first)
            {
                $is_first = false;
            }
            else
            {
                $regex .= '|';
            }

            $regex .= preg_quote($smiley, '/');
        }

        return $regex;
    }

    /**
     * Replaces textual emoticons with their font emoticon equivalents.
     *
     * @param string $postText  the post's text
     *
     * @return string  the changed post text
     */
    public function replaceTextEmots($postText)
    {
        return preg_replace($this->m_regex, $this->m_htmlCode, $postText);
    }
}
