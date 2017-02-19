<?php
namespace Common\Util\Validator;
  
class regex {

  public static function test( $input, $pattern ) {
    return !!preg_match($pattern[0], $input );
  }

}
