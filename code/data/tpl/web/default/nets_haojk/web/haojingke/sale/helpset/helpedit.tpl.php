<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<!--<link href="./resource/affordable/css/bootstrap.min.css?v=20170915" rel="stylesheet">-->
<!--<link href="./resource/affordable/css/commona.css?v=20170915" rel="stylesheet">	-->
<link href="./resource/css/bootstrap.min.css?v=20170719" rel="stylesheet">
<link href="./resource/css/common.css?v=20170719" rel="stylesheet">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript">
    if(navigator.appName == 'Microsoft Internet Explorer'){
        if(navigator.userAgent.indexOf("MSIE 5.0")>0 || navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0) {
            alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
        }
    }
    window.sysinfo = {
    <?php  if(!empty($_W['uniacid'])) { ?>'uniacid': '<?php  echo $_W['uniacid'];?>',<?php  } ?>
    <?php  if(!empty($_W['acid'])) { ?>'acid': '<?php  echo $_W['acid'];?>',<?php  } ?>
    <?php  if(!empty($_W['openid'])) { ?>'openid': '<?php  echo $_W['openid'];?>',<?php  } ?>
    <?php  if(!empty($_W['uid'])) { ?>'uid': '<?php  echo $_W['uid'];?>',<?php  } ?>
    'isfounder': <?php  if(!empty($_W['isfounder'])) { ?>1<?php  } else { ?>0<?php  } ?>,
        'siteroot': '<?php  echo $_W['siteroot'];?>',
            'siteurl': '<?php  echo $_W['siteurl'];?>',
            'attachurl': '<?php  echo $_W['attachurl'];?>',
            'attachurl_local': '<?php  echo $_W['attachurl_local'];?>',
            'attachurl_remote': '<?php  echo $_W['attachurl_remote'];?>',
            'module' : {'url' : '<?php  if(defined('MODULE_URL')) { ?><?php echo MODULE_URL;?><?php  } ?>', 'name' : '<?php  if(defined('IN_MODULE')) { ?><?php echo IN_MODULE;?><?php  } ?>'},
        'cookie' : {'pre': '<?php  echo $_W['config']['cookie']['pre'];?>'},
        'account' : <?php  echo json_encode($_W['account'])?>,
    };
