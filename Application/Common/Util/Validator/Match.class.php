<?php

namespace Common\Util\Validator;
  
class match {

  public static $matcher = array(
      "mobile" => "^1[34578]{1}\\d{9}$",
      "date" => "^(\\d{4})-(\\d{2})-(\\d{2})( (\\d{2}):(\\d{2}):(\\d{2}))?$",
      'ID' => "^(\\d{6})(18|19|20)?(\\d{2})([01]\\d)([0123]\\d)(\\d{3})(\\d|X)?",
      'post' => "[1-9]\\d{5}(?!d)"
  );

  public static function test( $input , $patternName) {
    $patterns = self::$matcher;
    $pattern = $patterns[$patternName[0]];
    return !!preg_match( "/$pattern/" , $input );
  }

}

