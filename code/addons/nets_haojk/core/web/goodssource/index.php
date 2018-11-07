<?php
if (!(defined('IN_IA')))
{
    exit('Access Denied');
}
class Index_NetsHaojkPage extends WebPage
{
    public function main()
    {
        global $_GPC;
        global $_W;

        if ($_W['action']=='goodssource.index'||$_W['action']=='goodssource')
            $this->index();
        elseif ($_W['action']=='goodssource.goodssource_add')
            $this->goodssource_add();
        elseif ($_W['action']=='goodssource.goodssource_addpost')
            $this->goodssource_addpost();
        elseif ($_W['action']=='goodssource.goodssource_delete')
            $this->goodssource_delete();
        elseif ($_W['action']=='goodssource.sourcegoods')
            $this->sourcegoods();
        elseif ($_W['action']=='goodssource.sourcegoods_add')
            $this->sourcegoods_add();
        elseif ($_W['action']=='goodssource.sourcegoods_save')
            $this->sourcegoods_save();
        elseif ($_W['action']=='goodssource.sourcegoods_delete')
            $this->sourcegoods_delete();
        elseif ($_W['action']=='goodssource.sourcegoods_addpost')
            $this->sourcegoods_addpost();
    }

    //商品源列表
    public function index()
    {
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];

        $where = " 1=1 and uniacid=".$_W['uniacid']." ";

