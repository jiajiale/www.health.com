<?php

namespace Common\Util\Validator;
  
  class required {
    
    public static function test( $input ) {
      return !empty( $input );
    }
    
  }
