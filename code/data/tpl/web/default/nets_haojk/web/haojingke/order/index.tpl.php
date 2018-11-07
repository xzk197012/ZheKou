<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<link href="./resource/css/bootstrap.min.css?v=20180110" rel="stylesheet">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">
    <div class="layui-row">
        <form action="" method="get"  enctype="multipart/form-data" class="layui-form layui-form-pane layui-col-md12">
            <input type="hidden" name="m" value="nets_haojk">
            <input type="hidden" name="do" value="web">
            <input type="hidden" name="r" value="order.index">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="c" value="site">
            <input type="hidden" name="search" value="1" />
            <input type="hidden" name="op" value="post" />
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label" style="width:80px;">日期范围</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" placeholder="日期范围" name="daterange" id="daterange" value="<?php  echo $_GPC['daterange'];?>">
                    </div>
                </div>
                <div class="layui-inline">
                        <label class="layui-form-label" style="width:80px;">订单状态</label>
                        <div class="layui-input-inline" style="width:100px;">
                            <select  name="yn" id="yn">
                                <?php  if(is_array($jd_orderstate)) { foreach($jd_orderstate as $item) { ?>
                                <option value ="<?php  echo $item['value'];?>" <?php  if($_GPC['yn']==$item['value']) { ?>selected="selected"<?php  } ?>><?php  echo $item['name'];?></option>
                                <?php  } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label" style="width:80px;">订单号</label>
                        <div class="layui-input-inline" style="width:100px;">
                            <input type="text" value="<?php  echo $_GPC['orderids'];?>"  name="orderids"  placeholder="订单号" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                <div class="layui-inline">
                    <label class="layui-form-label" style="width:80px;">关键字</label>
                    <div class="layui-input-inline" style="width:100px;">
                        <input type="text" value="<?php  echo $_GPC['keyword'];?>"  name="keyword"  placeholder="会员ID、昵称、推广位ID" autocomplete="off" class="layui-input">
                    </div>
                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                </div>
            </div>
        </form>
    </div>
    <!--<xblock>-->
    <!--<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量禁用</button>-->
    <!--<button class="layui-btn" onclick="hjk_dialog_show('添加','<?php  echo webUrl('sysset/gradefy/add');?>')"><i class="layui-icon"></i>添加</button>-->
    <!--<span class="x-right" style="line-height:40px">共有数据 <?php  echo $allcount;?> 条</span>-->
    <!--</xblock>-->
    <table class="layui-table">
        <thead>
        <tr >
            <th>订单/商品</th>
            <th>计佣金额</th>
            <th>订单佣金</th>
            <th>推广位</th>
            <th>合伙人佣金</th>
            <th>一级佣金</th>
            <th>二级佣金</th>
            <th>三级佣金</th>
            <th>下单/完成时间</th>
            <?php  if(!empty($_GPC["isbyuser"])) { ?>
            <th class="text-center" style="">提单用户</th>
            <?php  } ?>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $r) { ?>
        <tr style='background-color:#f5f1f1;'>
            <td class="text-center">
                 <?php  echo $r['orderId'];?>【官方状态:<?php  echo $r['valistatus'];?>】
                 <?php  if($r['iscountcommission']=="false" && ($r['validCode']==16 || $r['validCode']==17 || $r['validCode']==18) && !empty($r['finishTime'])) { ?>
                 <button href="javascript:;" class="layui-btn" onclick="countordercommission(this)" style="padding: 2px;line-height: 30px;height: 30px;"
                 partnerid="<?php  echo $r['partner_member']['memberid'];?>" partnercommission="<?php  echo $r['partner_levelcommission'];?>"
                 memberid="<?php  echo $r['members']['memberid'];?>" membercommission="<?php  echo $r['my_commission1'];?>"
                 memberid1="<?php  echo $r['members2']['memberid'];?>" membercommission1="<?php  echo $r['my_commission2'];?>" 
                 memberid2="<?php  echo $r['members3']['memberid'];?>" membercommission2="<?php  echo $r['my_commission3'];?>" orderId="<?php  echo $r['orderId'];?>">
                     <i class="iconfont"></i>
                     <cite>提前结算</cite>
                 </button>
                 <?php  } else if($r['iscountcommission']=="true") { ?>
                 <a style="color:#16dc19;">
                        <i class="iconfont"></i>
                        <cite>用户已结算</cite>
                    </a>
                 <?php  } ?>
            </td>
            <td class="text-center">
                ￥<?php  echo $r['cosPrice'];?>
            </td>
            <td class="text-center">
                <span>总￥<?php  echo $r['commission'];?></span>
                <span style="color:#009688;">平台￥<?php  $zhuancommission= $r['commission']-$r['partner_levelcommission']-$r['my_commission1']-$r['my_commission2']-$r['my_commission3']?>
                        <?php  if($zhuancommission>0) { ?> <?php  echo number_format($zhuancommission,2)?><?php  } else { ?>0<?php  } ?>
                </span>
            </td>
            <td class="text-center">
                <?php  echo $r['positionId'];?>
            </td>
            <td class="text-center">
                    <?php  if(!empty($r['partner_member'])) { ?>
                    <?php  echo $r['partner_member']['memberid']?>/
                    <?php  echo $r['partner_member']['nickname']?>
                    <?php  } else { ?>
                    无
                    <?php  } ?>
                
            </td>
            <td class="text-center">
                    <?php  if(!empty($r['members'])) { ?>
                    <?php  echo $r['members']['memberid']?>/
                    <?php  echo $r['members']['nickname']?>
                    <?php  } else { ?>
                    <?php  echo getUserNameByPid($r['positionId'],$r['orderId'])?>
                    <?php  } ?>
                
            </td>
            <td class="text-center">
                <?php  if(!empty($r['members2'])) { ?>
                <?php  echo $r['members2']['memberid']?>/
                <?php  echo $r['members2']['nickname']?>
                <?php  } else { ?>
                无
                <?php  } ?>
            </td>
            <td class="text-center" style='text-align:left;'>

                <?php  if(!empty($r['members3'])) { ?>
                <?php  echo $r['members3']['memberid']?>/
                <?php  echo $r['members3']['nickname']?>
                <?php  } else { ?>
                无
                <?php  } ?>
            </td>
            <td class="text-center">
                <?php  echo date("Y-m-d H:i:s",$r['orderTime'])?>
            </td>
            <?php  if(!empty($_GPC["isbyuser"])) { ?>
            <td class="text-center">
                <?php  echo getUserNameByOrderno($r['orderId'])?>
            </td>
            <?php  } ?>
        </tr>
        <tr>
            <td class="text-center" colSpan='3'>
                <div style='width:400px;height:auto;overflow:hidden;text-align:left;'>
                    <?php  foreach($r['skus'] AS $sku){?>
                        【<?php  echo $sku['valistatus'];?>】<?php  echo $sku['skuName'];?><br/>
                    <?php  }
                                ?>
                </div>
            </td>
            <td class="text-center">

                <?php 
                                    echo  '  x'.count($r['skus']).'件';
                                    ?>
            </td>
            <td class="text-center">
                <?php  if(!empty($r['partner_levelcommission'])) { ?>￥<?php  echo $r['partner_levelcommission'];?><br/><?php  } ?><?php  if(!empty($r['partner_commission'])) { ?>(佣金比例：<?php  echo $r['partner_commission'];?>%)<?php  } ?>
            </td>
            <td class="text-center">
                ￥<?php  echo $r['my_commission1'];?><br/><?php  if(!empty($r['level1_commission_ratio'])) { ?>(佣金比例：<?php  echo $r['level1_commission_ratio'];?>%)<?php  } ?>
            </td>
            <td class="text-center">
                <?php  if(!empty($r['members2'])) { ?>
                ￥<?php  echo $r['my_commission2'];?> <br/><?php  if(!empty($r['level2_commission_ratio'])) { ?>(佣金比例：<?php  echo $r['level2_commission_ratio'];?>%)<?php  } ?>
                <?php  } else { ?>
                无
                <?php  } ?>
            </td>
            <td class="text-center" style='text-align:left;'>

                <?php  if(!empty($r['members3'])) { ?>
                ￥<?php  echo $r['my_commission3'];?> <br/><?php  if(!empty($r['level3_commission_ratio'])) { ?>(佣金比例：<?php  echo $r['level3_commission_ratio'];?>%)<?php  } ?>
                <?php  } else { ?>
                无
                <?php  } ?>
            </td>
            <td class="text-center">
                <?php  if(!empty($r["finishTime"])) { ?>
                <?php  echo date("Y-m-d H:i:s",$r['finishTime'])?>
                <?php  } ?>
            </td>
            <?php  if(!empty($_GPC["isbyuser"])) { ?>
            <td class="text-center">
                <?php  echo getUserNameByOrderno($r['orderId'])?>
            </td>
            <?php  } ?>
        </tr>
        <?php  } } ?>
        </tbody>
    </table>

    <?php  echo $pager;?>

</div>
</body>
</html>

<script>

    layui.use('laydate', function(){
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#daterange' //指定元素
            ,range: '~'
            , max:0
        });
    });
    function countordercommission(obj){
        var loadii = layer.load();
        var o=$(obj);
        var ajaxurl="<?php  echo webUrl('order/countordercommission')?>";
        var data= {"orderId":o.attr("orderId"), "partnerid": o.attr("partnerid"),"partnercommission":o.attr("partnercommission"),"memberid": o.attr("memberid"),"membercommission":o.attr("membercommission"),"memberid1":o.attr("memberid1"),"membercommission1":o.attr("membercommission1"),"memberid2":o.attr("memberid2"),"membercommission2":o.attr("membercommission2")};
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
    }
</script>