<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\Column;
use app\index\model\Blog;

use app\index\model\Province;
use app\index\model\City;
use app\index\model\Area;

class Index extends Controller
{
    //获取博客列表页面
    public function index(Request $request)
    {
//        $data = $this->provinceList();
//        print_r($data);
        //获取栏目id
        $cid = intval($request->param('cid'));
        $blog = new Blog();

        //获取博客列表
        $blogData = $blog->findBlog($cid);

        //获取5篇热点文章
        $hotBlog = $blog->findHotBlog($cid,5);

        //获取博客栏目分类
        $cate = $this->findCategory();
        $this->assign('cate',$cate);
        $this->assign('blogData',$blogData);
        $this->assign('hotBlog',$hotBlog);
        return $this->fetch();
    }

    //获取博客详情页面
    public function show(Request $request)
    {
        //获取博客文章id
        $blogId = intval($request->param('blog_id'));

        //获取博客文章详情
        $blog = new Blog();
        $content = $blog->findOneBlog($blogId);

        //获取5篇热点文章
        $hotBlog = $blog->findHotBlog($content['column_id'],5);

        //获取博客栏目分类
        $cate = $this->findCategory();
        $this->assign('cate',$cate);
        $this->assign('content',$content);
        $this->assign('hotBlog',$hotBlog);
        return $this->fetch();
    }

    //登录
    public function login(){
        //获取博客栏目分类
        $cate = $this->findCategory();
        $this->assign('cate',$cate);
        return $this->fetch();
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

}
