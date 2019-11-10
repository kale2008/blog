<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Blog as Blg;
use app\admin\model\Column as Col;

class Blog extends Controller
{
	public function index() {
		$blg = new Blg();
		$totalCount = $blg->noDeleteCount();
		$pageClass = new \Page($totalCount);
		$limit = $pageClass->limit();
		$info = $blg->showBlog($limit);
		$pageData = $pageClass->allPage();

		$col = new Col();
		$allcolumn = $col->allcolumn();
		$this->assign('allColumn', $allcolumn);
		$this->assign('info', $info);
        $this->assign('pageData', $pageData);
		return $this->fetch('blog');
	}

	public function ajaxPage(Request $request)
	{
		$blg = new Blg();
		$col = new Col();
        if ($request->has('column_id') && $request->has('blog_title')) {
            $column_id = $request->param('column_id');
            $blog_title = $request->param('blog_title');
            $totalCount = $blg->searchCount($column_id, $blog_title);
            //dump($totalCount);
            $pageClass = new \Page($totalCount);
            $limit = $pageClass->limit();
            $info = $blg->searchBlog($limit, $column_id, $blog_title);
            //dump($info);
        } else {
            $totalCount = $blg->noDeleteCount();
            $pageClass = new \Page($totalCount);
            $limit = $pageClass->limit();
            $info = $blg->showBlog($limit);
        }
        
        $pageData = $pageClass->allPage();
        $data = ['info' => $info, 'pageData' => $pageData];
        return json($data);
	}
	public function ajaxStatus(Request $request)
    {
        $id = $request->param('id');
        $blg = new Blg();
        $res = $blg->updateStatus($id);
        return json(['num' => $res]);
    }

    public function ajaxDelete(Request $request)
    {
    	$id = $request->param('id');
    	$arr_id = explode(',', $id);
        $blg = new blg();
        $res = $blg->deleteBlog($arr_id);
        return json(['num' => $res]);
    }
	public function add()
	{
		$col = new Col();
		$allColumn = $col->publishColumn();
		$this->assign('allColumn', $allColumn);
		return $this->fetch('addBlog');
	}

	public function doadd(Request $request)
	{
		$title = $request->param('title');
		$content = $request->param('editorValue');
		$column_id = $request->param('column');
		$status = $request->param('status');
		$data = [
			'column_id' 	=>	$column_id,
			'blog_title'	=>	$title,
			'blog_content'	=>	$content,
			'blog_status'	=>	$status
		];
		$blg = new blg();
		$res = $blg->addBlog($data);
		if ($res) {
			$this->redirect('admin/blog/index');
		} else {
			return $this->error('发布失败，请重试！', 'admin/blog/index');
		}
	}
}