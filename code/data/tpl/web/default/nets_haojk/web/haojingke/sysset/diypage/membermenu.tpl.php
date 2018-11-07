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
            <blockquote class="layui-elem-quote">会员中心自定义菜单</blockquote>
            
            
            <div class="layui-tab layui-tab-card" id="setForm" style="float:left;width:45%;display:none;">
                <ul class="layui-tab-title">
                  <li class="layui-this">会员中心菜单设置</li>
                  
                </ul>
                <div class="layui-tab-content" style="height: auto;">

                    <form action="" method="post" id="saveform"  enctype="multipart/form-data" class="layui-form">
                        <input type="hidden" id="menu_tpl" name="menu_tpl" value="diy_bottom"/>
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
    var menutype="membermenu";
    var recordlist=recordjson;
    
    var emptyjson='{"ad_menu_name": "","itemtitle": "","itemremark": "","sort": 0,"list": [{"img": "","name": "","outer_url": "","url": ""}]}';
    var urltemparr=[];
    var formsubmiturl="<?php  echo webUrl('sysset/diypage/diyfoot_save',array('cloum'=>'membermenu'));?>";
    
    $(document).ready(function(){
        get_tabmenu();
    });
    
    
</script>

<script>
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
    function bindEvents() {
        
        require(['jquery', 'util'], function ($, util) {
            $('.btn-select-pic').unbind('click').click(function () {
                var imgitem = $(this).closest('.img-item');
                util.image('', function (data) {
                    imgitem.find('img').attr('src', data['url']);
                    imgitem.find('input').val(data['attachment']);
                });
            });
        });
    }
    function additem(){
        var outhtml=$("#headmenu_view div.layui-form-item:eq(0)").prop("outerHTML");
        $("#headmenu_view").append(outhtml);
        bindEvents();
    }
    function removeItem(obj){
        $(obj).parent().parent().remove();
    }
    function get_tabmenu(){
        layui.use('laytpl', function(){
            var laytpl = layui.laytpl;
        if(menutype==""){
                    $("#setForm").hide();
                    return;
                }
                $("#setForm").show();
                
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
                        if(obj.menu_tpl=="" || obj.menu_tpl==undefined || obj.menu_tpl==null){
                            obj.menu_tpl="diy_bottom";
                        }
                        console.log(obj);
                        $("#menu_tpl").val(obj.menu_tpl);
                        $(".layui-form-radio").removeClass("layui-form-radioed");

                        $("#"+obj.menu_tpl).addClass("layui-form-radioed");
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
              
              <label class="layui-form-label label40">链接</label>
              <div class="layui-input-inline">
                    <select type="text"   name="{{menutype}}_url[]" class="form-control valid"  placeholder="">
                            <?php  if($_W["wxappauth"]=="1" || $_W["wxgzhauth"]=="1") { ?>
                            <optgroup label="京东页面">
                                    <option {{#  if(item.url ===  '../choiceness/index?name=index'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=index">京东首页</option>
                                    <option {{#  if(item.url === '../choiceness/index?name=choiceness'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=choiceness">好券精选</option>
                                    <option {{#  if(item.url === '../choiceness/index?name=bigsearch'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=bigsearch">超级搜索</option>
                                    <option {{#  if(item.url === '../choiceness/index?name=好货情报局'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=好货情报局">好货情报局</option>
                                    <option {{#  if(item.url === '../choiceness/index?name=砍价'){ }}selected="selected"{{#  } }}  value="../choiceness/index?name=砍价">砍价</option>
                                    <option {{#  if(item.url ===  '../choiceness/index?name=拼购'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=拼购">拼购</option>
                                    <option {{#  if(item.url ===  '../choiceness/index?name=榜单'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=榜单">榜单</option>
                                    <option {{#  if(item.url ===  '../choiceness/index?name=2小时跑单'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=2小时跑单">2小时跑单</option>
                                    <option {{#  if(item.url ===  '../choiceness/index?name=全天销售榜'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=全天销售榜">全天销售榜</option>
                                    <option {{#  if(item.url ===  '../choiceness/index?name=实时排行榜'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=实时排行榜">实时排行榜</option>
                            </optgroup>
                            <?php  } ?>
                            <?php  if($_W["wxpddauth"]=="1") { ?>
                            <optgroup label="拼多多页面">
                                <option {{#  if(item.url ===  '../choiceness/index?name=pddindex'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=pddindex">拼多多首页</option>
                                <option {{#  if(item.url === '../choiceness/index?name=pddcatelist'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=pddcatelist">多券精选页</option>
                                <option {{#  if(item.url === '../choiceness/index?name=pddsearch'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=pddsearch">搜索列表页</option>
                            </optgroup>
                            <?php  } ?>
                            <?php  if($_W["wxgwqauth"]=="1") { ?>
                            <optgroup label="购物圈页面">
                                <option {{#  if(item.url ===  '../choiceness/index?name=gwqindex'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=gwqindex">购物圈首页</option>
                                <option {{#  if(item.url === '../choiceness/index?name=gwqtag'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=gwqsearch">购物圈搜索</option>
                                <option {{#  if(item.url === '../choiceness/index?name=gwqfriend'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=gwqfriend">购物圈好友</option>
                                <option {{#  if(item.url == "../choiceness/index?name=gwqshare"){}}selected="selected" {{#  } }}  value="../choiceness/index?name=gwqshare">晒入口</option>
                            </optgroup>
                            <?php  } ?>
                            <optgroup label="会员中心">
                                <option {{#  if(item.url === '../choiceness/index?name=my'){ }}selected="selected"{{#  } }} value="../choiceness/index?name=my">我的</option>
                            </optgroup>
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

  