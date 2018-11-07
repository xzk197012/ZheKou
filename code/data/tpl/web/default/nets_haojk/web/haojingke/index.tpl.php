<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php  echo $_W['current_module']['title'];?>-后台管理</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="shortcut icon" href="<?php echo NETS_HAOJIK_WEB_STYLE;?>favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="<?php echo NETS_HAOJIK_WEB_STYLE;?>css/font.css?v=201801291600">
    <link rel="stylesheet" href="<?php echo NETS_HAOJIK_WEB_STYLE;?>css/hjk.css?v=201801291600">
    <script type="text/javascript" src="<?php echo NETS_HAOJIK_WEB_STYLE;?>js/jquery.min.js?v=201801291600"></script>
    <script src="<?php echo NETS_HAOJIK_WEB_STYLE;?>lib/layui/layui.js?v=201801291600" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo NETS_HAOJIK_WEB_STYLE;?>js/hjk.js?v=201801291600"></script>

</head>
<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo">
        <?php  if(file_exists(IA_ROOT. "/addons/". $_W['current_module']['name']. "/icon-custom.jpg")) { ?>
        <img src="<?php  echo tomedia("addons/".$_W['current_module']['name']."/icon-custom.jpg")?>" class="head-app-logo" onerror="this.src='./resource/images/gw-wx.gif'">
        <?php  } else { ?>
        <img src="<?php  echo tomedia("addons/".$_W['current_module']['name']."/icon.jpg")?>" class="head-app-logo" onerror="this.src='./resource/images/gw-wx.gif'">
        <?php  } ?>
        <a href="../web/index.php?c=site&a=entry&do=web&m=nets_haojk"><?php  echo $_W['current_module']['title'];?>-<?php  echo $_W['acctype'];?></a></div>
    <div class="left_open">
        <i title="展开菜单栏" class="iconfont">&#xe699;</i>
    </div>
    <ul class="layui-nav left fast-add" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">+ 快捷菜单</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd>
                    <a onclick="hjk_dialog_show('发放优惠券','<?php  echo webUrl('coupon/coupon_add');?>')"><i class="iconfont">&#xe70c;</i>发放优惠券</a>
                </dd>
                <dd>
                    <a onclick="hjk_dialog_show('添加商品源','<?php  echo webUrl('goodssource/goodssource_add');?>')"><i class="iconfont">&#xe6f6;</i>添加商品源</a>
                </dd>
            </dl>
        </li>
    </ul>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item to-index"><a target="_blank" href="./index.php?c=platform&a=cover&m=nets_haojk">封面链接入口</a></li>
        <li class="layui-nav-item to-index"><a target="_blank" href="./index.php?c=platform&a=reply&m=nets_haojk">关键字链接入口</a></li>
        <li class="layui-nav-item">
            <a href="javascript:;">关闭操作</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a href="javascript:;" onclick="x_closethis()">关闭当前选项卡</a></dd>
                <dd><a href="javascript:;" onclick="x_closeall(); ">关闭全部选项卡</a></dd>
                <dd><a href="javascript:;" onclick="x_closealloutthis(); ">关闭其他选项卡</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item">
            <a href="javascript:;"><?php  echo $_W['user']['username'];?></a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <?php  if($_W['isfounder']) { ?>
                <dd><a target="_blank" href="<?php  echo url('cloud/upgrade');?>">自动更新</a></dd>
                <dd><a target="_blank" href="<?php  echo url('system/updatecache');?>">更新缓存</a></dd>
                <?php  } ?>
                <dd><a href="./index.php?c=home&a=welcome">退出</a></dd>
            </dl>
        </li>

    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6ce;</i>
                    <cite>系统初始化</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <?php  if($_W["wxappauth"]=="1" || $_W["wxgzhauth"]=="1" || $_W["wxgwqauth"]=="1") { ?>
                    <li>
                        <a _href="<?php  echo webUrl('system/index');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>京东联盟</cite>
                        </a>
                    </li >
                    <?php  } ?>
                    <li>
                        <a _href="<?php  echo webUrl('system/initialize');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>一键初始化</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php  echo webUrl('system/restore');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>一键修复</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php  echo webUrl('system/restorerelation');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>重建上下级关系</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php  echo webUrl('system/clearmember');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>一键清理无效会员</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6ae;</i>
                    <cite>基础设置</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>风格菜单</cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a _href="<?php  echo webUrl('sysset/stylemenu/index');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>风格设置</cite>
                                </a>
                            </li >
                            <?php  if($_W["wxappauth"]=="1" ||  $_W["wxgzhauth"]=="1") { ?>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/diypage/index');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>购物首页自定义</cite>
                                </a>
                            </li >
                            <?php  } ?>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/stylemenu/menu_banner');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>轮播菜单</cite>
                                </a>
                            </li>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/stylemenu/menu_top');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>头部菜单</cite>
                                </a>
                            </li>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/stylemenu/menu_pic');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>图片菜单</cite>
                                </a>
                            </li>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/stylemenu/menu_activity');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>活动菜单</cite>
                                </a>
                            </li>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/stylemenu/menu_ad');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>广告图片</cite>
                                </a>
                            </li>
                            <li>
                                    <a _href="<?php  echo webUrl('sysset/diypage/diyfoot');?>">
                                        <i class="iconfont">&#xe6a7;</i>
                                        <cite>底部菜单</cite>
                                    </a>
                                </li>
                                <li>
                                    <a _href="<?php  echo webUrl('sysset/diypage/membermenu');?>">
                                        <i class="iconfont">&#xe6a7;</i>
                                        <cite>会员中心自定义</cite>
                                    </a>
                                </li>
                            <!-- <li>
                                <a _href="<?php  echo webUrl('sysset/stylemenu/menu_bottom');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>底部菜单</cite>
                                </a>
                            </li> -->

                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>基础</cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>
                        <ul class="sub-menu">
                            <?php  if(($_W["wxappauth"]=="1" || $_W["wxgwqauth"]=="1") && $_W['acctype']=="小程序") { ?>
                                <li>
                                    <a _href="<?php  echo webUrl('sysset/base/appentry');?>">
                                        <i class="iconfont">&#xe6a7;</i>
                                        <cite>小程序入口</cite>
                                    </a>
                                </li>
                                <li>
                                    <a _href="<?php  echo webUrl('sysset/base/coupontype');?>">
                                        <i class="iconfont">&#xe6a7;</i>
                                        <cite>京东领券方式</cite>
                                    </a>
                                </li >
                            <?php  } ?>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/base/indexset');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>默认首页设置</cite>
                                </a>
                            </li >
                            <?php  if($_W['isfounder']==1) { ?>
                            <li>
                                    <a _href="<?php  echo webUrl('sysset/base/moduletype');?>">
                                        <i class="iconfont">&#xe6a7;</i>
                                        <cite>功能模块设置</cite>
                                    </a>
                                </li >
                                <?php  } ?>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/base/share');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>分享设置</cite>
                                </a>
                            </li>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/stylemenu/help');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>帮助设置</cite>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php  if($_W['acctype']=="小程序") { ?>
                    <li>
                        <a _href="<?php  echo webUrl('sysset/base/index');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>开放平台</cite>
                        </a>
                    </li >
                    <?php  } ?>
                    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>消息</cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a _href="<?php  echo webUrl('sysset/message/sms');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>短信设置</cite>

                                </a>
                            </li >
                            <li>
                                <a _href="<?php  echo webUrl('sysset/message/index');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>任务处理通知</cite>

                                </a>
                            </li>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/usekeyword');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>客服帮助</cite>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a _href="<?php  echo webUrl('sysset/gradefy');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>分佣设置</cite>
                        </a>
                    </li >
                    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>交易设置</cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a _href="<?php  echo webUrl('sysset/trade/index');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>代理返利设置</cite>
                                </a>
                            </li>
                            <li>
                                <a _href="<?php  echo webUrl('sysset/trade/payment');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>提现设置</cite>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <?php  if(($_W["wxappauth"]=="1" &&  $_W['acctype']=="小程序")|| ($_W["wxgzhauth"]=="1" &&  $_W['acctype']=="公众号")) { ?>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe70c;</i>
                    <cite>查券设置</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php  echo webUrl('coupon/index');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>全网查券</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="<?php  echo webUrl('coupon/mycoupon');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>我发放的券</cite>
                        </a>
                    </li >
                </ul>
            </li >
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe70c;</i>
                    <cite>京东推广位</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php  echo webUrl('system/positionset');?>">
                            <i class="iconfont">&#xe6b3;</i>
                            <cite>设置生成</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="<?php  echo webUrl('system/position');?>">
                            <i class="iconfont">&#xe6b3;</i>
                            <cite>推广位管理</cite>
                        </a>
                    </li >
                </ul>
            </li >
            <li>
                <a _href="<?php  echo webUrl('goodssource/index');?>">
                    <i class="iconfont">&#xe6f6;</i>
                    <cite>京东专题页</cite>
                    <!--<i class="iconfont nav_right">&#xe697;</i>-->
                </a>
            </li>
            <?php  } ?>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe756;</i>
                    <cite>互动营销</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php  echo webUrl('sale/entrancead');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>入口场景广告</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="<?php  echo webUrl('sale/barrage');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>弹幕订单</cite>
                        </a>
                    </li >
                    <?php  if(($_W["wxappauth"]=="1" &&  $_W['acctype']=="小程序")|| ($_W["wxgzhauth"]=="1" &&  $_W['acctype']=="公众号")) { ?>
                    <li>
                        <a _href="<?php  echo webUrl('sale/freegoods');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>砍价免单</cite>
                        </a>
                    </li >
                    <?php  } ?>
                    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>群发助手</cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a _href="<?php  echo webUrl('sale/groupsend');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>发送记录</cite>
                                </a>
                            </li >
                            <li>
                                <a _href="<?php  echo webUrl('sale/groupsend/groupsend_msg_temp_list');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>普通消息模板</cite>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php  if($_W['acctype']=="小程序") { ?>
                    <li>
                            <a _href="<?php  echo webUrl('sale/helpset/index');?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>助力抽奖</cite>
                            </a>
                    </li >
                    <?php  } ?>
                </ul>
            </li>
            <?php  if($_W["wxpddauth"]=="1") { ?>
             <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe70c;</i>
                    <cite>拼多多</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <?php  if($_W['acctype']=="小程序") { ?>
                    <li>
                        <a _href="<?php  echo webUrl('pinduoduo/pddentry');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>小程序入口</cite>
                        </a>
                    </li >
                    <?php  } ?>
                    <li>
                        <a _href="<?php  echo webUrl('pinduoduo/set');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>基础设置</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="<?php  echo webUrl('goodssource/pddgoodsource/index');?>">
                            <i class="iconfont">&#xe6f6;</i>
                            <cite>拼多多专题页</cite>
                            <!--<i class="iconfont nav_right">&#xe697;</i>-->
                        </a>
                    </li>
                    <li>
                        <a _href="<?php  echo webUrl('pinduoduo/diypage');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>自定义首页</cite>
                        </a>
                    </li >
                    <li>
                            <a _href="<?php  echo webUrl('pinduoduo/index');?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>全网查券</cite>
                            </a>
                    </li >
                    <li>
                        <a _href="<?php  echo webUrl('pinduoduo/mycoupon');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>我发放的券</cite>
                        </a>
                    </li >
                    <!-- <li>
                            <a _href="<?php  echo webUrl('pinduoduo/orderlist',array('status'=>0));?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>预估收入</cite>
                            </a>
                    </li > -->
                    <li>
                        <a _href="<?php  echo webUrl('pinduoduo/orderlist',array('status'=>1));?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>订单列表</cite>
                        </a>
                </li >
                    <li>
                        <a _href="<?php  echo webUrl('pinduoduo/useorder');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>返利订单</cite>
                        </a>
                </li >
                    <li>
                            <a _href="<?php  echo webUrl('pinduoduo/pddMember');?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>推广位管理</cite>
                            </a>
                    </li >
                </ul>
            </li >
            <?php  } ?>
             <?php  if($_W["wxpddauth"]=="1") { ?>
             <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe70c;</i>
                    <cite>蘑菇街</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">

                     <li>
                        <a _href="<?php  echo webUrl('mogujie/set');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>基础设置</cite>
                        </a>
                </li >
                    <li>
                            <a _href="<?php  echo webUrl('mogujie/index');?>">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>全网查券</cite>
                            </a>
                    </li >
                    <li>
                        <a _href="<?php  echo webUrl('mogujie/orderlist',array('status'=>1));?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>订单列表</cite>
                        </a>
                </li >
                <li>
                        <a _href="<?php  echo webUrl('mogujie/useorder');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>返利订单</cite>
                        </a>
                </li >

               
                </ul>
            </li >
            <?php  } ?>
            <?php  if($_W["wxgwqauth"]=="1" && $_W['acctype']=="小程序") { ?>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe70c;</i>
                    <cite>购物圈</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <?php  if($_W['acctype']=="小程序") { ?>
                    <li>
                        <a _href="<?php  echo webUrl('feed/feedentry');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>小程序入口</cite>
                        </a>
                    </li >
                    <?php  } ?>
                    <li>
                        <a _href="<?php  echo webUrl('feed/index');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>基础设置</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="<?php  echo webUrl('feed/top_menu');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>头部轮播</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="<?php  echo webUrl('feed/second_menu');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>图片菜单</cite>
                        </a>
                    </li >
                </ul>
            </li >
            <?php  } ?>
            <li>
                <a _href="<?php  echo webUrl('member');?>">
                    <i class="iconfont">&#xe6b8;</i>
                    <cite>会员</cite>
                    <!--<i class="iconfont nav_right">&#xe697;</i>-->
                </a>
                <!--<ul class="sub-menu">-->
                    <!--<li>-->
                        <!--<a _href="./index.php?c=site&a=entry&do=member&m=nets_haojk">-->
                            <!--<i class="iconfont">&#xe6a7;</i>-->
                            <!--<cite>会员列表</cite>-->
                        <!--</a>-->
                    <!--</li >-->
                <!--</ul>-->
            </li>

            <?php  if(NETS_HAOJIK_NUMBER=="TRUE") { ?>
            <li>
                <a _href="<?php  echo webUrl('number');?>">
                    <i class="iconfont">&#xe725;</i>
                    <cite>号码池</cite>
                </a>
            </li>
            <?php  } ?>
            <?php  if(($_W["wxappauth"]=="1" || $_W["wxgzhauth"]=="1" || $_W["wxgwqauth"]=="1") && ($_W['acctype']=="小程序" || $_W['acctype']=="公众号")) { ?>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe723;</i>
                    <cite>订单管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php  echo webUrl('order/index');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>订单列表</cite>
                        </a>
                    </li >
                    <!-- <li>
                        <a _href="<?php  echo webUrl('order/order');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>业绩订单</cite>
                        </a>
                    </li> -->
                    <li>
                        <a _href="<?php  echo webUrl('order/userorder');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>返利订单</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="<?php  echo webUrl('order/cuttingorder');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>砍价订单</cite>
                        </a>
                    </li >
                </ul>
            </li>
            <?php  } ?>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe758;</i>
                    <cite>财务管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>收款</cite>
                            <i class="iconfont nav_right">&#xe6a7;</i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a _href="<?php  echo webUrl('finance/recharge');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>收款-全部</cite>

                                </a>
                            </li >
                            <li>
                                <a _href="<?php  echo webUrl('finance/recharge/pay');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>收款-已完成</cite>

                                </a>
                            </li>
                            <li>
                                <a _href="<?php  echo webUrl('finance/recharge/unpay');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>收款-待付款</cite>

                                </a>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>提现</cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a _href="<?php  echo webUrl('finance/cash');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>提现-全部</cite>

                                </a>
                            </li >
                            <li>
                                <a _href="<?php  echo webUrl('finance/cash/unpay');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>提现-待审核</cite>

                                </a>
                            </li>
                            <li>
                                <a _href="<?php  echo webUrl('finance/cash/pay');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>提现-已完成</cite>
                                </a>
                            </li>
                            <li>
                                <a _href="<?php  echo webUrl('finance/cash/refuse');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>提现-已拒绝</cite>

                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
            </li>
            <?php  if($_W["wxappauth"]=="1" || $_W["wxgzhauth"]=="1") { ?>
            <!--<li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe732;</i>
                    <cite>合伙人</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php  echo webUrl('partner/index');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>合伙人列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php  echo webUrl('partner/partner_set');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>合伙人申请设置</cite>
                        </a>
                    </li>
                </ul>
            </li>-->
            <?php  } ?>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe757;</i>
                    <cite>统计分析</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <?php  if($_W["wxappauth"]=="1" || $_W["wxgzhauth"]=="1" || $_W["wxgwqauth"]=="1") { ?>
                    <li>
                        <a _href="<?php  echo webUrl('statistics/orderstatistics');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>订单分析</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="<?php  echo webUrl('statistics/member');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>会员增长趋势</cite>
                        </a>
                    </li>
                    <?php  } ?>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<ul class="rightmenu">
    <li data-type="closethis">关闭当前</li>
    <li data-type="closeall">关闭所有</li>
</ul>
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="true">
        <ul class="layui-tab-title">
            <li>首页</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show" >
                <iframe src='<?php  echo webUrl('statistics');?>' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">Copyright ©2018 <?php  echo $_W['current_module']['title'];?> v<?php  echo $_W['current_module']['version'];?> All Rights Reserved</div>
</div>
<!-- 底部结束 -->
<script>
</script>
</body>
</html>