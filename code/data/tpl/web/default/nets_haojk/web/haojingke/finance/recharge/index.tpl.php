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
            <input type="hidden" name="r" value="<?php  echo $_W['action'];?>">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="c" value="site">

            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">日期范围</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" placeholder="日期范围" name="daterange" id="daterange" value="<?php  echo $_GPC['daterange'];?>">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select  name="type" id="type">
                            <option value ="0" <?php  if($_GPC['type']=='0') { ?>selected="selected"<?php  } ?>>全部</option>
                            <option value ="1" <?php  if($_GPC['type']=='1') { ?>selected="selected"<?php  } ?>>积分</option>
                            <option value ="2" <?php  if($_GPC['type']=='2') { ?>selected="selected"<?php  } ?>>佣金</option>
                            <option value ="3" <?php  if($_GPC['type']=='3') { ?>selected="selected"<?php  } ?>>补贴</option>
                            <option value ="4" <?php  if($_GPC['type']=='4') { ?>selected="selected"<?php  } ?>>充值</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">关键字</label>
                    <div class="layui-input-inline">
                        <input type="text" value="<?php  echo $_GPC['keyword'];?>"  name="keyword"  placeholder="昵称、手机号、订单号" autocomplete="off" class="layui-input">
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
            <th>会员ID</th>
            <th>会员</th>
            <th>手机号</th>
            <th>金额/积分</th>
            <th>类型</th>
            <th>备注</th>
            <th>状态</th>
            <th>时间</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $r) { ?>
        <tr>
            <td class="text-center">
                <?php  echo $r['memberid'];?>
            </td>
            <td class="text-center">
                <?php  echo $r['nickname'];?>
            </td>
            <td class="text-center">
                <?php  echo $r['mobile'];?>
            </td>
            <td class="text-center">

                <?php  if($r['type']==2 || $r['type']==3 || $r['type']==4) { ?>
                ￥
                <?php  } ?>
                <?php  echo $r['money'];?>
                <?php  if($r['type']==1) { ?>
                积分
                <?php  } ?>
            </td>
            <td class="text-center">
                <?php  if($r['type']==1) { ?>
                积分
                <?php  } else if($r['type']==2) { ?>
                佣金
                <?php  } else if($r['type']==3) { ?>
                补贴
                <?php  } else if($r['type']==4) { ?>
                充值
                <?php  } ?>

            </td>
            <td class="text-center">
                <?php  echo $r['remark'];?>
            </td>
            <td class="text-center">
                <?php  if($r['status']==1) { ?>
                已完成
                <?php  } else if($r['status']==0) { ?>
                待付款
                <?php  } ?>

            </td>
            <td class="text-center" >
                <?php  echo date('Y-m-d H:m:i', $r['created_at'])?>
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