<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<link href="./resource/css/bootstrap.min.css?v=20180110" rel="stylesheet">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<style>.td-manage{text-align: left;} .td-manage a{margin: 1px;}</style>
<body>
<div class="x-body">
    <div class="layui-row">
        <form action="" method="get"  enctype="multipart/form-data" class="layui-form layui-form-pane layui-col-md12">
            <input type="hidden" name="m" value="nets_haojk">
            <input type="hidden" name="do" value="web">
            <input type="hidden" name="r" value="member.index">
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
                            <option value="3" <?php  if($_GPC['type']==3) { ?>selected="true"<?php  } ?>>合伙人</option>
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
            <th>推广位(京东/拼多多)</th>
            <th>时间</th>
            <th style="width: 280px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $r) { ?>
        <tr>
            <td class="text-center">
                ID:<?php  echo $r['memberid'];?>
                <br/>
                uniacid:<?php  echo $r['memberuniacid'];?>
            </td>
            <td class="text-left">

                <?php  if(!empty($r['avatar'])) { ?>
                <img src="<?php  echo str_replace('132132','132',tomedia($r['avatar']))?>" style='width:30px;height:30px;padding:1px;border:1px solid #ccc' />

                <?php  } ?>

                <span style="font-size:11px">
						<?php  echo $r['nickname'];?>
						【邀请人:<?php  echo $r['from_uid'];?>-<?php  echo get_fromnickname($r['from_uid'])?>】<br>
                        【合伙人:<?php  echo $r['from_partner_uid'];?>-<?php  echo get_fromnickname($r['from_partner_uid'])?>】
                </span>
                <br>
                <?php  echo $r['mfopenid'];?>
            </td>
            <td class="text-center">
                <?php  if($r['type']==0) { ?>会员<?php  } else if($r['type']==1) { ?>盟主<?php  } else if($r['type']==2) { ?>合伙人<?php  } ?>
            </td>
            <td class="text-center">
                <?php  if($r['level'] == 0) { ?>普通<?php  } else if($r['level'] == 1) { ?>白银<?php  } else if($r['level'] == 2) { ?> 黄金<?php  } else if($r['level'] == 3) { ?>铂金 <?php  } else if($r['level'] == 4) { ?>钻石<?php  } ?>
            </td>
            <td class="text-center">
                <?php  echo $r['credit1'];?>/<?php  echo $r['credit2'];?>
            </td>
            <td class="text-center">
                <?php  echo $r['jd_bitno'];?>/<?php  echo $r['pdd_bitno'];?>
            </td>
            <td class="text-center">
                <?php  echo date("Y-m-d H:i:s",$r['created_at'])?>
            </td>
            <td class="td-manage">
                <a  onclick="hjk_dialog_show('会员信息','<?php  echo url('mc/member',array('do'=>'post','uid'=>$r['memberid']))?>')"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6b8;</i> 会员信息</a>
                <a   onclick="hjk_dialog_show('查看预估订单','<?php  echo url('site/entry',array('m'=>'nets_haojk','do'=>'web','r'=>'order.index','keyword'=>$r['nickname']))?>')"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe723;</i> 订单(预估)</a>

                <a  onclick="hjk_dialog_show('编辑会员信息',' <?php  echo webUrl('member/member_edit');?>&id=<?php  echo $r['id'];?>')"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6f5;</i> 编辑</a>
                <br/>
                <?php  if($r['type']==2) { ?>
                <a  onclick="hjk_dialog_show('合伙人会员',' <?php  echo webUrl('member/member_subordinate');?>&memberid=<?php  echo $r['memberid'];?>&level=0')"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6f5;</i> 合伙人会员</a>
                <br/>
                <?php  } ?>

                <a  onclick="hjk_dialog_show('一级会员',' <?php  echo webUrl('member/member_subordinate');?>&memberid=<?php  echo $r['memberid'];?>&level=1')"  href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6f5;</i> 一级会员</a>
                
                <a  onclick="hjk_dialog_show('二级会员',' <?php  echo webUrl('member/member_subordinate');?>&memberid=<?php  echo $r['memberid'];?>&level=2')" href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-normal" ><i class="iconfont">&#xe6b8;</i> 二级会员</a>
                <a onclick="member_delete('<?php  echo $r['id'];?>')" href="javascript:;" class="layui-btn   layui-btn-mini  layui-btn-danger" ><i class="iconfont">&#xe69d;</i> 删除</a>

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
    /*删除*/
    function member_delete(id){
        layer.confirm('删除后不可恢复,确认要删除么？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('member/member_delete').'&id='?>"+id;
            $.post(ajaxurl,function(res){
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