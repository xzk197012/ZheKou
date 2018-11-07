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
            <input type="hidden" name="r" value="member.member_subordinate">
            <input type="hidden" name="level" value="<?php  echo $_GPC['level'];?>">
            <input type="hidden" name="memberid" value="<?php  echo $_GPC['memberid'];?>">
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
                    <label class="layui-form-label">身份</label>
                    <div class="layui-input-inline">
                        <select   id="type" name="type">
                            <option value=""<?php  if($_GPC['type']=="") { ?>selected="true"<?php  } ?>>全部</option>
                            <option value="1" <?php  if($_GPC['type']==1) { ?>selected="true"<?php  } ?>>会员</option>
                            <option value="2" <?php  if($_GPC['type']==2) { ?>selected="true"<?php  } ?>>盟主</option>

                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">关键字</label>
                    <div class="layui-input-inline">
                        <input type="text" value="<?php  echo $_GPC['keyword'];?>"  name="keyword"  placeholder="昵称、openid、会员ID" autocomplete="off" class="layui-input">
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
        <tr>
            <th >会员ID</th>
            <th >会员</th>
            <th>类型</th>
            <th>等级</th>
            <th>积分/余额</th>
            <th>时间</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $r) { ?>
        <tr>
            <td class="text-center">
                ID:<?php  echo $r['memberid'];?>

            </td>
            <td class="text-left">

                <?php  if(!empty($r['avatar'])) { ?>
                <img src="<?php  echo str_replace('132132','132',tomedia($r['avatar']))?>" style='width:30px;height:30px;padding:1px;border:1px solid #ccc' />

                <?php  } ?>

                <span style="font-size:11px">
                        <?php  echo $r['nickname'];?>
                        [邀请人:<?php  echo $r['from_uid'];?>-<?php  echo get_fromnickname($r['from_uid'])?>]</span>
                <br>
                <?php  echo $r['openid'];?>
            </td>
            <td class="text-center">
                <?php  if($r['type']==0) { ?>会员<?php  } else { ?>盟主<?php  } ?>
            </td>
            <td class="text-center">
                <?php  if($r['level'] == 0) { ?>普通<?php  } else if($r['level'] == 1) { ?>白银<?php  } else if($r['level'] == 2) { ?> 黄金<?php  } else if($r['level'] == 3) { ?>铂金 <?php  } else if($r['level'] == 4) { ?>钻石<?php  } ?>
            </td>
            <td class="text-center">
                <?php  echo $r['credit1'];?>/<?php  echo $r['credit2'];?>
            </td>
            <td class="text-center">
                <?php  echo date("Y-m-d H:i:s",$r['created_at'])?>
            </td>
        </tr>
        <?php  } } ?>
        </tbody>
    </table>

    <?php  echo $pager;?>

</div>
</body>
</html>

