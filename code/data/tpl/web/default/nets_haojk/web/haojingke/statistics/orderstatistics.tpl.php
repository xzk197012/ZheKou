<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
        <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
    <body>
        <div class="x-body">
            <blockquote class="layui-elem-quote">注：未付款前订单会标记为有效订单。京东订单：24小时内未付款的话自动取消了，取消后订单会标记为无效订单。</blockquote>

            <div class="layui-row  ">
                <form action="" method="get"  enctype="multipart/form-data" class="layui-form layui-form-pane layui-col-md12">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">日期范围</label>
                            <div class="layui-input-inline">
                                <input class="layui-input" placeholder="日期范围" name="daterange" id="daterange" value="<?php  echo $_GPC['daterange'];?>">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">推广位</label>
                            <div class="layui-input-inline " style="width: 100px;">
                                <input type="text" value=""  name="positionId"  placeholder="推广位ID" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">订单状态</label>
                            <div class="layui-input-inline " style="width: 100px;">
                                <select id="yn" >
                                    <option value="" selected >全部</option>
                                    <option value="1">有效</option>
                                    <option value="0">取消</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline " style="width: 100px;">
                                <input title="订单数量" type="checkbox" onchange="getstatistics()" checked lay-filter="count"  id="count" style="vertical-align:middle;" >
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline " style="width: 100px;">
                                <input title="订单金额" type="checkbox" onchange="getstatistics()" lay-filter="cosprice" id="cosprice"  style="vertical-align:middle;" >
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline " style="width: 100px;">
                                <input title="佣金金额" type="checkbox" onchange="getstatistics()" lay-filter="commission" id="commission" style="vertical-align:middle;" >
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn" type="button" onclick="getstatistics()"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div>
                <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                <div id="main" style="width: 100%;height:400px;"></div>
            </div>
            <blockquote class="layui-elem-quote">注：未付款前订单会标记为有效订单。京东订单：24小时内未付款的话自动取消了，取消后订单会标记为无效订单。</blockquote>
            <fieldset class="layui-elem-field">
              <legend>订单信息统计</legend>
              <div class="layui-field-box">
                <table class="layui-table" lay-even>
                    <thead>
                        <tr>
                            <th>统计</th>
                            <th>订单量</th>
                            <th>取消订单</th>
                            <th>实际成交额</th>
                            <th>预计佣金</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>今日</td>
                            <td id="count0">0</td>
                            <td id="cancelcount0">0</td>
                            <td id="cosPrice0">0</td>
                            <td id="commission0">0</td>
                        </tr>
                        <tr>
                            <td>昨日</td>
                            <td id="count1">0</td>
                            <td id="cancelcount1">0</td>
                            <td id="cosPrice1">0</td>
                            <td id="commission1">0</td>
                        </tr>
                        <tr>
                            <td>近7日</td>
                            <td id="count2">0</td>
                            <td id="cancelcount2">0</td>
                            <td id="cosPrice2">0</td>
                            <td id="commission2">0</td>
                        </tr>
                        <tr>
                            <td>近30日</td>
                            <td id="count3">0</td>
                            <td id="cancelcount3">0</td>
                            <td id="cosPrice3">0</td>
                            <td id="commission3">0</td>
                        </tr>
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

        //执行一个laydate实例
        laydate.render({
            elem: '#daterange' //指定元素
            ,range: '~'
            , max:0
        });
        form.on('checkbox(count)', function(data){
            getstatistics();
        });
        form.on('checkbox(cosprice)', function(data){
            getstatistics();
        });
        form.on('checkbox(commission)', function(data){
            getstatistics();
        });
        form.on('button(sreach)', function(data){
            getstatistics();
        });
        getstatistics();
        getorders30();
    });
    function getstatistics() {
        var count = $('#count').is(':checked');
        var cosprice = $('#cosprice').is(':checked');
        var commission = $('#commission').is(':checked');
        var positionId = $("input[name='positionId']").val().trim();
        var daterange = $("input[name='daterange']").val().trim();
        var yn =  $("#yn").val().trim();
        var data = {
            operation: "post",
            positionId:positionId,
            daterange:daterange,
            yn:yn
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
                    if(count){
                        var item={
                            name:'订单数量',
                            type:'line',
                            itemStyle : { normal: {label : {show: true}}},
                            data:result.data.count
                        }
                        seriesdata.push(item);
                    }
                    if(cosprice){
                        var item={
                            name:'订单金额',
                            type:'line',
                            itemStyle : { normal: {label : {show: true,position:'top',   formatter: ' ￥{c}'}}},
                            data:result.data.cosPrice
                        }
                        seriesdata.push(item);
                    }
                    if(commission){
                        var item=
                            {
                                name:'佣金金额',
                                type:'line',
                                itemStyle : { normal: {label : {show: true,position:'top',   formatter: ' ￥{c}'}}},
                                data:result.data.commission
                            }
                        seriesdata.push(item);
                    }
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

    function getorders30(){
        $.ajax({
            url: "<?php  echo webUrl('statistics/getorders30')?>",
            type: 'post',
            data: {},
            cache: false,
            dataType: "json",
            async: true, //默认为true 异步
            success: function (result) {
                console.log(result);
                var res=result.data;
                for(var i=0;i<res.length;i++){
                    $("#cancelcount"+(i)).text(res[i].cancelcount);
                    $("#commission"+(i)).text(res[i].commission);
                    $("#cosPrice"+(i)).text(res[i].cosPrice);
                    $("#count"+(i)).text(res[i].count);
                }
            }
        });
    }
</script>