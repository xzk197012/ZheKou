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
            <input type="hidden" name="r" value="sale.barrage">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="c" value="site">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">关键字</label>
                    <div class="layui-input-inline">
                        <input type="text" value="<?php  echo $_GPC['keyword'];?>"  name="keyword"  placeholder="昵称、提示语" autocomplete="off" class="layui-input">
                    </div>
                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                </div>
        </div>
        </form>
    </div>
    <xblock>
        <input type="text" name="tip"  id="tip"  placeholder=" 初始化提示语,例:领取了100元" autocomplete="off" class=" layui-input-w300">
        <button class="layui-btn layui-btn-normal"  onclick="updateall()"> 批量修改提示语</button>
        <button class="layui-btn" onclick="tipsinit()"> 初始化</button>
        <button class="layui-btn" onclick="hjk_dialog_show('添加',' <?php  echo webUrl('sale/barrage_add');?>')"><i class="layui-icon"></i>添加</button>
        <span class="x-right" style="line-height:40px">共有数据 <?php  echo $total;?> 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>头像</th>
            <th>昵称</th>
            <th>提示语</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $r) { ?>
        <tr>
            <td><img src="<?php  echo $r['avatar'];?>" width="48"></td>
            <td><?php  echo $r['nickname'];?></td>
            <td>
                <input type="text"  value="<?php  echo $r['tip'];?>"  class="layui-input"/>
            </td>
            <td class="td-manage">
                <a class="layui-btn   layui-btn-mini  layui-btn-normal" href="javascript:void(0)" onclick="savetip(this,<?php  echo $r['id'];?>)"><i class="iconfont">&#xe747;</i> 保存</a>
                <a class="layui-btn   layui-btn-mini  layui-btn-danger" href="javascript:void(0)" onclick="barrge_delete(this,<?php  echo $r['id'];?>)"><i class="iconfont">&#xe69d;</i> 删除</a>
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
    /*保存 提示语*/
    function savetip(obj,id){
        var loadii = layer.load();
        var tip=$(obj).parent().parent().find("input").eq(0).val();
        var ajaxurl="<?php  echo webUrl('sale/barrage_updatetipbyid')?>";
        var data= { "id": id, "tip": tip };
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
    /*删除*/
    function barrge_delete(obj,id){
        var loadii = layer.load();
        var ajaxurl="<?php  echo webUrl('sale/barrage_delete')?>";
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

    /*批量更新提示语*/
    function updateall(){
        var tip=$("#tip").val();
        if(tip==""){
            layer.msg("请输入提示语！");
            return false;
        }
        var loadii = layer.load();
        var ajaxurl="<?php  echo webUrl('sale/barrage_updatetip')?>";
        var data= {"tip": tip };
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

    /*批量更新提示语*/
    function tipsinit(){
        var loadii = layer.load();
        var ajaxurl="<?php  echo webUrl('sale/barrage_init')?>";
        $.post(ajaxurl,function(res){
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