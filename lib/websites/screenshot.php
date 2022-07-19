<?php

use Screen\Capture;

class Screenshot {

  public function getScreenshot($url){

    // $screenCapture = new Capture($url);
    // $screenCapture->setUrl($url);
    // $screenCapture->setWidth(1920);
    // $screenCapture->setHeight(1080);
    // $screenCapture->setClipWidth(1920);
    // $screenCapture->setClipHeight(1080);

    $website = str_replace('https://', '', $url);
    $website = str_replace('.', '_', $website);

    return PANEL_URL . '/assets/img/screenshots/' . $website . '.jpg';

  }

}

?>