</script>
<script>var require = { urlArgs: 'v=20170915' };</script>
<script type="text/javascript" src="./resource/js/lib/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="./resource/js/lib/bootstrap.min.js"></script>
<script type="text/javascript" src="./resource/js/app/util.js?v=20170719"></script>
<script type="text/javascript" src="./resource/js/app/common.min.js?v=20170719"></script>
<script type="text/javascript" src="./resource/js/require.js?v=20170719"></script>
<body>
<div class="x-body">


    <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
        
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>奖品名称
            </label>
            <div class="layui-input-block">
                <input type="text" name="title"   class="layui-input"  value="<?php  echo $help['title'];?>">
            </div>

        </div>
        <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>奖品描述
                </label>
                <div class="layui-input-block">
                    <textarea  name="remark" placeholder="请输入内容" class="layui-textarea"><?php  echo $help['remark'];?></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>助力描述(只有盟主(代理)或合伙人可见)
                    </label>
                    <div class="layui-input-block">
                        <textarea  name="dlremark" placeholder="每日邀请满10人抽奖，最高可将您的佣金分成提升10%" class="layui-textarea"><?php  echo $help['dlremark'];?></textarea>
                    </div>
                </div>
            <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>分享图标
                    </label>
                    <div class="layui-input-inline4">
                        <?php  echo tpl_form_field_image('logo',$help['logo'])?>
                    </div>
                    <!--<label class="layui-form-label">-->
                    <!--</label>-->
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>
                    </div>
            </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>助力次数
            </label>
            <div class="layui-input-inline">
                <input type="text" name="maxhelp"   class="layui-input"  value="<?php  echo $help['maxhelp'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">活动最大助力次数</div>
        </div>
        <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>模板消息
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="tplmsg"   class="layui-input"  value="<?php  echo $help['tplmsg'];?>">
                </div>
                <div class="layui-form-mid layui-word-aux">模板消息设置,搜索 抽奖结果通知，依次按顺序选择 活动商品、注意事项 2个关键词添加</div>
            </div>
            <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>奖励
                    </label>
                    <div class="layui-input-inline12">
                        <label style="float:left;">
                            <input  title="&nbsp;实物"  name="type" type="radio" value="1" <?php  if($help['type']==1 || empty($help['type'])) { ?>checked="checked"<?php  } ?>/>
                        </label>&emsp;&emsp;
                        <label  style="float:left;">
                            <input  title="&nbsp;红包 "  name="type" type="radio" value="2" <?php  if($help['type']==2) { ?>checked="checked"<?php  } ?>/>
                        </label>
                        <label  style="float:left;">
                            <input  title="&nbsp;余额 "  name="type" type="radio" value="3" <?php  if($help['type']==3) { ?>checked="checked"<?php  } ?>/>
                        </label>
                    </div>
                    <!--<label class="layui-form-label">-->
                    <!--</label>-->
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>实物请在描述和活动图片中做详细描述，红包开奖后直接发放到会员微信钱包，请保证企业商户内余额充足
                    </div>
                </div>
                <div class="layui-form-item">
                        <label class="layui-form-label">
                            <span class="x-red">*</span>红包金额
                        </label>
                        <div class="layui-input-inline">
                            <input type="text" name="money"   class="layui-input"  value="<?php  echo $help['money'];?>">
                        </div>
                        <div class="layui-form-mid layui-word-aux">奖励为红包时才生效</div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>中奖人数
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" name="targetwins"   class="layui-input"  value="<?php  echo $help['targetwins'];?>">
                    </div>
                    <div class="layui-form-mid layui-word-aux">多少人中奖</div>
                </div>
        <div class="layui-form-item">
                <label class="layui-form-label">开奖时间</label>
                <div class="layui-input-inline" style="width: 60px;">
                    <select name="help_h">
                        <?php  for($i=1;$i<=24;$i++){ ?>
                      <option value="<?php  echo $i;?>"  <?php  if($help_h==$i) { ?>selected="selected"<?php  } ?>><?php  echo $i;?></option>
                      <?php  }?>

                    </select>
                  </div>
                  <div class="layui-input-inline" style="width: 60px;">
                    <select name="help_m">
                      <option value="00" <?php  if($help_m=="00") { ?>selected="selected"<?php  } ?>>00</option>
                      <option value="30" <?php  if($help_m=="30") { ?>selected="selected"<?php  } ?>>30</option>
                    </select>
                  </div>
                <div class="layui-form-mid layui-word-aux">每天开奖时间</div>
        </div>

        <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>奖品图片
                </label>
                <div class="layui-input-inline4">
                    <?php  echo tpl_form_field_image('picture',$helppicture['0'])?>
                </div>

                <!--<label class="layui-form-label">-->
                <!--</label>-->
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>
                </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>
            </label>
            <div class="layui-input-inline4">
                <?php  echo tpl_form_field_image('picture1',$helppicture['1'])?>
            </div>

            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>
            </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            <span class="x-red">*</span>
        </label>
        <div class="layui-input-inline4">
            <?php  echo tpl_form_field_image('picture2',$helppicture['2'])?>
        </div>

        <!--<label class="layui-form-label">-->
        <!--</label>-->
        <div class="layui-form-mid layui-word-aux">
            <span class="x-red">*</span>
        </div>
</div>

        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <input type="hidden" name="id" value="<?php  echo $help['id'];?>"/>
            <input type="hidden" name="op" value="post"/>
            <input type="hidden" name="ispost" value="1"/>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            <button  class="layui-btn" lay-filter="add" lay-submit="">
                提交
            </button>
        </div>
    </form>

</div>
</body>
</html>

<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //监听提交
        form.on('submit(add)', function(data){
            var loadii = layer.load();
            console.log(data);
            //发异步，把数据提交给php
            var ajaxurl="<?php  echo webUrl('sale/helpset/helpset_addpost').'&id='.$_GPC['id']?>";
            $.post(ajaxurl,data.field,function(res){
                var res=JSON.parse(res);
                layer.close(loadii);
                if(res.status==1){
                    layer.alert("操作成功", {icon: 6,closeBtn: 0},function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                        parent.location.href=parent.location.href;
                    });
                }else{
                    layer.msg("操作失败");
                }
            });
            return false;
        });
    });
</script>
<script>
        layui.use('laydate', function(){
          var laydate = layui.laydate;
          //时间选择器
          laydate.render({
            elem: '#endtime'
            ,type: 'time'
          });
        });
</script>