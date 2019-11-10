<?php
namespace app\index\controller;
use app\index\model\Blog;

use app\index\model\Province;
use app\index\model\City;
use app\index\model\Area;

use app\index\model\Collection;
use app\index\model\Fabulous;
use think\facade\Cookie;

class Index extends Base
{
    //获取博客列表页面
    public function index()
    {
        //获取栏目id
        $cid = intval($this->request->param('cid'));
        $blog = new Blog();

        //获取博客列表
        $blogData = $blog->findBlog($cid);

        //获取5篇热点文章
        $hotBlog = $blog->findHotBlog($cid,5);
        $this->assign('blogData',$blogData);
        $this->assign('hotBlog',$hotBlog);
        return $this->fetch();
    }

    //获取博客详情页面
    public function show()
    {
        //获取博客文章id
        $blogId = intval($this->request->param('blog_id'));
        if (!$blogId) {
            $this->error('博文id不能为空');
        }
        //获取博客文章详情
        $blog = new Blog();
        $content = $blog->findOneBlog($blogId);

        //获取5篇热点文章
        $hotBlog = $blog->findHotBlog($content['column_id'],5);

        $collectionFlag = false; //默认未收藏
        $fabulousFlag = false;  //默认未点赞
        if (Cookie::has('index_user_id')){
           //查询用户是否登录，如果已登录查询是否点赞和收藏
            $memberId = Cookie::get('index_user_id');
            $collection = new Collection();
            if (!empty($collection->findOneBlog($blogId,$memberId))) {
                $collectionFlag = true; //查询到用户已收藏
            }

            $fabulous = new Fabulous();
            if (!empty($fabulous->findOneBlog($blogId,$memberId))) {
                $fabulousFlag = true; //查询到用户已点赞
            }
        }

        $this->assign('content',$content);
        $this->assign('hotBlog',$hotBlog);
        $this->assign('collectionFlag',$collectionFlag);
        $this->assign('fabulousFlag',$fabulousFlag);
        return $this->fetch();
    }



    /**
     * 省列表
     * @return string
     */
    public function provinceList(){
        $model = new Province();
        $data = $model->getProvinceList();
        return json_encode($data);
    }

    /**
     * 市列表
     * @return string
     */
    public function cityList(){
        $model = new City();
        $province_code = $_REQUEST['code'] ? $_REQUEST['code'] : 0;
        $data = $model->getCityListByProvinceCode($province_code);
        return json_encode($data);
    }

    /**
     * 区列表
     * @return string
     */
    public function areaList(){
        $model = new Area();
        $city_code = $_REQUEST['code'] ? $_REQUEST['code'] : 0;
        $data = $model->getAreaListByCityCode($city_code);
        return json_encode($data);
    }

    public function addCollection(){
        $model = new Collection();
        $blogId = $this->request->param('blog_id');
        $memberId = $this->request->param('member_id');
        $res = $model->addCollectionBlog($blogId,$memberId);
        if (empty($res)) {
            return json_encode(['status'=>0]);
        }
        return json_encode(['status'=>1]);
    }

    public function cancelCollection(){
        $model = new Collection();
        $blogId = $this->request->param('blog_id');
        $memberId = $this->request->param('member_id');
        $res = $model->cancelCollectionBlog($blogId,$memberId);
        if (empty($res)) {
            return json_encode(['status'=>0]);
        }
        return json_encode(['status'=>1]);
    }


    public function addFabulous(){
        $model = new Fabulous();
        $blogId = $this->request->param('blog_id');
        $memberId = $this->request->param('member_id');
        $res = $model->addFabulousBlog($blogId,$memberId);
        if (empty($res)) {
            return json_encode(['status'=>0]);
        }

        $modelBlog = new Blog();
        $modelBlog->setIncFabulous($blogId);
        return json_encode(['status'=>1]);
    }

    public function cancelFabulous(){
        $model = new Fabulous();
        $blogId = $this->request->param('blog_id');
        $memberId = $this->request->param('member_id');
        $res = $model->cancelFabulousBlog($blogId,$memberId);
        if (empty($res)) {
            return json_encode(['status'=>0]);
        }
        $modelBlog = new Blog();
        $modelBlog->setDecFabulous($blogId);
        return json_encode(['status'=>1]);
    }
}
