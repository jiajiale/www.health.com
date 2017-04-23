<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/6
 * Time: 21:13
 */
namespace Admin\Controller;

use Think\Controller;

class PublicController extends Controller{

    /**
     * @var \Admin\Logic\ManagerAccountLogic
     */
    protected $managerAccountLogic;

    public function _initialize(){
        $this->managerAccountLogic = D('ManagerAccount','Logic');
    }

    /**
     * 登录页面
     */
    function index(){
        $this->display("login");
    }

    /**
     * 管理员登录
     * @param null $username
     * @param null $password
     * @param null $verify
     */
    public function login($username = null, $password = null, $verify = null){
        if(IS_POST){
            //用户登录返回用户登录id或错误号
            $muid = $this->managerAccountLogic->login($username,$password);

            if($muid > 0){
                $this->success('登录成功！', U('Index/index'));
            }else{
                switch($muid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }
        } else {
            if(is_login()){
                $this->redirect('Index/index');
            }else{
                $this->display();
            }
            $this->redirect('Index/index');
        }
    }

    /**
     * 管理员注销登录
     */
    public function logout(){
        if(is_login()){
            $this->managerAccountLogic->logout();
            session('[destroy]');
            $this->success('退出成功！', U('index'));
        } else {
            $this->redirect('index');
        }
    }
}