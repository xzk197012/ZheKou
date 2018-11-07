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
</style>
    <body>
        <div class="x-body">
            <blockquote class="layui-elem-quote">购物首页自定义</blockquote>
            <blockquote class="layui-elem-quote layui-text">
            上下拖拽可改变页面模块顺序，请保持商品列表紧跟头部菜单之后，分类改变后当前页面刷新，不再跳转！
            </blockquote>
            <div action="" method="post"class="layui-form"  enctype="multipart/form-data"  >
            <xblock>
                <input type="button" name="submit" onclick="savePageitem()" value="提交" class="layui-btn " />
                <a class="layui-btn " href="<?php  echo webUrl('sysset/diypage/resetpage');?>" />初始化</a>
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            </xblock>
            <table class="layui-table" style="float:left;">
                <thead>
                        <td style="width:200px;"><span class="itemtitle">页面标题</span></td>
                        <td><input type="text" name="pagetitle" id="pagetitle" lay-verify="" autocomplete="off" placeholder="页面标题" class="layui-input" style="text-align:center;"/>
                        </td>
                        <td style="width:80px;"></td>
                </thead>
                <tbody id="tbody">
                <tr class="cube-item">
                    <td><span class="itemtitle">头部菜单1</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy_goods_filter.jpg" class="usemenu btn-move" data-type="" data-tag="goods_filter" /></td>
                    <td class="td-manage" style="vertical-align: text-top;">
                        <div class="layui-form-item">
                        <div class="layui-input-block"><input type="checkbox"  selectnum="1"  tag="menu" name="itemjson" datatype="diy_goods_filter" dataname="商品筛选" title="显示"></div>
                        </div>
                    </td>
                </tr>         
                <tr class="cube-item">
                    <td><span class="itemtitle">搜索</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy0_search.jpg" class="usemenu btn-move" data-type="" data-tag="search" /></td>
                    <td class="td-manage" style="vertical-align: text-top;">
                        <div class="layui-form-item">
                        <div class="layui-input-block"><input type="checkbox"  selectnum=""  tag="search" name="itemjson" datatype="diy0_search" dataname="商品筛选" title="显示" checked></div>
                        </div>
                    </td>
                </tr>
                <tr class="cube-item">
                    <td><span class="itemtitle">轮播菜单</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy1_banner1.jpg" class="usemenu btn-move" data-type="banner" data-tag="banner"/></td>
                    <td class="td-manage" style="vertical-align: text-top;">
                            <div class="layui-form-item">
                                <div class="layui-input-block"><input type="checkbox"  selectnum=""  tag="banner" name="itemjson" datatype="diy1_banner1" dataname="轮播菜单" title="显示" checked></div>
                            </div>
                    </td>
                </tr>
                <tr class="cube-item">
                    <td><span class="itemtitle">轮播菜单2</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy1_banner2.jpg" class="usemenu btn-move" data-type="banner2" data-tag="banner"/></td>
                    <td class="td-manage" style="vertical-align: text-top;">
                            <div class="layui-form-item">
                                    <div class="layui-input-block"><input type="checkbox"  selectnum=""  tag="banner" name="itemjson"  datatype="diy1_banner2" dataname="轮播菜单2"  title="显示" checked></div>
                                </div>
                    </td>
                </tr>
                <tr class="cube-item">
                    <td><span class="itemtitle">轮播菜单3</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy1_banner3.jpg" class="usemenu btn-move" data-type="banner3" data-tag="banner"/></td>
                    <td class="td-manage" style="vertical-align: text-top;">
                            <div class="layui-form-item">
                                    <div class="layui-input-block"><input type="checkbox"  selectnum=""  tag="banner" name="itemjson"  datatype="diy1_banner3" dataname="轮播菜单3"  title="显示" checked></div>
                                </div>
                    </td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">头部菜单2</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy4_menu1.jpg" class="usemenu btn-move" data-type="head_menu"/></td>
                    <td class="td-manage" style="vertical-align: text-top;">
                            <div class="layui-form-item">
                                    <div class="layui-input-block"><input type="checkbox"  selectnum="1"  tag="menu" name="itemjson" datatype="diy4_menu1" dataname="头部菜单"  title="显示" checked></div>
                                </div>
                    </td>
                </tr>
                <tr class="cube-item">
                        <td ><span class="itemtitle">头部菜单3</span></td>
                        <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy4_menu2.jpg" class="usemenu btn-move" data-type="head_menu2"/></td>
                        <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                                <div class="layui-input-block"><input type="checkbox"  selectnum="1"  tag="menu" name="itemjson" datatype="diy4_menu2" dataname="头部菜单2"  title="显示"></div>
                            </div></td>
                </tr>
                <tr class="cube-item">
                        <td ><span class="itemtitle">头部菜单4</span></td>
                        <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy4_menu3.jpg" class="usemenu btn-move" data-type="head_menu3"/></td>
                        <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                                <div class="layui-input-block"><input type="checkbox"  selectnum="1"  tag="menu" name="itemjson" datatype="diy4_menu3" dataname="头部菜单3"  title="显示"></div>
                            </div></td>
                </tr>
                <tr class="cube-item">
                        <td ><span class="itemtitle">头部菜单5</span></td>
                        <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy4_menu4.jpg" class="usemenu btn-move" data-type="head_menu4"/></td>
                        <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                                <div class="layui-input-block"><input type="checkbox"  selectnum="1"  tag="menu" name="itemjson" datatype="diy4_menu4" dataname="头部菜单4"  title="显示"></div>
                            </div></td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">广告图片</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy5_ad1.jpg" class="usemenu btn-move" data-type="ad_menu"/></td>
                    <td class="td-manage" style="vertical-align: text-top;">
                            <div class="layui-form-item">
                                    <div class="layui-input-block"><input type="checkbox" selectnum=""  tag="ad" name="itemjson" datatype="diy5_ad1" dataname="广告图片"  title="显示" checked></div>
                                </div>
                    </td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">广告图片2</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy5_ad2.jpg" class="usemenu btn-move" data-type="ad_menu2"/></td>
                    <td class="td-manage" style="vertical-align: text-top;">
                            <div class="layui-form-item">
                                    <div class="layui-input-block"><input type="checkbox" selectnum=""  tag="ad" name="itemjson" datatype="diy5_ad2" dataname="广告图片2"  title="显示" checked></div>
                                </div>
                    </td>
                </tr>
                <tr class="cube-item">
                        <td ><span class="itemtitle">广告图片3</span></td>
                        <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy5_ad3.jpg" class="usemenu btn-move" data-type="ad_menu3"/></td>
                        <td class="td-manage" style="vertical-align: text-top;">
                                <div class="layui-form-item">
                                        <div class="layui-input-block"><input type="checkbox" selectnum=""  tag="ad" name="itemjson" datatype="diy5_ad3" dataname="广告图片3"  title="显示" checked></div>
                                    </div>
                        </td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">图片菜单</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy6_picture1.jpg" class="usemenu btn-move" data-type="picture_menu"/></td>
                    <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                            <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="picture" datatype="diy6_picture1" dataname="图片菜单"  title="显示" checked></div>
                        </div></td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">图片菜单2</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy6_picture2.jpg" class="usemenu btn-move" data-type="picture_menu2"/></td>
                    <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                            <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="picture" datatype="diy6_picture2" dataname="图片菜单2"  title="显示" checked></div>
                        </div></td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">图片菜单3</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy6_picture3.jpg" class="usemenu btn-move" data-type="picture_menu3"/></td>
                    <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                            <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="picture" datatype="diy6_picture3" dataname="图片菜单3"  title="显示" checked></div>
                        </div></td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">图片菜单4</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy6_picture4.jpg" class="usemenu btn-move" data-type="picture_menu4"/></td>
                    <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                            <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="picture" datatype="diy6_picture4" dataname="图片菜单4"  title="显示" checked></div>
                        </div></td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">图片菜单5</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy6_picture21.jpg" class="usemenu btn-move" data-type="picture_menu5"/></td>
                    <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                            <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="picture2" datatype="diy6_picture21" dataname="图片菜单5"  title="显示" checked></div>
                        </div></td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">图片菜单6</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy6_picture22.jpg" class="usemenu btn-move" data-type="picture_menu6"/></td>
                    <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                            <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="picture2" datatype="diy6_picture22" dataname="图片菜单6"  title="显示" checked></div>
                        </div></td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">图片菜单7</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy6_picture23.jpg" class="usemenu btn-move" data-type="picture_menu7"/></td>
                    <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                            <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="picture2" datatype="diy6_picture23" dataname="图片菜单7"  title="显示" checked></div>
                        </div></td>
                </tr>
                <tr class="cube-item">
                        <td ><span class="itemtitle">图片菜单8</span></td>
                        <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy6_picture24.jpg" class="usemenu btn-move" data-type="picture_menu8"/></td>
                        <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                                <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="picture2" datatype="diy6_picture24" dataname="图片菜单8"  title="显示" checked></div>
                            </div></td>
                </tr>
                <tr class="cube-item">
                        <td ><span class="itemtitle">今日上新</span></td>
                        <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy7_goods_list1.jpg" class="usemenu btn-move" data-type=""/></td>
                        <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                                <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="good_list" datatype="diy7_goods_list1" dataname="今日上新"  title="显示" checked></div>
                            </div></td>
                    </tr>
                    <tr class="cube-item">
                            <td ><span class="itemtitle">今日上新2</span></td>
                           <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy7_goods_list4.jpg" class="usemenu btn-move" data-type=""/></td>
                            <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                                <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="good_list" datatype="diy7_goods_list4" dataname="今日上新2"  title="显示" checked></div>
                                </div></td>
                    </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">实时榜单</span></td>
                   <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy7_goods_list3.jpg" class="usemenu btn-move" data-type=""/></td>
                    <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                        <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="good_list" datatype="diy7_goods_list3" dataname="实时榜单"  title="显示" checked></div>
                        </div></td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">实时榜单2</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy7_goods_list2.jpg" class="usemenu btn-move" data-type=""/></td>
                    <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                            <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum=""  tag="good_list" datatype="diy7_goods_list2" dataname="商品列表(1)"  title="显示" checked></div>
                        </div></td>
                </tr>
                <tr class="cube-item">
                        <td ><span class="itemtitle">商品列表1</span></td>
                        <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy7_goods_list5.jpg" class="usemenu btn-move" data-type=""/></td>
                        <td class="td-manage" style="vertical-align: text-top;"><div class="layui-form-item">
                                <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum="1"  tag="good_list" datatype="diy7_goods_list5" dataname="商品列表(1)"  title="显示" checked></div>
                            </div></td>
                </tr>
                <tr class="cube-item">
                        <td ><span class="itemtitle">商品列表2</span></td>
                        <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy7_goods_list6.jpg" class="btn-move" data-type=""/></td>
                        <td class="td-manage" style="vertical-align: text-top;">
                            <div class="layui-form-item">
                                <div class="layui-input-block"><input type="checkbox" name="itemjson" selectnum="1" tag="good_list" datatype="diy7_goods_list6" dataname="商品列表(2)"  title="显示"></div>
                            </div>
                        </td>
                </tr>
                <tr class="cube-item">
                        <td ><span class="itemtitle">商品列表3</span></td>
                        <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy7_goods_list7.jpg" class="btn-move" data-type=""/></td>
                        <td class="td-manage" style="vertical-align: text-top;">
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <input type="checkbox" name="itemjson" selectnum="1" tag="good_list" datatype="diy7_goods_list7" dataname="商品列表(4)"  title="显示"></div>
                            </div>
                        </td>
                </tr>
                <tr class="cube-item">
                    <td ><span class="itemtitle">底部菜单</span></td>
                    <td><img src="<?php echo MODULE_URL;?>skin/diypage/diy_bottom.jpg" class="" datatype="" dataname="底部菜单"/></td>
                    <td class="td-manage"></td>
                </tr>
                </tbody>
            </table>
        </div>
            <div class="layui-tab layui-tab-card" id="setForm" style="float:left;width:45%;display:none;">
                <ul class="layui-tab-title">
                  <li class="layui-this">选项设置</li>
                  
                </ul>
                <div class="layui-tab-content" style="height: auto;">

                    <form action="" method="post" id="saveform"  enctype="multipart/form-data" class="layui-form">
                        <div class="layui-tab-item layui-show">
                            <div class="layui-form-item">
                                 <label class="layui-form-label">标题</label>
                                 <div class="layui-input-inline">
                                   <input type="text" name="itemtitle" lay-verify="" placeholder="请输入标题" autocomplete="off" class="layui-input">
                                 </div>
                                 <div class="layui-form-mid layui-word-aux">自定义标题，用于页面上展示，非必填</div>
                            </div>
                            <div class="layui-form-item">
                                    <label class="layui-form-label">描述</label>
                                    <div class="layui-input-inline">
                                      <input type="text" name="itemremark" lay-verify="" placeholder="请输入描述" autocomplete="off" class="layui-input">
                                    </div>
                                    <div class="layui-form-mid layui-word-aux">自定义描述，用于页面上展示，非必填</div>
                            </div>
                        </div>
                        <button  type="submit" class="layui-btn" lay-filter="add1" lay-submit="">
                            保存
                        </button>
                        <a class="layui-btn layui-btn-normal" lay-filter="additem" onclick="additem()">
                            添加
                        </a>
                        <div class="layui-form" action="" id="headmenu_view">
                                    
                        </div>
                        <br/>
                        <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                            保存
                        </button>
                        <a class="layui-btn layui-btn-normal" lay-filter="additem" onclick="additem()">
                                添加
                        </a>
                    </form>
                </div>
              </div>  
                
        </div>
    </body>
