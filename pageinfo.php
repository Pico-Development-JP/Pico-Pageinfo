<?php
/**
 * Pico PageInfo Plugin
 *
 * @author TakamiChie
 * @link http://onpu-tamago.net/
 * @license http://opensource.org/licenses/MIT
 * @version 1.1
 */
class PageInfo extends AbstractPicoPlugin {

  protected $enabled = false;

  public function onSinglePageLoaded(array &$pageData)
  {
    $url = substr($pageData['url'], strlen($this->getConfig("base_url")));
    $pageData['p_path'] = $url;
    $p = explode("/", $url);
    $pageData['p_name'] = array_pop($p); // 最後の項目はファイル名のため
    $pageData['p_pathes'] = $p;
    $pageData['p_depth'] = substr_count($url, "/") + 1;
  }
}

?>