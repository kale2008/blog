<?php
namespace app\index\controller;
use app\index\model\Blog;
use app\index\model\Collection;
use think\facade\Cookie;
use app\index\model\Column;
use app\index\model\Member;
use app\index\model\Province;
use app\index\model\City;
use app\index\model\Area;

class User extends Base
{
    private $error;

    //登录
    public function login(){
        //获取博客栏目分类
        $cate = $this->findCategory();
        $this->assign('cate',$cate);
        return $this->fetch();
    }

    //登录处理
    public function doLogin(){
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        if (!$username){
            $this->error('您的用户名不能为空');
        }
        if (!$password) {
            $this->error('您的密码不能为空');
        }
        //密码采用md5加密
        $password = md5($password);
        $model = new Member();
        $user = $model->findUserInfo($username,$password);
        if (empty($user)) {
            $this->error('你输入的用户名或者密码有误，请重新输入');
        }
        //设置Cookie 有效期为 1天
        $time = 86400;
        Cookie::set('index_user',$username,$time);
        Cookie::set('index_user_id',$user['member_id'],$time);
        Cookie::set('index_avatar',$user['member_avatar'],$time);
        $this->success('登录成功','/');
    }

    /**
     * 个人信息展示
     * @return mixed
     */
    public function show(){
        $username = Cookie::get('index_user');
        $userObj = new Member();
        $userInfo = $userObj->getUserByName($username);
        $provinceModel = new Province();
        $provinceData = $provinceModel->provinceAll();
        $cityModel = new city();
        $cityData = $cityModel->cityAll();
        $areaModel = new Area();
        $areaData = $areaModel->AreaAll();
        $userInfo['province_name'] = $provinceData[$userInfo['member_province']]['name'];
        $userInfo['city_name'] = $cityData[$userInfo['member_city']]['name'];
        $userInfo['county_name'] = $areaData[$userInfo['member_county']]['name'];
        if( !$userInfo ){
            $this->success('用户不存在','/');
        }
        return view('/user/info', ['userInfo'=>$userInfo]);
    }

    /**
     * 个人信息修改
     * @return string
     */
    public function infoUp(){
        $username = Cookie::get('index_user');
        $userObj = new Member();
        $userInfo = $userObj->getUserByName($username);
        if( !$userInfo ){
            $this->success('用户不存在','/');
        }

        // 提交操作， 修改用户信息和头像
        if( $this->request->isPost() ){
            $params = $this->request->param();
            $data = [
                'member_province'    => $params['province'],
                'member_city'        => $params['city'],
                'member_county'      => $params['county'],
                'member_address'     => $params['address']
            ];
            if( !$this->_checkUserUpdateData($params)){
                $this->error($this->error); // error page show
            }
            $pic = $userInfo['member_avatar'];
            $pic = explode('/', $pic);
            $len = count($pic);
            if(!empty($_FILES['avatar']['name']) && $_FILES['avatar']['name'] != $pic[$len-1]) {
                if (!($url = $this->_uploadAvatar($_FILES['avatar']))) {
                    $this->error($this->error); // error page show
                }
                $data['member_avatar'] = $url;
            }
            $userObj = new Member();
            $res = $userObj->updateMemberById($data, $userInfo['member_id']);
            if($res){
                $this->redirect('/index.php/index/user/show');
            }else{
                $this->error('修改个人信息失败'); // error page show
            }
        }else{
            $provinceModel = new Province();
            $provinceData = $provinceModel->getProvinceList();
            $cityModel = new City();
            $cityData = $cityModel->getCityListByProvinceCode($userInfo['member_province']);
            $areaModel = new Area();
            $areaData = $areaModel->getAreaListByCityCode($userInfo['member_city']);
            return view('/user/update', [
                'userInfo' => $userInfo,
                'provinceData' => $provinceData,
                'cityData' => $cityData,
                'areaData' => $areaData,
            ]);
        }
    }

    //注册
    public function reg(){
        //获取博客栏目分类
        $cate = $this->findCategory();
        $this->assign('cate',$cate);
        return $this->fetch();
    }

    //获取博客栏目分类，默认取6个分类
    public function findCategory(){
        $col = new Column();
        return $col->findCategory(6);

    }

    /**
     * 提交注册
     * @return string|void
     */
    public function regSave(){
        $params = $_REQUEST;
        if($_FILES['avatar']) $params['avatar'] = $_FILES['avatar'];
        if( !$this->_checkRegData($params)){
            $this->success($this->error); // error page show
        };
        if( !($url = $this->_uploadAvatar($_FILES['avatar'])) ){
            $this->success($this->error); // error page show
        }
        $params['avatar'] = $url;
        $data = [
            'member_name'        => $params['username'],
            'member_password'    => md5($params['password']),
            'member_province'    => $params['province'],
            'member_city'        => $params['city'],
            'member_county'      => $params['county'],
            'member_address'     => $params['address'],
            'member_avatar'      => $params['avatar'],
            'member_lastlogin'   => date('Y-m-d H:i:s'),
        ];
        $userObj = new Member();
        $res = $userObj->createMember($data);
        if($res){
            return $this->redirect('/');
        }else{
            $this->success('注册失败，请重新注册'); // error page show
        }
    }

