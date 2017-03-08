<?php
 
/*
 Controller name: JSON_API_Location_Controller
 Controller description: JSON API Location Controller
 */
 
final class JSON_API_Location_Controller {
 
  public function info() {
    return array(
      'version' => '1.0'
    );
  }
 
  public function menus_for_restaurant() {
    return array(
      'menus' => array( "Sample menu 1", "Sample menu 2", "Sample menu 3" )
    );
  }
}
