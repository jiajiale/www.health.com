<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/16
 * Time: 15:55
 */

namespace Admin\Logic;


class ManagerAccountLogic extends BaseLogic{

    /**
     * @var \Admin\Data\ManagerAccountData
     */
    protected $managerAccountData;

    public function _initialize(){
        $this->managerAccountData = D('ManagerAccount', 'Data');
    }

    /**
     * 获取单条数据
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->managerAccountData->getById($id);
    }

    /**
     * 获取多条数据
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara = null){
        return $this->managerAccountData->getList($conditions,$pagePara);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function saveManagerAccount($data){

        $ManagerAccount = D('ManagerAccount');

        if($ManagerAccount->create($data)){
            return $ManagerAccount->add();
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     * @param $data
     * @return bool
     */
    public function editManagerAccount($data){
        $ManagerAccount = D('ManagerAccount');

        if(isset($data['password']) && !empty($data['password'])){
            $data['password'] = hash_password($data['password'],C('PASSWORD_SALT_KEY'));
        }

        if($ManagerAccount->create($data)){
            return $ManagerAccount->save();
        }else{
            return false;
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function delManagerAccount($id){
        $ManagerAccount = D('ManagerAccount');

        return $ManagerAccount->delete($id);
    }

    /**
     * 管理员登陆
     * @param $username
     * @param $password
     * @return int
     */
    public function login($username, $password){
        $ManagerAccount = D('ManagerAccount');
        $map = array();
        $map['username'] = $username;

        $manager = $ManagerAccount->where($map)->find();
        if(is_array($manager) && $manager['status']){
            if(hash_password($password, C('PASSWORD_SALT_KEY')) === $manager['password']){
                $this->autoLogin($manager);
                return $manager['id']; //登录成功，返回用户ID
            } else {
                return -2; //密码错误
            }
        } else {
            return -1; //用户不存在或被禁用
        }
    }

    /**
     * 登录管理员
     * @param $manager
     */
    protected function autoLogin($manager){
        $ManagerAccount = D('ManagerAccount');

        /* 更新登录信息 */
        $data = array(
            'id'             => $manager['id'],
            'gmt_last_login'  => get_date(),
            'last_ip'         => get_client_ip(),
        );
        $ManagerAccount->save($data);

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'id'              => $manager['id'],
            'username'        => $manager['username'],
            'gmt_last_login'  => $manager['gmt_last_login'],
            'role_id'         => $manager['role_id']
        );

        session('manager_auth', $auth);
        session('manager_auth_sign', data_auth_sign($auth));
    }

    /**
     * 注销当前用户
     */
    public function logout(){
        session('manager_auth', null);
        session('manager_auth_sign', null);
    }
}