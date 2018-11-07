<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<link href="./resource/css/bootstrap.min.css?v=20180110" rel="stylesheet">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">
    <div class="layui-row">
        <form action="" method="get"  enctype="multipart/form-data" class="layui-form layui-form-pane layui-col-md12 ">
            <input type="hidden" name="m" value="nets_haojk">
            <input type="hidden" name="do" value="web">
            <input type="hidden" name="r" value="sale.freegoods">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="c" value="site">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">关键字</label>
                    <div class="layui-input-inline">
                        <input type="text" value="<?php  echo $_GPC['keyword'];?>"  name="keyword"  placeholder="商品名称、商品ID" autocomplete="off" class="layui-input">
                    </div>
                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                </div>
        </div>
        </form>
    </div>

    <table class="layui-table">
        <thead>
        <tr>
            <th>图片</th>
            <th class="w300">商品名称</th>
            <th>优惠券</th>
            <th>价格</th>
            <th >券后价</th>
            <th >佣金比例</th>
            <th >佣金约</th>
            <th >砍价人数</th>
            <th >排序</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $r) { ?>
        <tr>
            <td >
                <div class="goodjson" style="display:none;"><?php  echo json_encode($r);?></div>
                <img src="<?php  echo $r['picUrl'];?>" width="48" height="48" />
            </td>
            <td>
                <?php  echo $r['skuId'];?><br>
                <?php  echo $r['skuName'];?>
            </td>
            <td>
                ￥<?php  echo $r['discount'];?>
            </td>
            <td>
                ￥<?php  echo $r['wlPrice'];?>
            </td>
            <td>
                ￥<?php  echo $r['wlPrice_after'];?>
            </td>
            <td>
                <?php  echo $r['wlCommissionShare'];?>%
            </td>
            <td>
                ￥<?php  echo number_format($r['wlCommissionShare']/100*$r['wlPrice_after'],2); ?>
            </td>
            <td>

                <input type="text"  onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" value="<?php  echo $r['cuttingnum'];?>"  class="layui-input" style="width:50px;"/>

            </td>
            <td>
                <input type="text"  onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" value="<?php  echo $r['sort'];?>"  class="layui-input" style="width:50px;"/>
            </td>
            <td class="td-manage">
                <a class="layui-btn   layui-btn-mini  layui-btn-normal" href="javascript:void(0)" onclick="savesort(this,<?php  echo $r['id'];?>)"><i class="iconfont">&#xe747;</i> 保存</a>
                <a class="layui-btn   layui-btn-mini  layui-btn-danger" href="javascript:void(0)" onclick="freegoods_delete(this,<?php  echo $r['id'];?>)"><i class="iconfont">&#xe69d;</i> 删除</a>
            </td>
        </tr>
        <?php  } } ?>
        </tbody>
    </table>

    <?php  echo $pager;?>

</div>
</body>
</html>

<script>
    /*保存 排序*/
    function savesort(obj,id){
        var loadii = layer.load()
        var sort=$(obj).parent().parent().find("input").eq(1).val();
        var cuttingnum=$(obj).parent().parent().find("input").eq(0).val();
        var ajaxurl="<?php  echo webUrl('sale/freegoods_update')?>";
        var data= { "id": id, "sort": sort,"cuttingnum":cuttingnum };
        $.post(ajaxurl,data,function(res){
            var res=JSON.parse(res);
            layer.close(loadii);
            if(res.status==1){
                layer.alert(res.result.message, {icon: 6,closeBtn: 0});
            }else{
                layer.msg("操作失败");
            }
        });
    }
    /*删除*/
    function freegoods_delete(obj,id){
        var loadii = layer.load();
        var ajaxurl="<?php  echo webUrl('sale/freegoods_delete')?>";
        var data= { "id": id};
        $.post(ajaxurl,data,function(res){
            var res=JSON.parse(res);
            layer.close(loadii);
            if(res.status==1){
                layer.alert(res.result.message, {icon: 6,closeBtn: 0},function () {
                    location.href=location.href;
                });
            }else{
                layer.msg("操作失败");
            }
        });
    }

</script>