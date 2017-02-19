<?php

namespace Common\Util;

/**
 * Validate
 *
 * @copyright 2012 Jonas Hermsmeier
 * @author Jonas Hermsmeier <http://jhermsmeier.de>
 * 注：在原验证器上做过更改
 */
class Validate {

  /**
   * Runs a bunch of tests against given $data
   * with set $rules.
   *
   * Returns TRUE if the tests pass or an associative array of
   * occured errors and (if set) their messages otherwise.
   *
   * @param array $data
   * @param array $rules
   * @return mixed
   */
  public static function test($data,$rules) {
    $passed = true;
    $errors = array();

    foreach($rules as $field => $rule){
        $ruleset = static::parse($rule);

        // 包含必填规则则每一条都验证，不包含则填写了就验证
        if(!in_array('required',array_keys($ruleset['methods']))){
            if(!empty($data[$field])){
                foreach( $ruleset['methods'] as $method => &$params ) {
                    if( !static::$method($data[$field], $params ) ) {
                        $passed = false;

                        if(isset($ruleset['messages'][$method])){
                            $errors[$field] = $ruleset['messages'][$method];
                        }else{
                            $errors[$field] = null;
                        }

                        break;
                    }
                }
            }
        }else{
            foreach( $ruleset['methods'] as $method => &$params ) {
                if( !static::$method($data[$field], $params ) ) {
                    $passed = false;

                    if(isset($ruleset['messages'][$method])){
                        $errors[$field] = $ruleset['messages'][$method];
                    }else{
                        $errors[$field] = null;
                    }

                    break;
                }
            }
        }

        if(!$passed) break;
    }

    return $passed ?: $errors;
  }

  /**
   * Parses rule definitions.
   *
   * @param string $rule
   * @return array
   */
  protected static function parse( $rule ) {

    $tests = array();

    $pattern = '{^([a-z]+)(?:(?:[(](.*?)[)]) | (?:[:]\s+(.*?)))?$}xi';

    foreach($rule as $key => $val) {

        if(is_numeric($key)){
            $key = $val;
            $val = null;
        }

        if(preg_match( $pattern, $key, $m)){

          if(isset($m[2]) && !empty($m[2])) {
              $tests['methods'][$m[1]] = explode(',', $m[2] );
          }else{
              $tests['methods'][$m[1]] = null;
          }

          $tests['messages'][$m[1]] = $val;
        }
    }
    return $tests;
  }

  /**
   * Used to call test methods. When using a
   * namespace based class autoloader this has the effect
   * that only needed test methods will be loaded.
   *
   * @param string $method
   * @param array $parameters
   * @return bool
   */
  public static function __callStatic( $method, $parameters ) {
      $class = __NAMESPACE__."\\Validator\\".ucfirst($method);
      return call_user_func_array(
        array($class, 'test' ),
        $parameters
      );
  }

}
  
