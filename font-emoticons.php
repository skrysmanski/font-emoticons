<?php
/*
Plugin Name: Font Emoticons
Plugin URI:
Description: Replace the standard WP Smileys with font icons.
Version: 1.0.0
Author: Sebastian Krysmanski
Author URI:
*/

class FontEmoticonInfo {
  private $name;
  private $text_reps;

  public function __construct($name, $text_reps) {
    $this->name = $name;
    $this->text_reps = $text_reps;
  }

  public function insert_emots($post_text) {
    $code = '<span class="emoticon-'.$this->name.'"/>';
    return str_replace($this->text_reps, $code, $post_text);
  }
}

class FontEmoticons {
  # See: http://codex.wordpress.org/Using_Smilies#What_Text_Do_I_Type_to_Make_Smileys.3F
  private $emots;

  private function __construct() {
    $this->emots = array(
      new FontEmoticonInfo('happy', ':)', ':-)', ':c)', ':smile:'),
      new FontEmoticonInfo('unhappy', ':(', ':-(', ':c(', ':sad:'),
      new FontEmoticonInfo('wink2', ';)', ';-)', ';c)', ':wink:'),
      new FontEmoticonInfo('tongue', ':P', ':p', ':-P', ':-p', ':razz:'),
      
    );
  }
}