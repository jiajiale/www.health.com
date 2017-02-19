<?php
namespace Common\Util\Validator;
  
class between {

  public static function test( $input, $between ) {

    if( is_numeric($input) ){
      return ( $between[0] <= $input ) &&
      ( $between[1] >= $input );
    }

    return false;
  }

}