        if (!empty($_GPC['name'])) {
            $where.= " and name like '%".$_GPC['name']."%'";
        }
        if (!empty($_GPC['type'])) {
            $where.= " and type = ".$_GPC['type'];
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $list = pdo_fetchall("SELECT * FROM ".tablename('nets_hjk_menu')." where ".$where. " ORDER BY id DESC limit ". (($pindex - 1) * $psize) . ',' . $psize);
        $total = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_menu")." where".$where);
        $pager = pagination($total, $pindex, $psize);//var_dump($pager);die;
        $count = 0;
        include $this->template('haojingke/goodssource/index');
    }
    //商品源-添加
    public function goodssource_add()
    {
        global $_W;
        global $_GPC;
        if(!empty($_GPC['id'])){
            $edit_data = pdo_fetch("SELECT * FROM ".tablename('nets_hjk_menu')." WHERE id =".$_GPC['id']);
            //var_dump($edit_data);
            $res =json_decode($edit_data['query']);
            //var_dump($res);
            $all_name =  object_array($res);
            $price_name = $all_name['0'];
            $commission_name = $all_name['1'];
            $commissionpirce_name = $all_name['2'];
            $goodstype_name = $all_name['3'];
            $goodslx_name = $all_name['4'];
            $sort_name = $all_name['5'];
            $sortby_name=$all_name['6'];
            $keyword=$all_name['4'];
            //var_dump($keyword);
        }
        include $this->template('haojingke/goodssource/goodssource_add');
    }
    //商品源-添加提交
    public function goodssource_addpost()
    {
        global $_W;
        global $_GPC;
        $save_query = array();
        $res['name'] = $_GPC['name'];
        $res['type'] = 1;

        $res['created_at'] = time();
        $res['updated_at'] = time();
        $res['url'] = '../choiceness/index?name='.$_GPC['name'];
        //价格
        $price['name']='券后价';
        $price['filed'] = 'price';
        $value['minprice'] =$_GPC['minprice'];
        $value['maxprice'] =$_GPC['maxprice'];
        $price['value']=$value;
        $price['type'] = 'between';
        //佣金比例
        $commission['name']='佣金比例';
        $commission['filed']='commission';
        $commission_value['mincommission']=$_GPC['mincommission'];
        $commission_value['maxcommission']=$_GPC['maxcommission'];
        $commission['value'] = $commission_value;
        $commission['type'] = 'between';

        $commissionpirce['name']='佣金金额';
        $commissionpirce['filed']='commissionpirce';
        $commissionpirce_value['mincommissionpirce']=$_GPC['mincommissionpirce'];
        $commissionpirce_value['maxcommissionpirce']=$_GPC['maxcommissionpirce'];
        $commissionpirce['value'] = $commissionpirce_value;
        $commissionpirce['type'] = 'between';


        //排序方式
        $sort['name'] = '排序方式';
        $sort['filed'] = 'sort';
        $sort['value'] = $_GPC['sort'];
        $sort['type'] = '=';


        //关键词
        $keywords['name'] = '关键词';
        $keywords['filed'] = 'keyword';
        $keywords['value'] = $_GPC['keyword'];
        $keywords['type'] = '=';

        //合并
        $query = array($price,$commission,$commissionpirce,$sort,$keywords);
        $res['query'] = json_encode($query);
        $res['uniacid']=$_W['uniacid'];
        if(!empty($_GPC['type']) && $_GPC['type']==3){
            $res['type']=$_GPC['type'];
            $res['query']='';
        }
        $res['icon'] = tomedia($_GPC['icon']);
        if (!empty($_GPC['id'])) {
            $res['updated_at'] = time();
            $save_menu = pdo_update('nets_hjk_menu',$res,array('id'=>$_GPC['id']));
        }else
            $save_menu = pdo_insert('nets_hjk_menu',$res);
        if($save_menu)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
    //商品源-删除
    public function goodssource_delete()
    {
        global $_W;
        global $_GPC;
        $res = pdo_fetch("SELECT * FROM ".tablename('nets_hjk_menu')." WHERE id = ".$_GPC['id']);
        if (!empty($res)) {
            $j = pdo_delete('nets_hjk_menu',array('id'=>$_GPC['id']));
            $r = pdo_delete('nets_hjk_usegoods',array('menuid'=>$_GPC['id']));
            if($j)
                show_json(1,"操作成功");
            else
                show_json(1,"操作失败");
        }else
            show_json(1,"此商品源不存在");
    }
    //商品源-商品列表
    public function sourcegoods()
    {
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $page=empty($_GPC["page"])?1:$_GPC['page'];
        $usecate=pdo_fetchall("select * from ".tablename('nets_hjk_menu')." where uniacid=:uniacid and type=3",array(":uniacid"=>$_W['uniacid']));
        $cateid=$_GPC['cname'];
        if(empty($cateid)){
            $cateid=0;
        }
        $list=getSourceGoods($page,$cateid);

        $total=$list["total"];
        $list=$list["list"];
        $pager = pagination($total, $page, 20);

        include $this->template('haojingke/goodssource/sourcegoods');
    }
    //商品源-商品添加
    public function sourcegoods_add()
    {
        global $_W;
        global $_GPC;
        $usecate=pdo_fetchall("select * from ".tablename('nets_hjk_menu')." where uniacid=:uniacid and type=3",array(":uniacid"=>$_W['uniacid']));

        include $this->template('haojingke/goodssource/sourcegoods_add');
    }
    //商品源-商品更新排序
    public function sourcegoods_save()
    {
        global $_W;
        global $_GPC;
        $id=$_GPC['id'];
        $r=pdo_update('nets_hjk_usegoods',array("sort"=>$_GPC['sort']),array('id'=>$id));
        if($r)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
    //商品源-商品删除
    public function sourcegoods_delete()
    {
        global $_W;
        global $_GPC;
        $id=$_GPC['id'];
        $r=pdo_delete('nets_hjk_usegoods',array('id'=>$id,'uniacid'=>$_W['uniacid']));
        if($r)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
    //商品源-商品添加提交
    public function sourcegoods_addpost()
    {
        global $_W;
        global $_GPC;
        $total = 0;
        $skuId = $_GPC['skuId'];
        $usecnameid = $_GPC['cname'];
        if(empty($usecnameid))
            show_json(1,"请选择要加入的商品源！");
        $sort = $_GPC['sort'];
        //$skuId不存在，则为我发放的商品添加提交
        if(!empty($skuId)){
            $data["skuId"]=$skuId;
            $data["uniacid"]=$_W['uniacid'];
            $data["menuid"]=$usecnameid;
            $data["sort"]=$sort;
            $data["createtime"]=time();
            $goods=pdo_fetch("select * from ".tablename("nets_hjk_usegoods")." where uniacid=:uniacid and  menuid=:menuid and  skuId=:skuId"
                ,array(":uniacid"=>$_W['uniacid'],":skuId"=>$_W['skuId'],":menuid"=>$_W['menuid']));
            if($goods){
                $i=pdo_update("nets_hjk_usegoods",$data);
            }
            else{
                $i=pdo_insert("nets_hjk_usegoods",$data);
            }
        }
        else{
            $global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
            $data = array(
                'unionId' => $global['jduniacid']
            );
            $url=HAOJK_HOST."index/goodsskuidslist";
            $temp_url="index/goodsskuidslist".$_W['uniacid'];
            $filename=getfilename($temp_url);
            load()->func('communication');
            //var_dump($data);
            $res=ihttp_post($url,$data);
            $res=$res["content"];
            $list=json_decode($res);
//            $total=$list->total;
            $list=$list->data;

            $json_string = json_encode($list);

            file_put_contents($filename, $json_string);
            $json=file_get_contents($filename);
            $list=json_decode($json, true);
            $i=0;
            foreach($list AS $l){
                $data1['uniacid']=$_W['uniacid'];
                $data1['menuid']=$usecnameid;
                $data1['sort']=$sort;
                $data1['skuId']=$l['skuId'];
                $data1['createtime']=time();
                $d=pdo_fetch("select * from ".tablename("nets_hjk_usegoods")." where menuid=:menuid and skuId=:skuId",array(":menuid"=>$usecnameid,":skuId"=>$l['skuId']));
                if(empty($d)){
                    $i=pdo_insert("nets_hjk_usegoods",$data1);
                    if($i)
                        $total = $total+1;
                }
            }
        }
        $msg = "操作成功";
        if($total>0)
            $msg = "操作成功,本次添加【".$total."】";
        if($i)
            show_json(1,$msg);
        else
            show_json(1,"操作失败");
    }

}
?>