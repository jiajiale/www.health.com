<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/16
 * Time: 15:55
 */

namespace Admin\Logic;


class PermissionLogic extends BaseLogic{

    /**
     * @var \Admin\Data\PermissionData
     */
    protected $permissionData;

    public function _initialize(){
        $this->permissionData = D('Permission', 'Data');
    }

    /**
     * 获取单条数据
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->permissionData->getById($id);
    }

    /**
     * 获取多条数据
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara = null){
        return $this->permissionData->getList($conditions,$pagePara);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function savePermission($data){

        $Permission = D('Permission');

        if($Permission->create($data)){
            return $Permission->add();
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     * @param $data
     * @return bool
     */
    public function editPermission($data){
        $Permission = D('Permission');

        if($Permission->create($data)){
            return $Permission->save();
        }else{
            return false;
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function delPermission($id){
        $Permission = D('Permission');

        return $Permission->delete($id);
    }

    /**
     * 根据模块名获取权限列表
     * @param $module
     * @return mixed
     */
    public function getPermissionsByModule($module){
        return $this->permissionData->getPermissionsByModule($module);
    }


    /**
     * 权限分组
     * @return array
     */
    public function getModules(){
        return array(
            "角色管理"	=> "role",
            "权限管理"	=> "permission",
            "菜单管理"	=> "menu",
            "管理员管理"	=> "manager_account",
            "日志管理"	=> "log",
            "配置管理"	=> "config",

            "分类管理"	=> "category",
            "园区管理"	=> "community",
            "租户管理"	=> "tenant",
            "用户管理"	=> "user",
            "城市管理"	=> "city",
            "推送消息"	=> "push_message",
            "反馈管理"	=> "feedback",
            "帮助管理"	=> "help",

            "车辆管理"	=> "car_info",
            "车主管理"	=> "car_user",
            "乘客管理"	=> "passenger",

            "行程管理"	=> "route",
            "拼车管理"	=> "carpool",
            "预约管理"	=> "reserve",
            "拼车评价管理"	=> "comment",
            "意见管理"	=> "reason",

            "团购管理"	=> "goods",
            "添加团购"	=> "goods_add",
            "优惠管理"	=> "coupon",
            "商家管理"	=> "shop",
            "标签管理"	=> "tag",
            "焦点图管理"	=> "focus",
            "广告管理"	=> "advertisement",

            "订单管理"	=> "order",
            "配送管理"	=> "delivery",
            "团购评价管理"	=> "review",
            "地址管理"	=> "address",

            "添加活动"	=> "activity_add",
            "活动管理"   => "activity",
            "组织者管理" => "organizer",
            "活动标签管理" => "label",
            "活动参加管理" => "apply",
            "活动评价管理" => "record",
            "分享评价管理" => "remark",
            "活动分享管理" => "share",
        );
    }

}