<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">

    <xblock>
        <button class="layui-btn " onclick="hjk_dialog_show('添加','<?php  echo webUrl('sale/helpset/helpset_add');?>&id=0')">添加</button>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>图标</th>
            <th>标题</th>
            <th>奖励</th>
            <th>结束时间</th>
            <th>中奖人数</th>
            <th>最大助力数</th>
            <th>状态</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($res["list"])) { foreach($res["list"] as $r) { ?>
        <tr>
            <td >
                <img width="50" src="<?php  echo $r['logo'];?>"/>
            </td>
            <td>
                <?php  echo $r['title'];?>
            </td>
            <td >
                <?php  if($r['type']==1) { ?>实物<?php  } else if($r['type']==2) { ?>红包 ￥<?php  echo $r['money'];?><?php  } else if($r['type']==3) { ?>余额 ￥<?php  echo $r['money'];?><?php  } ?>
            </td>
            <td>
                每天 <?php  echo $r['endtime'];?> 开奖
            </td>
            <td>
                <?php  echo $r['targetwins'];?>
            </td>
            <td>
                <?php  echo $r['maxhelp'];?>
            </td>
            <td>
                <?php  if($r['startime']==0) { ?><span style="color:#1E9FFF;">启用</span><?php  } else { ?><span style="color:#FF5722;">停用</span><?php  } ?>
            </td>
            
            <td class="td-manage">
                <a title="编辑"  class="layui-btn layui-btn-normal layui-btn-mini"  onclick="hjk_dialog_show('编辑','<?php  echo webUrl('sale/helpset/helpset_add');?>&id=<?php  echo $r['id'];?>')" href="javascript:;">
                    <i class="layui-icon">&#xe63c;</i> 编辑
                </a>
                
                <a title="停用" class="layui-btn layui-btn-danger layui-btn-mini "  onclick="helpset_stop(this,'<?php  echo $r['id'];?>')" href="javascript:;">
                    <i class="iconfont">&#xe71a;</i> <?php  if($r['startime']==0) { ?>停用<?php  } else { ?>启用<?php  } ?>
                </a>
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
</body>
</html>

<script>
   

    /*停用*/
    function helpset_stop(obj,id){
        layer.confirm('确认要停用吗？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('sale/helpset/helpset_delete').'&id='?>"+id;
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