</html>

<script>var require = { urlArgs: 'v=20170915' };</script>
<script type="text/javascript" src="./resource/js/lib/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="./resource/js/lib/bootstrap.min.js"></script>
<script type="text/javascript" src="./resource/js/app/util.js?v=20170719"></script>
<script type="text/javascript" src="./resource/js/app/common.min.js?v=20170719"></script>
<script type="text/javascript" src="./resource/js/require.js?v=20170719"></script>
<script type="text/javascript" src="<?php echo NETS_HAOJIK_WEB_STYLE;?>js/jquery.nestable.js"></script>
<script language='javascript'>
    var recordjson=<?php  echo $jsonrecord;?>;
    var diypage_html="<?php  echo $global['homepage_itemhtml'];?>"
    var menutype="head_menu";
    var recordlist=recordjson;
    var emptyjson='{"ad_menu_name": "","itemtitle": "","itemremark": "","sort": 0,"list": [{"img": "","name": "","outer_url": "","url": ""}]}';
    var urltemparr=[];
    var formsubmiturl="<?php  echo webUrl('sysset/diypage/save',array('cloum'=>'PcloumP'));?>";
    $(function () {
        if(diypage_html!=""){
            //$("#tbody").html(diypage_html);
        }
        
    });
    $(document).ready(function(){
        
        getPageitem();
    });
    function bindEvents() {
        require(['jquery.ui'] ,function(){
            $("#tbody").sortable({handle: '.btn-move',axis: 'y'});
        });
        require(['jquery', 'util'], function ($, util) {
            $('.btn-select-pic').unbind('click').click(function () {
                var imgitem = $(this).closest('.img-item');
                util.image('', function (data) {
                    imgitem.find('img').attr('src', data['url']);
                    imgitem.find('input').val(data['attachment']);
                });
            });
        });
        inititemclick();
    }
    function additem(){
        layui.use('laytpl', function(){
            var laytpl = layui.laytpl;
        var outhtml=$("#headmenu_view div.layui-form-item:eq(0)").prop("outerHTML");
        console.log(outhtml);
        if(outhtml=="" || outhtml==undefined){
            var res=emptyjson;
            var obj=JSON.parse(res);
            console.log(res);
            var list=obj.list;
            console.log(list);
            if(typeof obj.itemtitle!=undefined){
                $("input[name='itemtitle']").val(obj.itemtitle);
            }
            if(typeof obj.itemremark!=undefined){
                $("input[name='itemremark']").val(obj.itemremark);
            }
            var getTpl = $("#headmenu").html()
            ,view =$('#headmenu_view');
            laytpl(getTpl).render(list, function(html){
                outhtml=html;
            }); 
        }
        $("#headmenu_view").append(outhtml);
        bindEvents();
        });
    }
    function removeItem(obj){
        $(obj).parent().parent().remove();
    }
    function getX(e) {
        e = e || window.event;
        return e.pageX || e.clientX + document.body.scroolLeft;
    }
    function getY(e) {
        e = e|| window.event;
        return e.pageY || e.clientY + document.boyd.scrollTop;
    }
    function HTMLEncode(html) 
    { 
    var temp = document.createElement ("div"); 
    (temp.textContent != null) ? (temp.textContent = html) : (temp.innerText = html); 
    var output = temp.innerHTML; 
    temp = null; 
    return output; 
    } 
    function HTMLDecode(text) 
    { 
    var temp = document.createElement("div"); 
    temp.innerHTML = text; 
    var output = temp.innerText || temp.textContent; 
    temp = null; 
    return output; 
    } 
    function savePageitem(){
        var itemArr=new Array();
        $("input[name='itemjson']").each(function(){
            var item=$(this);
            var itemobj=new Object();
            itemobj.datatype=item.attr("datatype");
            itemobj.dataname=item.attr("name");
            itemobj.sort=0;
            var isshow=item.parent().find(".layui-form-checked").eq(0).hasClass("layui-form-checked");
            console.log(isshow);
            itemobj.isshow=isshow;
            
            itemArr.push(itemobj);
        });
        console.log(itemArr);
        
        var loadii = layer.load();
        
        var pageitem=new Object();
        pageitem.title=$("#pagetitle").val();
        pageitem.pageitemjson=itemArr;
        var pagehtml=new Object();
        pagehtml.value=$("#tbody").html()
        pageitem.pageitemhtml=pagehtml;
        $.post("<?php  echo webUrl('sysset/diypage/savepageitem');?>",pageitem,function(res){
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
        
        console.log(itemArr);
    }
    function getPageitem(){
        var itemArr=new Array();
        $.post("<?php  echo webUrl('sysset/diypage/getpageitem');?>",function(res){
                if(res=="" || res==null){
                    bindEvents();
                    return;
                }
                var res=JSON.parse(res);
                $("#pagetitle").val(res.title);
                var htmlitem=res.itemhtml;
                var html=JSON.parse(htmlitem).value;
                html=HTMLDecode(html);
                $("#tbody").html(html);
                bindEvents();
                res=JSON.parse(res.itemjson);
                console.log(res);
                for(var i=0;i<res.length;i++){
                    if(res[i].isshow=="false"){
                        $("input[name='itemjson']").each(function(){
                            var item=$(this);
                            if(res[i].datatype==item.attr("datatype")){
                                console.log(res[i].datatype+"_"+item.attr("datatype"));
                                item.removeAttr("checked");
                                item.parent().find(".layui-form-checked").eq(0).removeClass("layui-form-checked");
                            }
                        });    
                    }
                }
        });
        console.log(itemArr);
    }
    
    layui.use(['form','layer'], function(){
            $ = layui.jquery;
            var form = layui.form
                ,layer = layui.layer;
            //监听提交
            form.on('submit(add1)', function(data){
                var action_formsubmiturl=formsubmiturl.replace("PcloumP",menutype);
                $("#saveform").attr("action",action_formsubmiturl);
                $("#saveform").submit();
                return false;
            });
            form.on('submit(add)', function(data){
                var action_formsubmiturl=formsubmiturl.replace("PcloumP",menutype);
                $("#saveform").attr("action",action_formsubmiturl);
                $("#saveform").submit();
                return false;
            });
        });
    
</script>

<script>
    function  inititemclick(){
        
        layui.use('laytpl', function(){
            var laytpl = layui.laytpl;
            console.log("初始化事件");
            $(".layui-form-checkbox").unbind("click").click(function(){
                console.log(123);
                
                var item=$(this).parent().find("input").eq(0);
                console.log(item);
                var currenttag=item.attr("tag");
                var currentselectnum=item.attr("selectnum");
                var isshow=item.attr("checked");
                console.log("当前选择标签："+currenttag+";可选择数："+currentselectnum+";isshow="+isshow);
                var iscansel=true;
                if(isshow==undefined){
                    console.log("显示");
                    $(".layui-form-checkbox").each(function(){
                        var item1=$(this).parent().find("input").eq(0);
                        var tag=item1.attr("tag");
                        var selectnum=item1.attr("selectnum");
                        //console.log(item1);
                        var isshow1=item1.attr("checked");
                        console.log(isshow1+"_"+selectnum+"_"+tag);
                        if(currenttag==tag && currentselectnum==selectnum && currentselectnum=="1" && isshow1=="checked"){
                            console.log("只能选择同类型的一个");
                            iscansel=false;
                        }
                    });
                    if(iscansel){
                        $(this).parent().parent().parent().parent().find("img").show();
                        //item.removeAttr("checked");
                        item.attr("checked",true);
                    }
                }else{
                    console.log("隐藏");
                    $(this).parent().parent().parent().parent().find("img").hide();
                    item.attr("checked",false);
                }
                if(iscansel){
                    $(this).toggleClass("layui-form-checked");
                }else{
                    layer.open({
                        type: 1
                        ,offset: "auto" 
                        ,id: 'layerMsg' 
                        ,content: '<div style="padding: 20px 100px;">该类型只能选择一个显示</div>'
                        ,btn: '关闭'
                        ,btnAlign: 'c' //按钮居中
                        ,shade: 0 //不显示遮罩
                        ,yes: function(){
                        layer.closeAll();
                        }
                    });
                }
            });
            $(".usemenu").unbind("click").click(function(){
                //console.log(getY());
                menutype=$(this).attr("data-type");
                
                if(menutype==""){
                    $("#setForm").hide();
                    return;
                }
                $("#setForm").show();
                var yheight=getY()-250;
                yheight=$(this).offset().top-140;
                $("#setForm").css("margin-top",yheight);
                if($(this).attr("data-type")!=""){
                    
                    $.post("<?php  echo webUrl('sysset/diypage/getglobal');?>&cloum="+menutype,function(res){
                        
                        if(res==null || res=="" || typeof(res)=='undefined' || res=="null"){
                            res=emptyjson;
                        }
                        
                        var obj=JSON.parse(res);
                        var menu_tpl_temp=obj.menu_tpl;
                        if(obj.list==null || obj.list=="" || obj.list=="undefined"){
                            res=emptyjson;
                            obj=JSON.parse(res);
                            obj.menu_tpl=menu_tpl_temp;
                        }
                        console.log(res);
                        var list=obj.list;
                        console.log(list);
                        if(typeof obj.itemtitle!=undefined){
                            $("input[name='itemtitle']").val(obj.itemtitle);
                        }
                        if(typeof obj.itemremark!=undefined){
                            $("input[name='itemremark']").val(obj.itemremark);
                        }
                        var getTpl = $("#headmenu").html()
                        ,view =$('#headmenu_view');
                        laytpl(getTpl).render(list, function(html){
                            view.html(html);
                            bindEvents();
                        });    
                    });
                }
            });
        });
    }

    
    </script>

<script id="headmenu" type="text/html">
    
    {{#  layui.each(d, function(index, item){ }}
    {{# var itemurl=item.url;}}
    <div class="layui-form-item">
            <div class="layui-inline">
                  <label class="layui-form-label img-item">
                      <img class="layui-upload-img" src="{{item.img}}" width="80"><br/>
                      <input type="hidden" class="form-control" name="{{menutype}}_img[]" value="{{item.img}}" />
                      <button type="button" class="btn btn-default btn-select-pic">选择图片</button>
                  </label>
            </div>
            <div class="layui-inline">
              {{# 
                if(typeof(item.name)=='undefined' || item.name==""){
                    item.name="";
                }
               }}
              <label class="layui-form-label label40">名称</label>
              <div class="layui-input-inline">
                <input type="tel" name="{{menutype}}_name[]" value="{{item.name}}" autocomplete="off" class="layui-input">
              </div>
              <br/><br/><br/>
              
              <label class="layui-form-label label40">链接<?php  echo $_W['global']['jdmodule_status'];?></label>
              <div class="layui-input-inline">
                    <select type="text"   name="{{menutype}}_url[]" class="form-control valid"  placeholder="">
                            <?php  if(($_W["wxappauth"]=="1" || $_W["wxgzhauth"]=="1") && $_W['global']['jdmodule_status']=='1') { ?>
                            <optgroup label="京东页面">
                                    <option {{#  if(item.url ===  '../choiceness/index?name=index'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=index">首页</option>
                                    <option {{#  if(item.url === '../choiceness/index?name=choiceness'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=choiceness">好券精选</option>
                                    <option {{#  if(item.url === '../choiceness/index?name=bigsearch'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=bigsearch">超级搜索</option>
                                    <option {{#  if(item.url === '../choiceness/index?name=my'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=my">我的</option>
                                    <option {{#  if(item.url === '../choiceness/index?name=好货情报局'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=好货情报局">好货情报局</option>
                                    <option {{#  if(item.url === '../choiceness/index?name=砍价'){ }}selected="selected"{{#  } }}  value="../choiceness/index?name=砍价">砍价</option>
                                    <option {{#  if(item.url ===  '../choiceness/index?name=拼购'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=拼购">拼购</option>
                                    <option {{#  if(item.url ===  '../choiceness/index?name=榜单'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=榜单">榜单</option>
                                    <option {{#  if(item.url ===  '../choiceness/index?name=2小时跑单'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=2小时跑单">2小时跑单</option>
                                    <option {{#  if(item.url ===  '../choiceness/index?name=全天销售榜'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=全天销售榜">全天销售榜</option>
                                    <option {{#  if(item.url ===  '../choiceness/index?name=实时排行榜'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=实时排行榜">实时排行榜</option>
                            </optgroup>
                            <?php  } ?>
                            <?php  if($_W["wxpddauth"]=="1" && $_W['global']['pddmodule_status']=='1') { ?>
                            <optgroup label="拼多多页面">
                                <option {{#  if(item.url ===  '../choiceness/index?name=pddindex'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=pddindex">首页</option>
                                <option {{#  if(item.url === '../choiceness/index?name=bigsearch'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=bigsearch">超级搜索</option>
                                
                                <option {{#  if(item.url === '../choiceness/index?name=pddsearch'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=pddsearch">列表页</option>
                            </optgroup>
                            <?php  } ?>
                        <?php  if($_W['acctype']=="小程序") { ?>
                        <optgroup label="互动营销">
                            <option {{#  if(item.url ===  '../choiceness/index?name=Prizedraw'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=Prizedraw">助力抽奖</option>
                        </optgroup>
                        <?php  } ?>
                            {{# if(menutype!="tab_menu"){ }}
                            <optgroup label="自定义商品源">
                                    {{#  layui.each(recordlist, function(index, r){ }}
                                    {{# 
                                        var isselected=false;
                                        if(r.type==1){
                                            if(item.url.split("=").length==4){
                                                item.url=item.url.split("=")[0]+"="+item.url.split("=")[1]+"=1="+r.id;
                                            }
                                            if(item.url==r.url || item.url==r.url+"=1="+r.id){
                                                isselected=true;
                                            }
                                        }
                                    }}
                                    {{# if(isselected && r.type==1){ }}
                                    <option  value="{{r.url}}=1={{r.id}}" selected="selected">{{r.name}}</option>
                                    {{# } }}
                                    {{# if(!isselected && r.type==1){}}
                                    <option  value="{{r.url}}=1={{r.id}}">{{r.name}}</option>
                                    {{# } }}
                                    {{#  }); }}
                                
                            </optgroup>
                            <optgroup label="商品分类">
                                
                                    {{#  layui.each(recordlist, function(index, r){ }}
                                    {{# 
                                        var isselected=false;
                                        if(r.type==2){
                                            if(item.url.split("=").length==4){
                                                item.url=item.url.split("=")[0]+"="+item.url.split("=")[1]+"=2="+r.id;
                                            }
                                            if(item.url==r.url || item.url==r.url+"=2="+r.id){
                                                isselected=true;
                                            }
                                        }
                                    }}
                                    {{# if(isselected && r.type==2){ }}
                                    <option  value="{{r.url}}=2={{r.id}}" selected="selected">{{r.name}}</option>
                                    {{# } }}
                                    {{# if(!isselected && r.type==2){}}
                                    <option  value="{{r.url}}=2={{r.id}}">{{r.name}}</option>
                                    {{# } }}
                                    {{#  }); }}
                            </optgroup>
                            <optgroup label="自选商品源">
                                    {{#  layui.each(recordlist, function(index, r){ }}
                                    {{# 
                                        var isselected=false;
                                        if(r.type==3){
                                            if(item.url.split("=").length==4){
                                                item.url=item.url.split("=")[0]+"="+item.url.split("=")[1]+"=3="+r.id;
                                            }
                                            if(item.url==r.url || item.url==r.url+"=3="+r.id){
                                                isselected=true;
                                            }
                                        }
                                    }}
                                    {{# if(isselected ){ }}
                                    <option  value="{{r.url}}=3={{r.id}}" selected="selected">{{r.name}}</option>
                                    {{# } }}
                                    {{# if(!isselected && r.type==3){}}
                                    <option  value="{{r.url}}=3={{r.id}}">{{r.name}}</option>
                                    {{# } }}
                                    {{#  }); }}
                            </optgroup>
                            {{# } }}
                    </select>
              </div>
              <br/><br/><br/>
              <label class="layui-form-label label40">外链</label>
              <div class="layui-input-inline">
                <input type="text" name="{{menutype}}_outer_url[]" value="{{item.outer_url}}" autocomplete="off" class="layui-input">
              </div>
              <a class="layui-btn layui-btn-danger height28" onclick="removeItem(this)"><i class="layui-icon"></i></a>
            </div>
          </div>
          {{#  }); }}
  </script>

  