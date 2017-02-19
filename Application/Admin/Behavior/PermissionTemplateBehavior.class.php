<?php

namespace Admin\Behavior;
use Think\Behavior;

class PermissionTemplateBehavior extends Behavior {

    public function run(&$content){
        if(is_login()){
            // 匹配data-code单个权限的标签数组
            $permission_arr = $this->matchPermission($content);
            // 匹配data-code-group多个权限的标签
            $permission_group = $this->matchGroupPermission($content);
            // 获取登录用户的权限标签数组
            $role_permissions = $this->getMangerPermission();

            // 模板中的单个标签不在用户的权限标签数组中则删除
            if(count($permission_arr) > 0){
                foreach($permission_arr as $permission){
                    if(!in_array($permission,$role_permissions)){
                        $content = $this->delManagerPermission($content,$permission);
                    }
                }
            }

            // 模板中的标签组都不在用户的标签数组中则删除
            if(!empty($permission_group)){
                $permission_group_arr = explode(',',$permission_group);
                if(count(array_diff($permission_group_arr,$role_permissions)) == count($permission_group_arr)){
                    $content = $this->delManagerGroupPermission($content,$permission_group);
                }
            }
        }
    }

    /**
     * 匹配模板中单个标记权限的地方
     * @param $content
     * @return array
     */
    protected function matchPermission($content){
        $pattern = '/data-code=\"(.*)\"/';
        if(preg_match_all($pattern,$content,$matches)){
            return $matches[1];
        }else{
            return array();
        };
    }

    /**
     * 匹配模板中群组标记权限的地方
     * @param $content
     * @return array
     */
    protected function matchGroupPermission($content){
        $pattern = '/data-code-group=\"(.*)\"/';
        if(preg_match($pattern,$content,$matches)){
            return $matches[1];
        }else{
            return array();
        };
    }

    /**
     * 获取用户的权限代码数组
     * @return array
     */
    protected function getMangerPermission(){
        $manager = session('manager_auth');
        // 获取用户的权限
        $role_permissions = get_array_column(
            D('RolePermissionRelation', 'Logic')->getRolePermissions($manager['role_id']),'code');
        return $role_permissions;
    }

    /**
     * 删掉不在用户权限中的单个操作标签
     * @param $content
     * @param $permission
     * @return mixed
     */
    protected function delManagerPermission($content,$permission){
        $pattern = '/<a.*data-code=\"'. $permission .'\">.*<\/a>/';
        return preg_replace_callback($pattern,function($match){
            return "";
        },$content);
    }

    /**
     * 删掉不在用户权限中的多个操作标签
     * @param $content
     * @param $permission
     * @return mixed
     */
    protected function delManagerGroupPermission($content,$permission){
        $pattern = '/<th.*data-code-group=\"'. $permission .'\">.*<\/th>|
                    <td.*data-code-group=\"'. $permission .'\">.*<\/td>/';
        return preg_replace_callback($pattern,function($match){
            return "";
        },$content);
    }
}