    /**
     * 校验注册数据
     * @param $data
     * @return bool
     */
    private function _checkRegData($data){
        $usernameTitle = '用户名';
        $passwordTitle = '密码';
        $repasswordTitle = '重复密码';
        $provinceTitle = '省';
        $cityTitle = '市';
        $countyTitle = '区';
        $addressTitle = '具体地址';
        $avatarTitle = '上传头像';
        $emptyTitle = '不能为空';
        $regex = '/[0-9a-zA-Z_-]+/';

        // 校验用户名
        if( empty($data['username']) ){
            $this->error = $usernameTitle.$emptyTitle;
            return false;
        }
        if( !preg_match($regex, $data['username'])){
            $this->error = $usernameTitle.'必须是字母数字下划线横杠';
            return false;
        }
        // 校验用户名唯一
        $userObj = new Member();
        if( $userObj->getUserByName($data['username']) ){
            $this->error = $usernameTitle.'已经存在';
            return false;
        }

        // 校验密码
        if( empty($data['password']) ){
            $this->error = $passwordTitle.$emptyTitle;
            return false;
        }
        if( !preg_match($regex, $data['password'])){
            $this->error = $passwordTitle.'必须是字母数字下划线横杠';
            return false;
        }

        // 校验重复密码
        if( empty($data['repassword']) ){
            $this->error = $repasswordTitle.$emptyTitle;
            return false;
        }
        if( $data['repassword'] != $data['password']){
            $this->error = $repasswordTitle.'和'.$passwordTitle.'必须相同';
            return false;
        }

        // 校验省/市/县
        if( empty($data['province']) ){
            $this->error = $provinceTitle.$emptyTitle;
            return false;
        }
        if( empty($data['city']) ){
            $this->error = $cityTitle.$emptyTitle;
            return false;
        }
        if( empty($data['county']) ){
            $this->error = $countyTitle.$emptyTitle;
            return false;
        }

        // 校验具体地址
        if( empty($data['address']) ){
            $this->error = $addressTitle.$emptyTitle;
            return false;
        }
        // 校验上传头像
        if( empty($data['avatar']) ){
            $this->error = $avatarTitle.$emptyTitle;
            return false;
        }
        return true;
    }

    //退出登录
    public function logout()
    {
        Cookie::delete('index_user');
        Cookie::delete('index_avatar');
        Cookie::delete('index_user_id');
        $this->success('退出成功','/');
    }

    public function collection()
    {
        if (!Cookie::has('index_user_id')) {
            $this->error('您未登录');
        } else {
            $memberId = Cookie::get('index_user_id');
            $collectionModel = new Collection();
            $blogData = $collectionModel->findAllBlog($memberId);
            //获取5篇热点文章
            $blog = new Blog();
            $hotBlog = $blog->findHotBlog(0, 5);
            $this->assign('blogData', $blogData);
            $this->assign('hotBlog', $hotBlog);
        }
        return $this->fetch();
    }
    /**
     * 上传头像文件
     * @param $file
     * @return bool
     */
    private function _uploadAvatar( $file ){
        $res = false;
        if($file['error'] != 0){
            $this->error = '上传失败请重试';
            return $res;
        }
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $arr = explode(".", $file["name"]);
        $len = count($arr);
        $extension = $arr[$len-1];
        if (!((($file["type"] == "image/gif")
                || ($file["type"] == "image/jpeg")
                || ($file["type"] == "image/jpg")
                || ($file["type"] == "image/pjpeg")
                || ($file["type"] == "image/x-png")
                || ($file["type"] == "image/png"))
            && in_array($extension, $allowedExts))){
            $this->error = '文件扩展名仅支持gif，jpg，png，jpeg';
            return $res;
        }
        if( ($file['size']>1048576) ){ //php.ini 默认支持最大文件大小为2M
            $this->error = '文件大小最大上传2M';
        }
        $dir = '/static/index/image/user_avatar/';
        $file_path = $_SERVER['DOCUMENT_ROOT'].$dir;
        if( !file_exists($file_path) ){
            mkdir($file_path, '755');
        }
        if(file_exists($file_path.$file['name'])){
            $this->error = '您上传的文件已存在';
            return $res;
        }
        $newFile = $file_path.$file["name"];
        if( !move_uploaded_file($file["tmp_name"], $newFile) ){
            $this->error = '上传失败请重试';
            return $res;
        }
        $res = $dir.$file["name"];
        return $res;
    }

    /**
     * 校验注册数据
     * @param $data
     * @return bool
     */
    private function _checkUserUpdateData($data){
        $provinceTitle = '省';
        $cityTitle = '市';
        $countyTitle = '区';
        $addressTitle = '具体地址';
        $emptyTitle = '不能为空';
        //$regex = '/[0-9a-zA-Z_-]+/';

        // 校验省/市/县
        if( empty($data['province']) ){
            $this->error = $provinceTitle.$emptyTitle;
            return false;
        }
        if( empty($data['city']) ){
            $this->error = $cityTitle.$emptyTitle;
            return false;
        }
        if( empty($data['county']) ){
            $this->error = $countyTitle.$emptyTitle;
            return false;
        }

        // 校验具体地址
        if( empty($data['address']) ){
            $this->error = $addressTitle.$emptyTitle;
            return false;
        }
        return true;
    }
}
