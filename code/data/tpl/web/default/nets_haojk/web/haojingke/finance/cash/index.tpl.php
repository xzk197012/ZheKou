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
            <input type="hidden" name="r" value="<?php  echo $_W['action'];?>">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="c" value="site">
            <input type="hidden" name="import" value="0" id="import">

            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">日期范围</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" placeholder="日期范围" name="daterange" id="daterange" value="<?php  echo $_GPC['daterange'];?>">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select   id="cashtype" name="cashtype">
                            <option value="5"<?php  if($_GPC['cashtype']=="5") { ?>selected="true"<?php  } ?>>全部</option>
                            <option value="1" <?php  if($_GPC['cashtype']==1 ) { ?>selected="true"<?php  } ?>>微信</option>
                            <option value="2" <?php  if($_GPC['cashtype']==2) { ?>selected="true"<?php  } ?>>支付宝</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">关键字</label>
                    <div class="layui-input-inline">
                        <input type="text" value="<?php  echo $_GPC['keyword'];?>"  name="keyword"  placeholder="昵称、手机号" autocomplete="off" class="layui-input">
                    </div>
                    <button class="layui-btn"  lay-submit="" lay-filter="sreach" onclick="$('#import').val(0);"><i class="layui-icon">&#xe615;</i></button>
                    <?php  if($_GPC['r']=="finance.cash.pay"||$_GPC['r']=="finance.cash"||$_GPC['r']=="finance.cash.index") { ?>
                    <button class="layui-btn layui-btn-normal"   onclick="$('#import').val(1);"><i class="iconfont">&#xe718;</i> 导出 </button>
                    <?php  } ?>
                </div>
            </div>
        </form>
    </div>
    <!--<xblock>-->
    <!--<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量禁用</button>-->
    <!--<button class="layui-btn" onclick="hjk_dialog_show('添加','<?php  echo webUrl('sysset/gradefy/add');?>')"><i class="layui-icon"></i>添加</button>-->
    <!--<span class="x-right" style="line-height:40px">共有数据 <?php  echo $allcount;?> 条</span>-->
    <!--</xblock>-->
    <table class="layui-table">
        <thead>
        <tr >
            <th>ID</th>
            <th>会员</th>
            <th>手机号</th>
            <th>金额</th>
            <th>提现类型</th>
            <th class="w300">备注</th>
            <th>申请时间</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $r) { ?>
        <tr>
            <td class="text-center">
                <?php  echo $r['memberid'];?>
            </td>
            <td class="text-center">
                <?php  echo $r['nickname'];?>
            </td>
            <td class="text-center">
                <?php  echo $r['mobile'];?>
            </td>
            <td class="text-center">
                ￥<?php  echo $r['money'];?>
            </td>
            <td class="text-center" >
                <?php  if($r['cashtype']==2) { ?> 支付宝提现<?php  } else { ?>微信提现<?php  } ?>
            </td>
            <td class="text-left" style="font-size:11px;">
                <?php  echo $r['remark'];?>
            </td>
            <td class="text-center" >
                <?php  echo date('Y-m-d H:i:s', $r['created_at'])?>
            </td>
            <td class="text-center">
                <?php  if($r['status']==0) { ?> 申请提现<?php  } else if($r['status']==1) { ?>已完成<?php  } else if($r['status']==2) { ?>已拒绝<?php  } ?>
            </td>
            <td class="text-center">
                <?php   $alipayindex= strpos($r['remark'],'支付宝已打款')?>
                <?php  if($r['cashtype']==2 && (empty($alipayindex))&&$r['status']==0) { ?>

                <a onclick="cash_alipay(<?php  echo $r['id'];?>)" href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe69d;</i> 支付宝付款</a>

                <?php  } ?>
                 <?php  if($r['status']==0) { ?>
                <?php  if($r['cashtype']==1) { ?>
                <a onclick="cash_wechart(<?php  echo $r['id'];?>)" href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe69d;</i> 微信付款</a>

                <?php  } ?>
                <a onclick="cash_allow(<?php  echo $r['id'];?>)" href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe69d;</i> 手动发放</a>
                <a onclick="cash_refuse(<?php  echo $r['id'];?>)" href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-danger" ><i class="iconfont">&#xe69d;</i> 拒绝</a>

                <?php  } ?>

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

    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //监听提交
        form.on('submit(export)', function(data){
            data.field.import = 1;
        });
    });
    /*支付宝付款*/
    function cash_alipay(id){
        layer.confirm('已经支付宝打款,确定同意提现申请？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('finance/cash/cash_alipay').'&id='?>"+id;
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

    /*微信付款*/
    function cash_wechart(id){
        layer.confirm('同意后直接进行微信支付,确定同意提现申请？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('finance/cash/cash_wechart').'&id='?>"+id;
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
    /*手动发放*/
    function cash_allow(id){
        layer.confirm('同意后直接进行手动发放,确定同意提现申请？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('finance/cash/cash_allow').'&id='?>"+id;
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
    /*删除*/
    function cash_refuse(id){
        layer.confirm('拒绝后不可恢复,确认确定拒绝提现申请？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('finance/cash/cash_refuse').'&id='?>"+id;
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