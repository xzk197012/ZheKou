<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
        <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
    <body>
        <div class="x-body ">
            <div class="layui-container">
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                    <legend>切换好京客云服务接口</legend>
                </fieldset>
                    <div class="layui-row layui-col-space5">
                            <div class="layui-form-item">
                                    <button  type="button" id="switch1"  class="layui-btn" lay-filter="switch1" lay-submit="" onclick="switchcloud(1)">
                                            切换1号接口
                                    </button>
                                    <button  type="button" id="switch1"  class="layui-btn" lay-filter="switch1" lay-submit="" onclick="switchcloud(2)">
                                            切换2号接口
                                    </button>
                                    <button  type="button" id="switch1"  class="layui-btn" lay-filter="switch1" lay-submit="" onclick="switchcloud(3)">
                                            切换3号接口
                                    </button>
                                </div>
                </div>
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                    <legend></legend>
                </fieldset>
                <div class="layui-row layui-col-space5">
                    <div class="layui-col-xs3">
                        <div class="title">
                            <span class="x-right layui-badge "><?php  echo $totalcount;?></span>
                            <h5>会员总数</h5>
                        </div>
                        <div class="content">
                            <div class="layui-col-xs6">
                                七日新增占比
                            </div>
                            <div class="layui-col-xs6">
                                <span class=" layui-badge ">
                                    <?php  if($totalcount>0) echo round($sevendaycount*100/$totalcount,2)." %" ?>
                                </span>

                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs3">
                        <div class="title">
                            <span class="x-right layui-badge layui-bg-orange"><?php  echo $mysourcetotal;?></span>
                            <h5>自选商品源</h5>
                        </div>
                        <div class="content">
                            <div class="layui-col-xs6">
                                总商品源
                            </div>
                            <div class="layui-col-xs6">
                                <span class="x-right layui-badge layui-bg-orange"><?php  echo $sourcetotal;?></span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs3">
                        <div class="title">
                            <span class="x-right layui-badge layui-bg-green"><?php  echo $pidallcount;?></span>
                            <h5>推广位数量</h5>
                        </div>
                        <div class="content">
                            <div class="layui-col-xs6">
                                已使用
                            </div>
                            <div class="layui-col-xs6">
                                <span class=" layui-badge layui-bg-green"><?php  echo $pidusecount;?></span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs3">
                        <div class="title">
                            <span class="x-right layui-badge layui-bg-blue"><?php  echo $invitecount;?></span>
                            <h5>邀请总数</h5>
                        </div>
                        <div class="content">
                            <div class="layui-col-xs6">
                                邀请会员占比
                            </div>
                            <div class="layui-col-xs6">
                                <span class=" layui-badge layui-bg-blue"><?php  if($totalcount>0)  echo round($invitecount*100/$totalcount,2)." %"?></span>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div>
                <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                <div id="main" style="width: 100%;height:400px;"></div>
            </div>
            <fieldset class="layui-elem-field">
                <legend>新增用户 TOP 10</legend>
                <div class="layui-field-box">
                    <table class="layui-table" lay-even>
                        <thead>
                        <tr>
                            <th>头像</th>
                            <th>昵称</th>
                            <th>类型</th>
                            <th>等级</th>
                            <th>日期</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php  if(is_array($list)) { foreach($list as $r) { ?>
                        <tr>
                            <td class="text-center">
                                <?php  if(!empty($r['avatar'])) { ?>
                                <img src="<?php  echo str_replace('132132','132',tomedia($r['avatar']))?>" style='width:30px;height:30px;padding:1px;border:1px solid #ccc' />

                                <?php  } ?>
                            </td>
                            <td class="text-left">
                                <?php  echo $r['nickname'];?>
                            </td>
                            <td class="text-center">
                                <?php  if($r['type']==0) { ?>会员<?php  } else { ?>盟主<?php  } ?>
                            </td>
                            <td class="text-center">
                                <?php  if($r['level'] == 0) { ?>普通<?php  } else if($r['level'] == 1) { ?>白银<?php  } else if($r['level'] == 2) { ?> 黄金<?php  } else if($r['level'] == 3) { ?>铂金 <?php  } else if($r['level'] == 4) { ?>钻石<?php  } ?>
                            </td>
                            <td class="text-center">
                                <?php  echo date("Y-m-d H:i:s",$r['created_at'])?>
                            </td>
                        </tr>
                        <?php  } } ?>
                        </tbody>
                    </table>

                </div>
            </fieldset>
        </div>
    </body>
</html>
<script type="text/javascript" src="<?php echo NETS_HAOJIK_WEB_STYLE;?>js/echarts.min.js?v=201801291600"></script>
<script>

    var layer;
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));
    layui.use(['laydate','form','layer'], function(){
        var laydate = layui.laydate,
            form = layui.form
            ,layer = layui.layer;
        getstatistics();
    });
    function getstatistics() {
        var data = {
            operation: "post"
        };
        var loadii = layer.load();
        $.ajax({
            url: "<?php  echo webUrl('statistics/getstatistics')?>",
            type: 'post',
            data: data,
            cache: false,
            dataType: "json",
            async: true, //默认为true 异步
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                layer.close(loadii);
            },
            success: function (result) {
                layer.close(loadii);
                if(result.status_code=='200'){
                    myChart.clear();
                    var seriesdata=[];
                    var item={
                        name:'订单数量',
                        type:'line',
                        itemStyle : { normal: {label : {show: true}}},
                        data:result.data.count
                    }
                    seriesdata.push(item);
                    option ={
                        title: {
                            text: '交易走势图'
                        },
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['交易走势图']
                        },
                        toolbox: {
                            show: true,
                            feature: {
                                dataZoom: {
                                    yAxisIndex: 'none'
                                },
                                dataView: {readOnly: false},
                                magicType: {type: ['line', 'bar']},
                                restore: {},
                                saveAsImage: {}
                            }
                        },
                        xAxis:  {
                            type: 'category',
                            boundaryGap: true,
                            data:result.data.days
                        },
                        yAxis: {
                        },
                        series: seriesdata
                    }
                    // 使用刚指定的配置项和数据显示图表。
                    myChart.setOption(option);
                }
                else
                    layer.msg(result.result.message)

            }
        });
    }

    function switchcloud(id){
            var ajaxurl="<?php  echo webUrl('system/switchcloud',array('id'=>'_id_'))?>";
            ajaxurl=ajaxurl.replace("_id_",id);
            $.post(ajaxurl,function(res){
                var res=JSON.parse(res);
                if(res.status==1){
                    layer.alert(res.result.message, {icon: 6,closeBtn: 0},function () {
                        location.href=location.href;
                    });
                }else{
                    layer.msg("操作失败");
                }
            });
        
        };

</script>