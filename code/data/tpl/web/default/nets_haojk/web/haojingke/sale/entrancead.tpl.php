<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
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
    <style>
        .layui-table img {
            max-width: 300px;
        }
        .layui-table td, .layui-table th {
            position: relative;
            padding: 0px 0px;
            min-height: auto;
            line-height: 0px;
            font-size: 14px;
            border: 0px solid #e2e2e2;
            cursor:move;
        }
        .layui-table {
         width: auto; 
            margin: 10px 0;
            background-color: #fff;
        }
        .itemtitle{margin: 10px 60px;font-size:15px;color:gray}
        .layui-input-block {
            margin-left: 20px;
            min-height: 36px;
        }
        .label40{width:40px;}
        .height28{
        height: 28px;
        line-height: 28px;
    }
    .layui-input-inline input[type=checkbox], .layui-form input[type=radio], .layui-form select {
        display: block;
    }
    .layui-select-title{display:none;}
    </style>
<body>
<div class="x-body">
    <blockquote class="layui-elem-quote">入口场景广告设置</blockquote>

    <form action="" method="post" class="layui-form"  enctype="multipart/form-data"  >

        <div class="layui-form-item">
            <label class="layui-form-label">是否启用</label>
            <div class="layui-input-block">
                <input type="checkbox" name="entrancead_status" title="启用" <?php  if($r["entrancead_status"]==1) { ?> checked="" <?php  } ?>>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>入场广告大图
            </label>
            <div class="layui-input-inline4">
                <?php  echo tpl_form_field_image('entrancead_pic',$r['entrancead_pic'])?>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->

        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>跳转页面 
            </label>
            <div class="layui-input-inline">
                <select name="entrancead_jump" class="form-control">
                    <?php  if($_W["wxappauth"]=="1" || $_W["wxgzhauth"]=="1") { ?>
                    <optgroup label="京东页面">
                        <option <?php if($r['entrancead_jump'] ==  '../choiceness/index?name=index') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=index">京东首页</option>
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=choiceness') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=choiceness">好券精选</option>
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=bigsearch') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=bigsearch">超级搜索</option>
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=好货情报局') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=好货情报局">好货情报局</option>
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=砍价') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=砍价">砍价</option>
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=拼购') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=拼购">拼购</option>
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=榜单') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=榜单">榜单</option>
                        <option <?php if($r['entrancead_jump'] ==  '../choiceness/index?name=2小时跑单') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=2小时跑单">2小时跑单</option>
                        <option <?php if($r['entrancead_jump'] ==  '../choiceness/index?name=全天销售榜') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=全天销售榜">全天销售榜</option>
                        <option <?php if($r['entrancead_jump'] ==  '../choiceness/index?name=实时排行榜') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=实时排行榜">实时排行榜</option>
                    </optgroup>
                    <?php  } ?>
                    <?php  if($_W["wxpddauth"]=="1") { ?>
                    <optgroup label="拼多多页面">
                        <option <?php if($r['entrancead_jump'] ==  '../choiceness/index?name=pddindex') { ?>selected="selected"<?php  } ?>  value="../choiceness/index?name=pddindex">拼多多首页</option>
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=pddsearch') { ?>selected="selected"<?php  } ?>  value="../choiceness/index?name=pddsearch">搜索列表页</option>
                    </optgroup>
                    <?php  } ?>
                    <?php  if($_W["wxgwqauth"]=="1") { ?>
                    <optgroup label="购物圈页面">
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=gwqindex') { ?>selected="selected"<?php  } ?>  value="../choiceness/index?name=gwqindex">购物圈首页</option>
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=gwqsearch') { ?>selected="selected"<?php  } ?>  value="../choiceness/index?name=gwqsearch">购物圈搜索</option>
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=gwqfriend') { ?>selected="selected"<?php  } ?>  value="../choiceness/index?name=gwqfriend">购物圈好友</option>
                    </optgroup>
                    <?php  } ?>
                    <optgroup label="会员中心">
                        <option <?php if($r['entrancead_jump'] == '../choiceness/index?name=my') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=my">我的</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="layui-form-item" style="padding-left: 50px;">
            <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                提交
            </button>
            </div>
    </form>
</div>
</body>
</html>

<script>var require = { urlArgs: 'v=20170915' };</script>
<script type="text/javascript" src="./resource/js/lib/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="./resource/js/lib/bootstrap.min.js"></script>
<script type="text/javascript" src="./resource/js/app/util.js?v=20170719"></script>
<script type="text/javascript" src="./resource/js/app/common.min.js?v=20170719"></script>
<script type="text/javascript" src="./resource/js/require.js?v=20170719"></script>
