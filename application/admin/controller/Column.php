<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Column as Col;

class Column extends Controller
{   
    public function index()
    {   
        $col = new Col();
        $totalCount = $col->totalCount();
        $pageClass = new \Page($totalCount);
        $limit = $pageClass->limit();
        $info = $col->showColumn($limit);
        $pageData = $pageClass->allPage();
        $this->assign('info', $info);
        $this->assign('pageData', $pageData);
        return $this->fetch('column');    
    }

    public function ajaxPage(Request $request)
    {   
        $col = new Col();
        if ($request->has('column_name') && $request->has('column_status')) {
            $column_name = $request->param('column_name');
            $column_status = $request->param('column_status');
            $totalCount = $col->searchCount($column_name, $column_status);
            $pageClass = new \Page($totalCount);
            $limit = $pageClass->limit();
            $info = $col->searchColumn($limit, $column_name, $column_status);
        } else {
            $totalCount = $col->totalCount();
            $pageClass = new \Page($totalCount);
            $limit = $pageClass->limit();
            $info = $col->showColumn($limit);
        }
        $pageData = $pageClass->allPage();
        //dump($data);
        $data = ['info' => $info, 'pageData' => $pageData];
        return json($data);
    }
    public function ajaxStatus(Request $request)
    {
        $id = $request->param('id');
        $col = new Col();
        $res = $col->updateStatus($id);
        return json(['num' => $res]);
    }
    public function ajaxUpdate(Request $request)
    {
        $column_id = $request->param('column_id');
        $column_name = $request->param('column_name');
        $col = new Col();
        $res = $col->updateColumn($column_id, $column_name);
        return json(['num' => $res]);
    }

    public function ajaxDelete(Request $request)
    {
        $id = $request->param('id');
        $col = new Col();
        $res = $col->deleteColumn($id);
        return json(['num' => $res]);
    }

    public function add()
    {
        return $this->fetch('addColumn');
    }

    public function doadd(Request $request)
    {
        $column_name = $request->param('title');
        $column_stauts = $request->param('status');
        $data = [
            'column_name'   =>  $column_name,
            'column_status' =>  $column_status
        ];
        $column = new Col();
        $info = $column->findColumn($data);
        if ($info != null) {
            return json(['num'  => 0, 'msg'  => '此栏目名称已经存在，请重新输入！']);
        }
        $res = $column->addColumn($column_name, $column_stauts);

        if ($res) {
            return json(['num'  => 1, 'msg'  => '栏目添加成功']);
        } else {
            return json(['num'  => 0, 'msg'  => '栏目添加失败，请重试！']);
        }
        
    }

}
