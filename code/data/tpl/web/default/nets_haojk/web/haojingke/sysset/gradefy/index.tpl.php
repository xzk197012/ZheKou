<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">

    <xblock>
        <button class="layui-btn " onclick="grade_init()">一键初始化</button>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <!--<th>-->
                <!--<div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>-->
            <!--</th>-->
            <th>等级</th>
            <th>身份</th>
            <th>显示<br/>等级</th>
            <th>显示<br/>身份</th>
            <th>签到<br/>积分</th>
            <th>订单<br/>积分</th>
            <th>推荐<br/>盟主积分</th>
            <th>推荐<br/>好友积分</th>
            <th>一级<br/>佣金</th>
            <th>二级<br/>佣金</th>
            <th>三级<br/>佣金</th>
            <th>助力<br/>最高佣金</th>
            <th>结算<br/>次数</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($data)) { foreach($data as $r) { ?>
        <tr>
            <!--<td>-->
                <!--<div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='2'><i class="layui-icon">&#xe605;</i></div>-->
            <!--</td>-->
            <td >
                <?php  if($r['name'] == 0) { ?>普通<?php  } else if($r['name'] == 1) { ?>白银<?php  } else if($r['name'] == 2) { ?> 黄金<?php  } else if($r['name'] == 3) { ?>铂金 <?php  } else if($r['name'] == 4) { ?>钻石<?php  } ?>

            </td>
            <td>
                <?php  if($r['type'] == 0) { ?>会员<?php  } else if($r['type'] == 1) { ?>盟主<?php  } else if($r['type'] == 2) { ?>合伙人<?php  } ?>
            </td>
            <td >

                <?php  if(empty($r['gradename'])) { ?>
                <?php  if($r['name'] == 0) { ?>普通<?php  } else if($r['name'] == 1) { ?>白银<?php  } else if($r['name'] == 2) { ?> 黄金<?php  } else if($r['name'] == 3) { ?>铂金 <?php  } else if($r['name'] == 4) { ?>钻石<?php  } ?>
                <?php  } else { ?>
                <?php  echo $r['gradename'];?><?php  } ?>
            </td>
            <td>
                <?php  if(empty($r['identityname'])) { ?>
                <?php  if($r['type'] == 0) { ?>会员<?php  } else if($r['type'] == 1) { ?>盟主<?php  } else if($r['type'] == 2) { ?>合伙人<?php  } ?>
                <?php  } else { ?>
                <?php  echo $r['identityname'];?><?php  } ?>
            </td>
            <td>
                <?php  echo $r['sign_credit1'];?>
            </td>
            <td>
                <?php  echo $r['order_credit1'];?>
            </td>
            <td>
                <?php  echo $r['releader_credit1'];?>
            </td>
            <td>
                <?php  echo $r['refriend_credit1'];?>
            </td>
            <td>
                <?php  echo $r['myteam_credit2'];?>%
            </td>
            <td>
                <?php  echo $r['myleader1_credit2'];?>%
            </td>
            <td>
                <?php  echo $r['myleader2_credit2'];?>%
            </td>
            <td >
                <?php  echo $r['max_credit2'];?>%
            </td>
            <td >
                <?php  echo $r['commission_formonth'];?>次
            </td>
            <td class="td-manage">
                <a title="编辑"  class="layui-btn layui-btn-normal layui-btn-mini"  onclick="hjk_dialog_show('编辑','<?php  echo webUrl('sysset/gradefy/add');?>&id=<?php  echo $r['id'];?>')" href="javascript:;">
                    <i class="layui-icon">&#xe63c;</i> 编辑
                </a>
                <?php  if($r['name'] != 0 || $r['type']==2) { ?>
                <?php  if($r['isuse'] == 1) { ?>
                <a title="停用" class="layui-btn layui-btn-danger layui-btn-mini "  onclick="grade_stop(this,'<?php  echo $r['id'];?>')" href="javascript:;">
                    <i class="iconfont">&#xe71a;</i> 停用
                </a>
                <?php  } else { ?>
                <a title="启用" class="layui-btn   layui-btn-mini"  onclick="grade_start(this,'<?php  echo $r['id'];?>')" href="javascript:;">
                    <i class="iconfont">&#xe6b1;</i> 启用
                </a>
                <?php  } ?>
                <?php  } ?>

                <!--<a class="btn btn-default  btn-sm" target="_self" href="../web/index.php?c=site&a=entry&op=memberlevel_add&do=memberlevel&m=nets_haojk&id=<?php  echo $r['id'];?>">编辑</a>-->
                <!--<a onclick="return confirm('确定要删除吗？删除后将无法恢复！');" href="<?php  echo url('site/entry',array('m'=>'nets_haojk','do'=>'memberlevel','op'=>'del','id'=>$r['id']))?>" class="btn btn-primary btn-sm" title="" data-original-title="点此删除">删除</a>-->
            </td>
        </tr>
        <?php  } } ?>
        </tbody>
    </table>
    <!--<div class="page">-->
        <!--<div>-->
            <!--<a class="prev" href="">&lt;&lt;</a>-->
            <!--<a class="num" href="">1</a>-->
            <!--<span class="current">2</span>-->
            <!--<a class="num" href="">3</a>-->
            <!--<a class="num" href="">489</a>-->
            <!--<a class="next" href="">&gt;&gt;</a>-->
        <!--</div>-->
    <!--</div>-->

</div>
<img src="http://www.haojingke.com/images/gradyfyset.png"/>
</body>
</html>

<script>
    /*i初始化*/
    function grade_init(){
        layer.confirm('确认要初始化吗，初始化会删除现有的分佣设置，建议备份后在删除！！！',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('sysset/gradefy/init')?>";
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

    /*停用*/
    function grade_stop(obj,id){
        layer.confirm('确认要停用吗？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('sysset/gradefy/stop').'&id='?>"+id;
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

    /*启用*/
    function grade_start(obj,id){
        layer.confirm('确认要启用吗？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('sysset/gradefy/start').'&id='?>"+id;
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
</script>