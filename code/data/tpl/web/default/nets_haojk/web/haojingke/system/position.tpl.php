<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<link href="./resource/css/bootstrap.min.css?v=20180110" rel="stylesheet">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">
    <div class="layui-row">
        <form action="" method="get"  enctype="multipart/form-data" class="layui-form layui-form-pane layui-col-md12">
            <input type="hidden" name="m" value="nets_haojk">
            <input type="hidden" name="do" value="web">
            <input type="hidden" name="r" value="system.position">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="c" value="site">

            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">日期范围</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" placeholder="日期范围" name="daterange" id="daterange" value="<?php  echo $_GPC['daterange'];?>">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-inline">
                        <select  name="pstate" id="pstate">
                            <option value ="" <?php  if($_GPC['pstate']=='') { ?>selected="selected"<?php  } ?>>全部</option>
                            <option value ="1" <?php  if($_GPC['pstate']=='1') { ?>selected="selected"<?php  } ?>>已用</option>
                            <option value ="2" <?php  if($_GPC['pstate']=='2') { ?>selected="selected"<?php  } ?>>未用</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">关键字</label>
                    <div class="layui-input-inline">
                        <input type="text" value="<?php  echo $_GPC['keyword'];?>"  name="keyword"  placeholder="推广位ID、推广位名称、会员ID" autocomplete="off" class="layui-input">
                    </div>
                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                </div>
            </div>
        </form>
    </div>
    <xblock>
        <!--<button class="layui-btn " onclick="pid_update()"> 同步推广位</button>-->
        <span class="x-right" style="line-height:40px">共 <?php  echo $allcount;?> ，已用 <?php  echo $usecount;?> ，剩余<?php  echo intval($allcount)-intval($usecount)?></span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>推广位</th>
            <th>描述</th>
            <th>会员</th>
            <th>分配时间</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $r) { ?>
        <tr>
            <td >
                <?php  if($r['state']==1) { ?><span style="color:red">已用</span><?php  } else { ?><span style="color:#009688">未用</span><?php  } ?>
                <?php  echo $r['bitno'];?>
            </td>
            <td>
                <?php  echo $r['remark'];?>
            </td>
            <td>
                <?php  if(!empty($r['avatar'])) { ?>
                <img src='<?php  echo tomedia($r['avatar'])?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' />
                <?php  } ?>
                <span style="font-size:11px">[ID:<?php  echo $r['memberid'];?>]
                          ~
                        [昵称:<?php  echo get_fromnickname($r['memberid'])?>]</span>
            </td>
            <td>
                <?php  if($r['state']==1) { ?>
                <?php  if(!empty($r['updated_at'])) { ?>
                <?php  echo date("Y-m-d H:i:s",$r['updated_at'])?>
                <?php  } else if(!empty($r['created_at'])) { ?>
                <?php  echo date("Y-m-d H:i:s",$r['created_at'])?>
                <?php  } ?>
                <?php  } ?>
            </td>
            <td class="td-manage">
                <a onclick="pid_allot('<?php  echo $r['id'];?>')" href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6f5;</i> 手动分配</a>
                <a  onclick="hjk_dialog_show('编辑会员信息','<?php  echo url('mc/member',array('do'=>'post','uid'=>$r['memberid']))?>')"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6b8;</i> 会员信息</a>
                <a   onclick="hjk_dialog_show('编辑会员信息','<?php  echo url('site/entry',array('m'=>'nets_haojk','do'=>'web','r'=>'order.index','keyword'=>$r['nickname']))?>')"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe723;</i> 订单(预估)</a>

                <a onclick="pid_delete('<?php  echo $r['id'];?>')" href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-danger" ><i class="iconfont">&#xe69d;</i> 删除</a>
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
    /*手动分配*/
    function pid_allot(id){
        //prompt层
        layer.prompt({title: '请输入分配用户的openid！', formType: 0}, function(openid, index){
            if(openid==''){
                layer.msg("请要分配用户的openid");
                return false;
            }
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('system/position_allot').'&id='?>"+id+"&openid="+openid;
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
            layer.close(index);
        });
    }
    /*删除*/
    function pid_delete(id){
        layer.confirm('删除后不可恢复,确认要删除么？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('system/position_delete').'&id='?>"+id;
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
        });
    }
    /*同步推广位*/
    function pid_update(){
        layer.confirm('推广位越多同步时间越长，你可以刷新此页面终止或者重试,现在开始同步吗？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('system/position_update')?>";
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
        });
    }

    layui.use('laydate', function(){
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#daterange' //指定元素
            ,range: '~'
            , max:0
        });
    });

</script>