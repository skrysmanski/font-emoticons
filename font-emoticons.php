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
    $code = '<span class="icon-emo-'.$this->name.'"/>';
    return str_replace($this->text_reps, $code, $post_text);
  }
}

class FontEmoticons {
  private $emots;

  private function __construct() {
    # See: http://codex.wordpress.org/Using_Smilies#What_Text_Do_I_Type_to_Make_Smileys.3F
    $this->emots = array(
      new FontEmoticonInfo('happy', array(':)', ':-)', ':c)', ':smile:')),
      new FontEmoticonInfo('unhappy', array(':(', ':-(', ':c(', ':sad:')),
      new FontEmoticonInfo('wink2', array(';)', ';-)', ';c)', ':wink:')),
      new FontEmoticonInfo('tongue', array(':P', ':p', ':-P', ':-p', ':razz:')),
      new FontEmoticonInfo('sleep', array('-.-', '-_-', ':sleep:')),
      new FontEmoticonInfo('thumbsup', array(':thumbs:', ':thumbsup:')),
      new FontEmoticonInfo('devil', array(':devil:', ':twisted')),
      new FontEmoticonInfo('surprised', array(':o', ':-o', ':eek:', '8O', '8o', '8-O', '8-o', ':shock:')),
      new FontEmoticonInfo('coffee', array(':coffee:')),
      new FontEmoticonInfo('sunglasses', array('8)', '8-)', '8c)', 'B)', 'B-)', 'Bc)', ':cool:')),
      new FontEmoticonInfo('displeased', array(':/', ':-/')),
      new FontEmoticonInfo('beer', array(':beer:')),
      new FontEmoticonInfo('grin', array(':D', ':-D', ':cD', ':grin:')),
      # No real icon for "mad" available yet. Use the same as angry.
      new FontEmoticonInfo('angry', array('x(', 'x-(', 'X(', 'X-(', ':angry:', ':x', ':-x', ':mad:')),
      new FontEmoticonInfo('saint', array('O:)', '0:)', 'o:)', 'O:-)', '0:-)', 'o:-)', ':saint:')),
      new FontEmoticonInfo('cry', array(":'(", ":'-(", ':cry:')),
      new FontEmoticonInfo('shoot', array(':shoot:')),
      new FontEmoticonInfo('laugh', array('^^', '^_^', ':lol:'))
    );

    # Disable Wordpress' own smileys
    update_option('use_smilies', 0);

    add_filter('the_content', array($this, 'replace_emots'), 500);
    if (!is_admin()) {
      add_action('wp_print_styles', array($this, 'enqueue_stylesheets_callback'));
    }

  }

  public static function init() {
    static $instance = null;
    if ($instance === null) {
      $instance = new FontEmoticons();
    }
  }

  public function enqueue_stylesheets_callback() {
    wp_register_style('emoticons', WP_PLUGIN_URL.'/font-emoticons/emoticons.css');
    wp_enqueue_style('emoticons');
  }

  public function replace_emots($content) {
    foreach ($this->emots as $emot) {
      $content = $emot->insert_emots($content);
    }

    return $content;
  }
}

FontEmoticons::init();
