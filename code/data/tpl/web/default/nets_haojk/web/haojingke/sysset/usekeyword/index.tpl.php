<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">

    <xblock>
        <a class="layui-btn " href="<?php  echo webUrl('sysset/usekeyword/edit');?>">添加</a>
    </xblock>
    <div class="x-body">
        <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
            
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>默认客服消息
                </label>
                <div class="layui-input-block">
                    <textarea name="service_msg" lay-verify="service_msg" rows="8" style="height:150px"
                           autocomplete="off" class="layui-input"><?php  echo $r['service_msg'];?></textarea>
                </div>
                <label class="layui-form-label">
                </label>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>默认客服消息
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                </label>
                <div class="layui-input-block">
                    <input type="hidden" name="menu_name" value="小程序设置"/>
                    <input type="hidden" name="op" value="post"/>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                        提交
                    </button>
                </div>
            </div>
        </form>
    </div>
    <table class="layui-table">
        <thead>
        <tr>
            <!--<th>-->
                <!--<div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>-->
            <!--</th>-->
            <th>关键词</th>
            <th>标题</th>
            <th>描述</th>
            <th>是否启用</th>
            <th>时间</th>
            
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>京东</td>
            <td></td>
            <td>支持京东关键词搜索商品，如 “京东 手机”，默认搜索京东的商品</td>
            <td>启用</td><td></td><td class="td-manage"></td>
        </tr>
        <tr>
            <td>拼多多</td>
            <td></td>
            <td>支持拼多多关键词搜索商品，如 “拼多多 手机”</td>
            <td>启用</td><td></td><td class="td-manage"></td>
        </tr>
        <tr>
            <td>union-click.jd.com,jd.com,http,https</td>
            <td></td>
            <td>支持京东商品链接搜索商品，在京东复制的商品详细页链接,或京东商品短链搜索</td>
            <td>启用</td><td></td><td class="td-manage"></td>
        </tr>
        <tr>
            <td>mobile.yangkeduo.com</td>
            <td></td>
            <td>支持拼多多商品链接搜索商品，在拼多多复制的商品详细页链接</td>
            <td>启用</td><td></td><td class="td-manage"></td>
        </tr>
        <?php  if(is_array($data)) { foreach($data as $r) { ?>
        <tr>
            <td >
                <?php  echo $r['keyword'];?>
            </td>
            <td>
                <?php  echo $r['title'];?>
            </td>
            <td >
                <?php  echo $r['remark'];?>
                <p style="color:#FF5722;font-size: 11px;">
                链接：
                <a style="color:#FF5722;font-size: 11px;" target="_blank" href="<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=helpmsg&m=nets_haojk&k=<?php  echo $r['id'];?>">
                    <?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=helpmsg&m=nets_haojk&k=<?php  echo $r['id'];?>
                </a>
            </p>
            </td>
            <td>
                <?php  if($r['state'] == 0) { ?>未启用<?php  } else if($r['state'] == 1) { ?>启用
                <?php  } else { ?>
                    未启用
                <?php  } ?>
            </td>
            <td >
                <?php  echo date('Y-m-d h:i:s', $r['created_at'])?>
            </td>
            <td class="td-manage">
                <a title="编辑"  class="layui-btn layui-btn-normal layui-btn-mini"  href="<?php  echo webUrl('sysset/usekeyword/edit');?>&id=<?php  echo $r['id'];?>" >
                    <i class="layui-icon">&#xe63c;</i> 编辑
                </a>
                <?php  if($r['state'] == 1) { ?>
                <a title="停用" class="layui-btn layui-btn-danger layui-btn-mini "  onclick="usekeyword_stop(this,'<?php  echo $r['id'];?>')" href="javascript:;">
                    <i class="iconfont">&#xe71a;</i> 停用
                </a>
                <?php  } else { ?>
                <a title="启用" class="layui-btn   layui-btn-mini"  onclick="usekeyword_start(this,'<?php  echo $r['id'];?>')" href="javascript:;">
                    <i class="iconfont">&#xe6b1;</i> 启用
                </a>
                <?php  } ?>
                
                <!--<a class="btn btn-default  btn-sm" target="_self" href="../web/index.php?c=site&a=entry&op=memberlevel_add&do=memberlevel&m=nets_haojk&id=<?php  echo $r['id'];?>">编辑</a>-->
                <a class="layui-btn   layui-btn-mini" onclick="usekeyword_delete(this,'<?php  echo $r['id'];?>')"  class="btn btn-primary btn-sm" title="" data-original-title="点此删除">
                    <i class="layui-icon">&#xe640;</i>删除</a>
                
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
    /*添加*/
    function usekeyword_delete(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('sysset/usekeyword/delete').'&id='?>"+id;
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
    function usekeyword_stop(obj,id){
        layer.confirm('确认要停用吗？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('sysset/usekeyword/stop').'&id='?>"+id;
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
    function usekeyword_start(obj,id){
        layer.confirm('确认要启用吗？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('sysset/usekeyword/start').'&id='?>"+id;
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