<?php

namespace Common\Util\Validator;
  
  class numeric {
    
    public static function test( $input ) {
      return !!preg_match( '{^[0-9]+$}', $input );
    }
    
  }
