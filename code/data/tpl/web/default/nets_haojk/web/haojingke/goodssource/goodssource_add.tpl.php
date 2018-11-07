<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<link href="./resource/css/bootstrap.min.css?v=20180110" rel="stylesheet">
<link href="./resource/css/common.css?v=20170719" rel="stylesheet">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<script>var require = { urlArgs: 'v=201180110' };</script>
<script type="text/javascript" src="./resource/js/lib/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="./resource/js/lib/bootstrap.min.js"></script>
<script type="text/javascript" src="./resource/js/app/util.js?v=20180110"></script>
<script type="text/javascript" src="./resource/js/app/common.min.js?v=20170719"></script>
<script type="text/javascript" src="./resource/js/require.js?v=20180110"></script>
<script type="text/javascript" src="<?php echo NETS_HAOJIK_WEB_STYLE;?>js/jquery.nestable.js"></script>
<body>
<div class="x-body">
    <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
        <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>商品源类型
            </label>
            <div class="layui-input-block">
                <label ><input title="条件筛选商品" name="type" type="radio" id="status-1" <?php  if($edit_data['type']!=3) { ?> checked="checked" <?php  } ?> value="1" onclick="$('#filterpanel').show();$('#icon').hide();"> </label>
                <label ><input title="自选商品" name="type" type="radio"  <?php  if($edit_data['type']==3) { ?> checked="checked" <?php  } ?> value="3" onclick="$('#filterpanel').hide();$('#icon').show();"</label>
                <span class="x-red"></span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>商品源名称
            </label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="name"
                       autocomplete="off" class="layui-input" value="<?php  echo $edit_data['name'];?>">
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
            </div>
        </div>
        <div id="icon">
            <div class="layui-form-item">
                <label class="layui-form-label">
                    商品源图标
                </label>
                <div class="layui-input-inline4">
                    <?php  echo tpl_form_field_image('icon',$edit_data['icon'])?>
                </div>
                <!--<label class="layui-form-label">-->
                <!--</label>-->
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>设置商品源图标,建议50*50
                </div>
            </div>
        </div>
        <div id="filterpanel">
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>券后价
                </label>
                <div class="layui-input-inline4">
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="minprice" id="minprice" type="text" value="<?php  echo $price_name['value']['minprice'];?>" placeholder="￥" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="maxprice" id="maxprice" type="text" value="<?php  echo $price_name['value']['maxprice'];?>" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <!--<label class="layui-form-label">-->
                <!--</label>-->
                <div class="layui-form-mid layui-word-aux">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>佣金比例
                </label>
                <div class="layui-input-inline4">
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="mincommission" id="mincommission" type="text"  value="<?php  echo $commission_name['value']['mincommission'];?>"  placeholder="%" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="maxcommission" id="maxcommission" type="text"  value="<?php  echo $commission_name['value']['maxcommission'];?>"  placeholder="%" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <!--<label class="layui-form-label">-->
                <!--</label>-->
                <div class="layui-form-mid layui-word-aux">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>佣金金额
                </label>
                <div class="layui-input-inline4">
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="mincommissionpirce" id="mincommissionpirce" type="text" value="<?php  echo $commissionpirce_name['value']['mincommissionpirce'];?>"  placeholder="￥" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="maxcommissionpirce" id="maxcommissionpirce" type="text" value="<?php  echo $commissionpirce_name['value']['maxcommissionpirce'];?>" placeholder="￥" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <!--<label class="layui-form-label">-->
                <!--</label>-->
                <div class="layui-form-mid layui-word-aux">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    排序
                </label>
                <div class="layui-input-inline4">
                    <select   name="sort" id="sort">
                        <option value ="0" <?php  if($sort_name['value']=='0') { ?>selected="selected"<?php  } ?>>默认排序</option>
                        <option value ="1" <?php  if($sort_name['value']=='1') { ?>selected="selected"<?php  } ?>>券后价</option>
                        <option value ="2" <?php  if($sort_name['value']=='2') { ?>selected="selected"<?php  } ?>>券金额</option>
                        <option value ="3" <?php  if($sort_name['value']=='3') { ?>selected="selected"<?php  } ?>>佣金比例</option>
                        <option value ="99" <?php  if($sort_name['value']=='4') { ?>selected="selected"<?php  } ?>>佣金金额</option>
                        <option value ="98" <?php  if($sort_name['value']=='6') { ?>selected="selected"<?php  } ?>>2小时销量</option>
                        <option value ="98" <?php  if($sort_name['value']=='7') { ?>selected="selected"<?php  } ?>>当天销量</option>
                        <option value ="98" <?php  if($sort_name['value']=='8') { ?>selected="selected"<?php  } ?>>人气榜</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>关键词
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="keyword" lay-verify="keyword"
                           autocomplete="off" class="layui-input" value="<?php  echo $keyword['value'];?>">
                </div>
                <!--<label class="layui-form-label">-->
                <!--</label>-->
                <div class="layui-form-mid layui-word-aux">
                    关键词,多个关键词以英文逗号,拆分
                </div>
            </div></div>
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <div class="layui-input-block">
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                    提交
                </button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
<script>
    <?php  if($edit_data['type']!=3) { ?>
    $('#filterpanel').show();$('#icon').hide();
    <?php  } else { ?>
    $('#filterpanel').hide();$('#icon').show();
    <?php  } ?>


        layui.use(['form','layer'], function(){
            $ = layui.jquery;
            var form = layui.form
                ,layer = layui.layer;

            //自定义验证规则
            form.verify({
                name: function(value){
                    if(value.length <=0){
                        return '请输入商品源名称！';
                    }
                }
            });
            //监听提交
            form.on('submit(add)', function(data){
                var loadii = layer.load();
                console.log(data);
                //发异步，把数据提交给php
                var ajaxurl="<?php  echo webUrl('goodssource/goodssource_addpost')?>";
                $.post(ajaxurl,data.field,function(res){
                    var res=JSON.parse(res);
                    layer.close(loadii);
                    if(res.status==1){
                        layer.alert(res.result.message, {icon: 6,closeBtn: 0},function () {
                            // 获得frame索引
                            
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                            //parent.location.href=parent.location.href;
                        });
                    } else {
                        layer.msg(res.message);
                    }
                });
                return false;
            });
        });

</script>