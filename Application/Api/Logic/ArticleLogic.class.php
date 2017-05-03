<?php

namespace Api\Logic;


class ArticleLogic extends BaseLogic{

    /**
     * @var \Api\Data\ArticleData
     */
    protected $articleData;

    public function _initialize(){
        $this->articleData = D('Article', 'Data');
    }

    /**
     * 获取空间动态
     * @param $conditions
     * @return mixed
     */
    public function getSayInfo($conditions){
        return $this->articleData->getSayInfo($conditions);
    }

    /**
     * 获取用户动态
     * @param $conditions
     * @return mixed
     */
    public function getUserSayInfo($conditions){
        return $this->articleData->getUserSayInfo($conditions);
    }

    /**
     * 获取动态用户信息
     * @param $conditions
     * @return mixed
     */
    public function getSayUser($conditions){
        return $this->articleData->getSayUser($conditions);
    }

    /**
     * 获取用户动态信息
     * @param $conditions
     * @return mixed
     */
    public function getUserSay($conditions){
        return $this->articleData->getUserSay($conditions);
    }

    /**
     * 获取动态用户头像
     * @param $conditions
     * @return mixed
     */
    public function getSayUserImage($conditions){
        return $this->articleData->getSayUserImage($conditions);
    }

    /**
     * @param $conditions
     * @return mixed
     */
    public function getSayOk($conditions){
        return $this->articleData->getSayOk($conditions);
    }

    /**
     * 发表评论
     * @param $data
     * @return array|bool
     */
    public function commentArticles($data){
        $Saytexttable = D('Saytexttable');
        $Daytask = D('Daytask');
        $Achiveprogress = D('Achiveprogress');

        $insertId = $Saytexttable->add(array(
            "sayID" => $data['syaID'],
            "userID" => $data['userID'],
            "sayMessage" => $data['sayMessage'],
            "textTime" => date('Y-m-d H:i:s',time()),
            "friendID" => $data['friendID'],
            "sayKind" => $data['sayKind']
        ));

        if($insertId){
            $backback = array();

            if($data["sendPut"] == 1){
                $message = "qw";
                if($data['sayKind'] == 1) {
                    $message = $data['userName'].'回复了你的说说';
                }else{
                    $message = $data['userName'].'评论了你的说说';

                    $Daytask->where('userID = %d',$data['userID'])->setInc('daycomment');
                    $Achiveprogress->where('userID = %d',$data['userID'])->setInc('comment');
                }
            }

            if($data['userID'] != $data['friendID']){
                // todo 推送消息
            }

            return array('time'=>date('Y-m-d H:i:s',time()),'ret' => $backback);
        }else{
            return false;
        }
    }
}