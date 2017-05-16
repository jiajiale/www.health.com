<?php
namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller {

    /**
     * @var \Admin\Logic\MenuLogic
     */
    protected $menuLogic;
    /**
     * @var \Admin\Logic\ManagerAccountLogic
     */
    protected $managerAccountLogic;

    /**
     * @var \Admin\Logic\RolePermissionRelationLogic
     */
    protected $rolePermissionRelationLogic;

    protected $managerData;

    public function __construct(){
        parent::__construct();

        $this->menuLogic = D('Menu','Logic');
        $this->managerAccountLogic = D('ManagerAccount','Logic');
        $this->rolePermissionRelationLogic = D('RolePermissionRelation', 'Logic');

        $this->check_login();
        $this->check_priv();
    }

    /**
     * 获取有效数据
     */
    public function getAvailableData()
    {
        $data = array();

        $request = array_merge(I('get.'),I('post.'));
        unset($request['page_index']);
        unset($request['page_size']);

        foreach ($request as $key => $value) {
//            if ($value != '' || $value === 0) {
//                $data[$key] = $value;
//            }

            $data[$key] = $value;
        }
        return $data;
    }


    /**
     * 成功返回
     * @param null $data
     * @param string $msg
     * @param string $referer
     * @param int $code
     */
    public function ajaxSuccess($data = null, $msg = '',$referer = '', $code = 200 )
    {
        $ajaxData = array();
        if (!$msg) $msg = "ok";

        $ajaxData['state'] = 'success';
        $ajaxData['message'] = $msg;
        $ajaxData['data'] = $data;
        $ajaxData['code'] = $code;
        $ajaxData['href'] = $data;
        $ajaxData['referer'] = $referer;

        $this->ajaxReturn($ajaxData);
    }

    /**
     * 失败返回
     * @param string $msg
     * @param string $referer
     * @param int $code
     */
    public function ajaxError($msg = '', $referer = '', $code = 300)
    {
        $ajaxData = array();
        if (!$msg) $msg = "fail";

        $ajaxData['state'] = 'fail';
        $ajaxData['message'] = $msg;
        $ajaxData['code'] = $code;
        $ajaxData['referer'] = $referer;

        $this->ajaxReturn($ajaxData);
    }

    /**
     * 自动定向成功失败
     * @param $flag
     * @param string $msg
     * @param string $referer
     */
    public function ajaxAuto($flag,$msg = '操作',$referer = ''){
        if($flag !== false){
            $this->ajaxSuccess(null,$msg.'成功',$referer);
        }else{
            $this->ajaxError($msg.'失败',$referer);
        }
    }

    /**
     * 检查管理员是否登录
     */
    protected function check_login(){
        if($mid = is_login()){
            $this->managerData = $this->managerAccountLogic->getById($mid);
            $menuTree = $this->menuLogic->getMenuTreeByRoleId($this->managerData['role_id']);

            foreach($menuTree as $k=>$v){
                foreach($v['childrens'] as $key=>$val){
                    foreach($val['childrens'] as $keyItem=>$valItem){
                        $menuTree[$k]['childrens'][$key]['childrens'][$keyItem]['url'] = U($valItem['url']);
                    }
                }
            }

            $this->assign('menuTree',json_encode($menuTree));
            $this->assign('managerData',$this->managerData);
        }else {
            $error = '请先登录！';
            $this->error($error,U('public/index'));
        }
    }

    /**
     * 检查用户的权限
     */
    protected function check_priv(){
        $manager = session('manager_auth');
        $permission = strtolower(CONTROLLER_NAME.'_'.str_replace('do_','',ACTION_NAME));

        // 获取用户的权限
        $role_permissions = get_array_column(
            $this->rolePermissionRelationLogic->getRolePermissions($manager['role_id']),'code');

        // 以下控制器和方法不检查
        $uncheckArr = array(
            'index_index',
            'image_uploadpicture',
            'image_uploadfile',
            'index_main',
            'city_get_city_list',
            'config_pin',
            'config_tuan',
            'category_get_category_list',
            'tag_get_tag_list',
            'coupon_graphic',
            'coupon_poster',
            'shop_get_query_shop_list',
            'goods_get_query_goods_list',
            'activity_get_query_activity_list',
            'organizer_company',
            'organizer_non_personal',
            'organizer_personal',
            'organizer_edit_non_personal',
            'organizer_edit_personal',
            'organizer_get_query_organizer_list',
            'label_get_label_list',
            'config_huo',
            'goods_shelve',
            'order_get_query_goods_attr',
        );

        if(!in_array($permission,array_merge($uncheckArr,$role_permissions))){
            $error = '没有权限';
            $this->error($error,U('public/index'));
        }

    }

}