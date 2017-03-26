<?php

namespace Common\Util\Validator;
  
class alpha {

  public static function test( $input ) {
    return !!preg_match( '{^[a-z]+$}i', $input );
  }

}

