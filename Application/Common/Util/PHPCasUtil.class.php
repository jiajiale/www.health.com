<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/21
 * Time: 17:08
 */

namespace Common\Util;

use phpCAS;

vendor('CAS.CAS');

class PHPCasUtil {

    private $config;
    private $service;

    public function __construct($service = ''){
        $this->config = C('CAS');
        phpCAS::client(CAS_VERSION_2_0,$this->config['HOST'],$this->config['PORT'],'cas');
        phpCAS::setNoCasServerValidation();
        $this->service = $service;
        phpCAS::setDebug();
    }

    public function login(){
        if(!phpCAS::isAuthenticated()){
            phpCAS::setFixedServiceURL($this->service);
        }
        phpCAS::forceAuthentication();
        phpCAS::handleLogoutRequests();
    }

    public function logout(){
        phpCAS::handleLogoutRequests();
        phpCAS::forceAuthentication();
        phpCAS::setServerLogoutURL("http://app.lierdapark.com/ibuildings2/logout");
        phpCAS::logout();
    }

    /**
     * 检查用户是否认证
     */
    public function checkAuthentication(){
        return phpCAS::checkAuthentication();
    }

    /**
     * 检查用户是否认证通过
     */
    public function isAuthenticated(){
        return phpCAS::isAuthenticated();
    }

    /**
     * 获取登录用户信息
     */
    public function getUser(){
       return phpCAS::getUser();
    }

    /**
     * 获取登录用户信息
     * @return array
     */
    public function getAttributes(){
        return phpCAS::getAttributes();
    }

}