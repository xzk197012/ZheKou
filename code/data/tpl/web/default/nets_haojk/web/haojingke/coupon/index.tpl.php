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
            <input type="hidden" name="r" value="coupon.index">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="c" value="site">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label ">券后价</label>
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="minprice" id="minprice" type="text" value="<?php  echo $_GPC['minprice'];?>" placeholder="￥" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="maxprice" id="maxprice" type="text" value="<?php  echo $_GPC['maxprice'];?>" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">佣金比例</label>
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="mincommission" id="mincommission" type="text" value="<?php  echo $_GPC['mincommission'];?>"  placeholder="%" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="maxcommission" id="maxcommission" type="text" value="<?php  echo $_GPC['maxcommission'];?>" placeholder="%" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">佣金金额</label>
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="mincommissionpirce" id="mincommissionpirce" type="text" value="<?php  echo $_GPC['mincommissionpirce'];?>"  placeholder="￥" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline layui-input-inline-w100">
                        <input name="maxcommissionpirce" id="maxcommissionpirce" type="text" value="<?php  echo $_GPC['maxcommissionpirce'];?>" placeholder="￥" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">分类</label>
                    <div class="layui-input-inline">
                        <select  name="cname" id="cname">
                            <option value ="" <?php  if($_GPC['cname']=='') { ?>selected="selected"<?php  } ?>>全部</option>
                            <?php  if(is_array($cate)) { foreach($cate as $c) { ?>
                            <option value ="<?php  echo $c['name'];?>" <?php  if($_GPC['cname']==$c["name"]) { ?>selected="selected"<?php  } ?>><?php  echo $c["name"];?></option>
                            <?php  } } ?>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <select   name="sort" id="sort">
                            <option value ="0" <?php  if($_GPC['sort']=='0') { ?>selected="selected"<?php  } ?>>默认排序</option>
                            <option value ="1" <?php  if($_GPC['sort']=='1') { ?>selected="selected"<?php  } ?>>券后价</option>
                            <option value ="2" <?php  if($_GPC['sort']=='2') { ?>selected="selected"<?php  } ?>>券金额</option>
                            <option value ="3" <?php  if($_GPC['sort']=='3') { ?>selected="selected"<?php  } ?>>佣金比例</option>
                            <option value ="99" <?php  if($_GPC['sort']=='4') { ?>selected="selected"<?php  } ?>>佣金金额</option>
                            <option value ="98" <?php  if($_GPC['sort']=='6') { ?>selected="selected"<?php  } ?>>2小时销量</option>
                            <option value ="98" <?php  if($_GPC['sort']=='7') { ?>selected="selected"<?php  } ?>>当天销量</option>
                            <option value ="98" <?php  if($_GPC['sort']=='8') { ?>selected="selected"<?php  } ?>>人气榜</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">关键字</label>
                    <div class="layui-input-inline">
                        <input type="text" value="<?php  echo $_GPC['keyword'];?>"  name="keyword"  placeholder="商品名称、商品ID" autocomplete="off" class="layui-input">
                    </div>
                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                </div>
        </div>
        </form>
    </div>
    <table class="layui-table">
        <thead>
        <tr>
            <th>图片</th>
            <th class="w400">商品名称</th>
            <th>优惠券</th>
            <th>价格</th>
            <th >券后价</th>
            <th >佣金</th>
            <th >额外服务费/限量</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $r) { ?>
        <tr>
            <td class="postTd" >
                <div class="goodjson" style="display:none;"><?php  echo json_encode($r);?></div>
                <img src="<?php  echo $r['picUrl'];?>" width="48" height="48" />
            </td>
            <td>
                <?php  echo $r['skuId'];?><br>
                <?php  echo $r['skuName'];?>
            </td>
            <td>
                ￥<?php  echo $r['discount'];?>
            </td>
            <td>
                ￥<?php  echo $r['wlPrice'];?>
            </td>
            <td>
                ￥<?php  echo $r['wlPrice_after'];?>
            </td>
            <td>
                <?php  echo $r['wlCommissionShare'];?>%/￥<?php  echo number_format($r['wlCommissionShare']/100*$r['wlPrice_after'],2); ?>
            </td>
            <td>
                ￥<?php  echo $r['spread_amount'];?>/<?php  echo $r['spread_count'];?>个<br>
                <?php  echo date("Y-m-d",$r['spread_start_at'])?>-<?php  echo date("Y-m-d",$r['spread_end_at'])?>
            </td>
            <td class="td-manage">
                <a  onclick="hjk_dialog_show('加入商品源',' <?php  echo webUrl('goodssource/sourcegoods_add');?>&skuId=<?php  echo $r['skuId'];?>')"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6b9;</i> 加入商品源</a>
                <a  onclick="freegoods_add(this)"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6b9;</i> 加入免单</a>
                <br><br>
                <a  data-skuid="<?php  echo $r['skuId'];?>"  onclick="hjk_dialog_show('优惠券商品群发添加',' <?php  echo webUrl('sale/groupsend/groupsend_add');?>&skuId=<?php  echo $r['skuId'];?>',600,400)"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6b9;</i> 加入群发</a>

                <!--<a  onclick="groupsend_add(this)"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6b9;</i> 加入群发</a>-->
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

    //免单优惠券商品添加
    function freegoods_add(obj){
        //prompt层
        layer.prompt({title: '请输入砍价次数！', formType: 0}, function(cuttingnum, index){
            if(cuttingnum==''){
                layer.msg("请输入砍价次数");
                return false;
            }
            var loadii = layer.load();
            var id = $(obj).parent('td').siblings(".postTd").children(".goodjson").html();
            data = { "goods": id, "cuttingnum": cuttingnum };
            var ajaxurl="<?php  echo webUrl('sale/freegoods_addpost')?>";
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
            layer.close(loadii);
            layer.close(index);
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