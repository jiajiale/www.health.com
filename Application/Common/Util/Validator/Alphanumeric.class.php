<?php
namespace Common\Util\Validator;
  
class alphanumeric {

  public static function test( $input ) {
    return !!preg_match( '{^[a-z0-9]+$}i', $input );
  }

}
