<?php
namespace Common\Util\Validator;
  
class length {

  public static function test( $input, $length ) {
    $strlen = mb_strlen(php_charset($input), 'utf-8');

    return ( $length[0] <= $strlen ) && ( $length[1] >= $strlen );
  }

}
