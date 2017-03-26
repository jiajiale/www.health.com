<?php

namespace Common\Util\Validator;
  
class ISBN {

  public static function test( $input ) {
    // strip formatting
    $ISBN = preg_replace( '{[^0-9X]}', '', $input );
    // get length
    $length = strlen( $ISBN );
    // get the checksum
    $checksum = ( $ISBN[( $length - 1 )] === 'X' ) ? 10 : intval( $ISBN[( $length - 1 )] );
    // ISBN-10 or ISBN-13?
    if( $length === 13 ) {
      $sum = NULL;
      for( $i = 1; $i < 13; $i++ ) {
        $d = intval( $ISBN[( $i - 1 )] );
        $f = ( $i % 2 ) ? 1 : 3;
        $sum += $d * $f;
      }
      $sum = 10 - $sum % 10;
      return $sum === $checksum;
    }
    else if( $length === 10 ) {
      $sum = NULL;
      for( $i = 1; $i < 10; $i++ )
        $sum += $i * intval( $ISBN[( $i - 1 )] );
      $sum = $sum % 11;
      return $sum === $checksum;
    }
    return FALSE;
  }

}

