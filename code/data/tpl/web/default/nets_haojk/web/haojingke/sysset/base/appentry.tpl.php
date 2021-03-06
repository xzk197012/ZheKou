<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">
    <blockquote class="layui-elem-quote layui-text">
        提供小程序AppID和入口链接给第三方，第三方可在公众号菜单或公众号文章中接入小程序，全自动跟踪佣金。
    </blockquote>
    <div class="layui-form-item">
        <label class="layui-form-label">
            购物首页入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="gouwu"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/index/index">
            <button  type="button" onclick="copygouwu()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            好劵精选入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="haoquan"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/choiceness/index">
            <button  type="button" onclick="copyhaoquan()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            会员中心入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="huiyuan"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/my/index">
            <button  type="button" onclick="copyhuiyuan()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            榜单入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="bangdan"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/seniority/index">
            <button  type="button" onclick="copybangdan()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            2小时跑单入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="twohours"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/seniorityTwohours/index">
            <button  type="button" onclick="copytwohours()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            全天销量入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="alldays"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/seniorityAlldays/index">
            <button  type="button" onclick="copyalldays()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            超级搜索入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="bigsearch"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/search/index">
            <button  type="button" onclick="copybigsearch()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            实时榜单入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="shishi"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/search/index">
            <button  type="button" onclick="copyshishi()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            今日上新入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="todaynew"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/search/index">
            <button  type="button" onclick="copytodaynew()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            抽奖入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="pingou"
                   autocomplete="off" class="layui-input layui-disabled" value="page/pinduoduo/pages/Prizedraw/index">
            <button  type="button" onclick="copypingou()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            砍价入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="sharefreeorder"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/shareFreeOrder/index">
            <button  type="button" onclick="copysharefreeorder()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            代理首页入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="daili"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/index/index?from_uid=会员ID">
            <button  type="button" onclick="copydaili()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">
            商品详情入口
        </label>
        <div class="layui-input-block">
            <input type="text"  id="goods"
                   autocomplete="off" class="layui-input layui-disabled" value="haojk/pages/detail/index?skuId=xxx（商品ID）&from_uid=xxx（会员ID）">
            <button  type="button" onclick="copygoods()" class="layui-btn" >
                复制
            </button>
        </div>
    </div>
</div>
</body>
</html>
<script>
    function copygouwu() {
        var d = document.getElementById("gouwu");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copyhaoquan() {
        var d = document.getElementById("haoquan");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copyhuiyuan() {
        var d = document.getElementById("huiyuan");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copybangdan() {
        var d = document.getElementById("bangdan");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copytwohours() {
        var d = document.getElementById("twohours");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copyalldays() {
        var d = document.getElementById("alldays");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copybigsearch() {
        var d = document.getElementById("bigsearch");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copyshishi() {
        var d = document.getElementById("shishi");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copytodaynew() {
        var d = document.getElementById("todaynew");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copypingou() {
        var d = document.getElementById("pingou");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copysharefreeorder() {
        var d = document.getElementById("sharefreeorder");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copydaili() {
        var d = document.getElementById("daili");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
    function copygoods() {
        var d = document.getElementById("goods");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }
</script>