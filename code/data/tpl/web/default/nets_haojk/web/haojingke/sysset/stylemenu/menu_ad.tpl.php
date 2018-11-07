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

<body>
<div class="x-body">

    <form action="" method="post"  enctype="multipart/form-data"  >
        <xblock>
            <button type="button" class="layui-btn" onclick="addCube()"><i class="layui-icon"></i>添加</button>
            <input type="hidden" name="ad_menu_name" value="广告图片设置"/>
            <input type="hidden" name="op" value="menu_ad"/>
            <input type="submit" name="submit" value="提交" class="layui-btn " />
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
        </xblock>
        <table class="layui-table">
            <thead>
            <tr>
                <th></th>
                <th>图片</th>
                <th><?php  if($_W['acctype']=='小程序') { ?>目标小程序appid(跳转到其他小程序)<?php  } else { ?>外部链接<?php  } ?></th>
                <th>链接</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody id="tbody">
            <?php  if(is_array($re_ad_menu)) { foreach($re_ad_menu as $data) { ?>
            <tr class="cube-item">
                <td >
                    <a href="javascript:;" class="layui-btn layui-btn-primary btn-move"><i class="iconfont">&#xe6fd;</i></a>
                </td>
                <td>
                    <div class="input-group img-item">
                        <div class="input-group-addon">
                            <img src="<?php  echo tomedia($data['img'])?>" style="height:20px;width:20px" />
                        </div>
                        <input type="text" class="form-control" name="ad_menu_img[]" value="<?php  echo $data['img'];?>" />

                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default btn-select-pic">选择图片</button>
                        </div>

                    </div>
                </td>
                <td>
                    <div class="input-group form-group" style="margin: 0;">

                        <input type="text" value="<?php  echo $data['outer_url'];?>" class="form-control valid" name="ad_outer_url[]" placeholder="请输入">
                    </div>
                </td>
                <td>
                    <div class="input-group form-group" style="margin: 0;">
                        <select type="text"   name="ad_menu_url[]" class="form-control valid"  placeholder="" style="width: 300px;">
                                <?php  if($_W["wxappauth"]=="1" || $_W["wxgzhauth"]=="1") { ?>    
                                <optgroup label="京东页面">
                                    <option <?php if($data['url'] == '../choiceness/index?name=index') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=index">首页</option>
                                    <option <?php if($data['url'] == '../choiceness/index?name=choiceness') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=choiceness">好券精选</option>
                                    <option <?php if($data['url'] == '../choiceness/index?name=bigsearch') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=bigsearch">超级搜索</option>
                                    <option <?php if($data['url'] == '../choiceness/index?name=my') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=my">我的</option>
                                    <option <?php if($data['url'] == '../choiceness/index?name=好货情报局') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=好货情报局">好货情报局</option>
                                    <option <?php if($data['url'] == '../choiceness/index?name=砍价') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=砍价">砍价</option>
                                    <option <?php if($data['url'] == '../choiceness/index?name=拼购') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=拼购">拼购</option>
                                    <option <?php if($data['url'] == '../choiceness/index?name=榜单') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=榜单">榜单</option>
                                    <option <?php if($data['url'] == '../choiceness/index?name=2小时跑单') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=2小时跑单">2小时跑单</option>
                                    <option <?php if($data['url'] == '../choiceness/index?name=全天销售榜') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=全天销售榜">全天销售榜</option>
                                    <option <?php if($data['url'] == '../choiceness/index?name=实时排行榜') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=实时排行榜">实时排行榜</option>
                                </optgroup>
                            <?php  } ?>
                            <?php  if($_W["wxpddauth"]=="1") { ?>
                            <optgroup label="拼多多页面">
                                <option <?php if($data['url'] ==  '../choiceness/index?name=pddindex') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=pddindex">首页</option>
                                <option <?php if($data['url'] == '../choiceness/index?name=pddsearch') { ?>selected="selected"<?php  } ?> value="../choiceness/index?name=pddsearch">列表页</option>
                            </optgroup>
                            <?php  } ?>
                                <optgroup label="自定义商品源">
                                    <?php  if(is_array($record)) { foreach($record as $i => $url) { ?>
                                    <?php  if($url['type']==1) { ?>
                                    <?php  
                                $urltemparr=explode('=',$data['url']);
                                    if(count($urltemparr)==4){
                                    $data['url']=$urltemparr[0]."=".$urltemparr[1]."=1=".$url['id'];
                                } ?>
                                    <?php  if($data['url'] == $url['url'] || $data['url'] == $url['url'].'=1='.$url['id']) { ?> 
                                    <option  value="<?php  echo $url['url'];?>=1=<?php  echo $url['id'];?>" selected="selected"><?php  echo $url['name'];?></option> 
                                    <?php  } else { ?>
                                    <option  value="<?php  echo $url['url'];?>=1=<?php  echo $url['id'];?>"><?php  echo $url['name'];?></option>
                                    <?php  } ?>
                                    <?php  } ?>
                                    <?php  } } ?>
                                </optgroup>
                                <optgroup label="商品分类">
                                    <?php  if(is_array($record)) { foreach($record as $i => $url) { ?>
                                    <?php  if($url['type']==2) { ?>
                                    <?php  
                                $urltemparr=explode('=',$data['url']);
                                    if(count($urltemparr)==4){
                                    $data['url']=$urltemparr[0]."=".$urltemparr[1]."=2=".$url['id'];
                                } ?>
                                    <?php  if($data['url'] == $url['url'] || $data['url'] == $url['url'].'=2='.$url['id']) { ?> 
                                    <option  value="<?php  echo $url['url'];?>=2=<?php  echo $url['id'];?>" selected="selected"><?php  echo $url['name'];?></option> 
                                    <?php  } else { ?>
                                    <option  value="<?php  echo $url['url'];?>=2=<?php  echo $url['id'];?>"><?php  echo $url['name'];?></option>
                                    <?php  } ?>
                                    <?php  } ?>
                                    <?php  } } ?>
                                </optgroup>
                                <optgroup label="自选商品源">
                                    <?php  if(is_array($record)) { foreach($record as $i => $url) { ?>
                                    <?php  if($url['type']==3) { ?>
                                    <?php  
                                $urltemparr=explode('=',$data['url']);
                                    if(count($urltemparr)==4){
                                    $data['url']=$urltemparr[0]."=".$urltemparr[1]."=3=".$url['id'];
                                } ?>
                                    <?php  if($data['url'] == $url['url'] || $data['url'] == $url['url'].'=3='.$url['id']) { ?> 
                                    <option  value="<?php  echo $url['url'];?>=3=<?php  echo $url['id'];?>" selected="selected"><?php  echo $url['name'];?></option> 
                                    <?php  } else { ?>
                                    <option  value="<?php  echo $url['url'];?>=3=<?php  echo $url['id'];?>"><?php  echo $url['name'];?></option>
                                    <?php  } ?>
                                    <?php  } ?>
                                    <?php  } } ?>
                                </optgroup>
                        </select>
                    </div>
                </td>
                <td class="td-manage">
                    <a title="停用" class="layui-btn layui-btn-danger "  onclick="removeCube(this)" href="javascript:;">
                        <i class="layui-icon">&#xe640;</i>移除
                    </a>

                    <!--<a class="btn btn-default  btn-sm" target="_self" href="../web/index.php?c=site&a=entry&op=memberlevel_add&do=memberlevel&m=nets_haojk&id=<?php  echo $r['id'];?>">编辑</a>-->
                    <!--<a onclick="return confirm('确定要删除吗？删除后将无法恢复！');" href="<?php  echo url('site/entry',array('m'=>'nets_haojk','do'=>'memberlevel','op'=>'del','id'=>$r['id']))?>" class="btn btn-primary btn-sm" title="" data-original-title="点此删除">删除</a>-->
                </td>
            </tr>
            <?php  } } ?>
            </tbody>
        </table>
    </form>

    <blockquote class="layui-elem-quote">ps : 
            <?php  if($_W['acctype']=='小程序') { ?>目标小程序appid设置后优先跳转目标小程序（注：必须是同一公众号下，而非同个 open 账号下）<?php  } else { ?>外部链接设置后优先使用外部链接<?php  } ?>
        </blockquote>
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



    $(function () {
        bindEvents();
        $('#div_nestable').nestable({maxDepth: 1});
        $('.dd-item').addClass('full');
        $(".dd-handle a,.dd-handle div").mousedown(function (e) {
            e.stopPropagation();
        });
        $("#save_sort").click(function () {
            var json = window.JSON.stringify($('#div_nestable').nestable("serialize"));
            $(':input[name=datas]').val(json);
        })
    })
    function removeCube(obj){
        $(obj).closest('.cube-item').remove();
    }
    function addCube(){
        if($('.cube-item').length>=6){

            layer.msg("最多只能添加6个！");
            return;

        }
        var timestamp=new Date().getTime();
        var html='<tr class="cube-item"><td ><a href="javascript:;" class="layui-btn layui-btn-primary btn-move"><i class="iconfont">&#xe6fd;</i></a></td><td>';
        html+='<div class="input-group img-item">';
        html+='<div class="input-group-addon">';
        html+='<img src="" style="height:20px;width:20px" />';
        html+='</div>';
        html+='<input type="text" class="form-control" name="ad_menu_img[]" />';
        html+='<div class="input-group-btn">';
        html+='<button type="button" class="btn btn-default btn-select-pic">选择图片</button>';
        html+='</div>';
        html+='</div>';
        html+='</td> <td><div class="input-group form-group" style="margin: 0;"><input type="text" value="" class="form-control valid" name="banner_outer_url[]" placeholder="请输入"></div>';
        html+='</td><td><div class="input-group form-group" style="margin: 0;">';
        html+='<select type="text"  name="ad_menu_url[]" class="form-control valid" style="width:300px;"  id="cubelink_'+timestamp+'">';
        html+='<optgroup label="跳转页面"><option  value="../choiceness/index?name=好货情报局">好货情报局</option><option  value="../choiceness/index?name=砍价">砍价</option><option  value="../choiceness/index?name=拼购">拼购</option><option value="../choiceness/index?name=榜单">榜单</option><option value="../choiceness/index?name=2小时跑单">2小时跑单</option><option value="../choiceness/index?name=全天销售榜">全天销售榜</option><option value="../choiceness/index?name=实时排行榜">实时排行榜</option></optgroup>';
        html+='<optgroup label="自定义商品源"><?php  if(is_array($record)) { foreach($record as $i => $url) { ?><?php  if($url["type"]==1) { ?> <option  value="<?php  echo $url["url"];?>=1=<?php  echo $url["id"];?>" ><?php  echo $url["name"];?></option><?php  } ?><?php  } } ?></optgroup>';
        html+='<optgroup label="商品分类"><?php  if(is_array($record)) { foreach($record as $i => $url) { ?><?php  if($url["type"]==2) { ?> <option  value="<?php  echo $url["url"];?>=2=<?php  echo $url["id"];?>" ><?php  echo $url["name"];?></option><?php  } ?><?php  } } ?></optgroup>';
        html+='<optgroup label="自选商品源"><?php  if(is_array($record)) { foreach($record as $i => $url) { ?><?php  if($url["type"]==3) { ?> <option  value="<?php  echo $url["url"];?>=3=<?php  echo $url["id"];?>" ><?php  echo $url["name"];?></option><?php  } ?><?php  } } ?></optgroup>';
        html+='</select>';
        html+='</div>'
        html+='</td><td><a title="停用" class="layui-btn layui-btn-danger "  onclick="removeCube(this)" href="javascript:;"><i class="layui-icon">&#xe640;</i>移除</a>';

        html+='</td>';
        html+='</tr>';
        $('#tbody').append(html);
        bindEvents();
    }
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
        require(['jquery.ui'] ,function(){
            $("#tbody").sortable({handle: '.btn-move'});
        })
    }

</script>