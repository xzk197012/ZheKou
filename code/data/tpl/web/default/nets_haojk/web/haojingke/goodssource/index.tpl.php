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
            <input type="hidden" name="r" value="goodssource.index">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="c" value="site">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">菜单类型</label>
                    <div class="layui-input-inline">
                        <select  id="type" name="type">
                            <option value=""<?php  if($_GPC['type']=="") { ?>selected="true"<?php  } ?>>全部</option>
                            <option value="1" <?php  if($_GPC['type']==1) { ?>selected="true"<?php  } ?>>自定义商品源</option>
                            <option value="3" <?php  if($_GPC['type']==3) { ?>selected="true"<?php  } ?>>自选商品源</option>
                            <option value="2" <?php  if($_GPC['type']==2) { ?>selected="true"<?php  } ?>>分类商品源</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">商品源名称</label>
                    <div class="layui-input-inline">
                        <input type="text" value="<?php  echo $_GPC['name'];?>"  name="name"  placeholder="商品源" autocomplete="off" class="layui-input">
                    </div>
                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>

                </div>
        </div>
        </form>
    </div>
    <xblock>
        <button class="layui-btn" onclick="hjk_dialog_show('添加商品源','<?php  echo webUrl('goodssource/goodssource_add');?>')"><i class="layui-icon"></i>添加商品源</button>
        <span class="x-right" style="line-height:40px">共有数据 <?php  echo $total;?> 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>商品源名称</th>
            <th class="w400">链接</th>
            <th>类型</th>
            <th>时间</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $r) { ?>
        <tr>
            <td >
                <?php  echo $r['name'];?>
            </td>
            <td>
                <?php  if($r['type'] == 1) { ?><?php  } else if($r['type'] == 2) { ?><?php  } else if($r['type'] == 3) { ?>
                <?php  $tempurl=$_W['siteroot'].$this->createMobileUrl("tuiguang",array('cateid'=>$r['id']));
                 echo str_replace('m=','m=nets_haojk',str_replace('/./','/app/',$tempurl));?>
                <?php  } ?>
            </td>
            <td>
                <?php  if($r['type'] == 1) { ?>自定义商品源<?php  } else if($r['type'] == 2) { ?>分类商品源<?php  } else if($r['type'] == 3) { ?>自选商品源<?php  } ?>
            </td>
            <td>
                <?php  echo date("Y-m-d H:i:s",$r['created_at'])?>
            </td>
            <td class="td-manage">
                <?php  if($r['type']==1 || $r['type']==3) { ?>
                <a  onclick="hjk_dialog_show('编辑商品源','<?php  echo webUrl('goodssource/goodssource_add').'&id='.$r['id'];?>')" href="javascript:void(0)" class="layui-btn   layui-btn-mini  layui-btn-normal" title=""><i class="layui-icon">&#xe63c;</i> 编辑</a>
                <a class="layui-btn   layui-btn-mini  layui-btn-danger" href="javascript:void(0)" onclick="goodssource_delete(this,<?php  echo $r['id'];?>)"><i class="iconfont">&#xe69d;</i> 删除</a>
                <?php  } ?>
                <?php  if($r['type']==3) { ?>
                <a href="<?php  echo webUrl('goodssource/sourcegoods').'&cname='.$r['id']?>" class="layui-btn   layui-btn-mini  layui-btn-normal" >商品管理</a>

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
    /*删除*/
    function goodssource_delete(obj,id){
        var loadii = layer.load();
        var ajaxurl="<?php  echo webUrl('goodssource/goodssource_delete')?>";
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