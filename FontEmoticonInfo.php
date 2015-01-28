<?php

class FontEmoticonInfo
{
    const EMOTS_BASE_CLASS_NAME = 'wp-font-emots-';

    private $name;
    private $text_reps;
    private $regex;

    public function __construct($name, $text_reps)
    {
        $this->name      = $name;
        $this->text_reps = $text_reps;

        $this->regex = '';
        $is_first    = true;
        foreach ($text_reps as $smiley)
        {
            if ($is_first)
            {
                $is_first = false;
            }
            else
            {
                $this->regex .= '|';
            }
            $this->regex .= preg_quote($smiley, '/');
        }

        $this->regex = '/(\s+)(?:' . $this->regex . ')(\s+)/U';
    }

    public function insert_emots($post_text)
    {
        $code = '\\1<span class="' . self::EMOTS_BASE_CLASS_NAME . $this->name . '"/>\\2';

        return preg_replace($this->regex, $code, $post_text);
    }
}
