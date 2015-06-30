<?php
/**
 * Pico PageInfo Plugin
 *
 * @author TakamiChie
 * @link http://onpu-tamago.net/
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class PageInfo{

  private $content_dir;
  
  // copied from pico source, $headers as array gives ability to add additional metadata, e.g. header image
  private function read_file_meta($file_url, $content) {
    $headers['p_path'] = $file_url;
    $p = explode("/", $file_url);
    array_shift($p); // 一つ目の要素は必ずコンテンツフォルダのため
    $headers['p_name'] = array_pop($p); // 最後の項目はファイル名のため
    $headers['p_pathes'] = $p;
    $headers['p_depth'] = substr_count($file_url, "/");

		return $headers;
  }
  
  public function config_loaded(&$settings) {
    $this->base_url = $settings['base_url'];
    $this->content_dir = ROOT_DIR . $settings["content_dir"];
  }

  public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page) {

    $ifmerge = function (&$page, &$looking_page, &$file_meta) {
      if($page['url'] == $looking_page['url']){
        $page = array_merge($page, $file_meta);
      }
    };
    
    $new_pages = array();
    foreach ($pages as $page) {
      $file_url = substr($page["url"], strlen($this->base_url));
      if($file_url[strlen($file_url) - 1] == "/") $file_url .= 'index';
      $file_name = $this->content_dir . $file_url . ".md";
      
      // get metadata from page
      if (file_exists($file_name)) {
        $file_content = file_get_contents($file_name);
        $file_meta = $this->read_file_meta($file_url, $file_content);
        $page = array_merge($page, $file_meta);
        array_push($new_pages, $page);
        $ifmerge($current_page, $page, $file_meta);
        $ifmerge($next_page, $page, $file_meta);
        $ifmerge($prev_page, $page, $file_meta);
      }
    }
    $pages = $new_pages;
  }
}

?>