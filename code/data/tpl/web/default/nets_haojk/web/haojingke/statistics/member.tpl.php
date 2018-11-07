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
            <input type="hidden" name="r" value="statistics.member">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="c" value="site">

            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">日期选择</label>
                    <div class="layui-input-inline-w100">
                        <select id='days' name="days" lay-filter="days">
                            <option value=""  <?php  if(empty($_GPC['days']) ) { ?> selected <?php  } ?>>按日期</option>
                            <option value="7"  <?php  if($_GPC['days']==7) { ?>selected<?php  } ?>>7天</option>
                            <option value="14"  <?php  if($_GPC['days']==14) { ?>selected<?php  } ?>>14天</option>
                            <option value="30"  <?php  if($_GPC['days']==30) { ?>selected<?php  } ?>>30天</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">年份</label>
                    <div class="layui-input-inline-w100">
                        <select id='year' name="year" lay-filter="year">
                            <option value=''>年份</option>
                            <?php  if(is_array($years)) { foreach($years as $y) { ?>
                            <option value="<?php  echo $y;?>"  <?php if(empty($_GPC['year'])?$y == date("Y"):$_GPC['year']==$y) { ?>selected="true"<?php  } ?>><?php  echo $y;?>年</option>
                            <?php  } } ?>

                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">月份</label>
                    <div class="layui-input-inline-w100">
                        <select id='month' name="mouth"  lay-filter="mouth">
                            <option value=' '>月份</option>
                            <?php  if(is_array($mouths)) { foreach($mouths as $m) { ?>
                            <option value="<?php  echo $m;?>"  <?php if(empty($_GPC['mouth'])?$m == date("m"):$_GPC['mouth']==$m) { ?>selected="true"<?php  } ?>><?php  echo $m;?>月</option>
                            <?php  } } ?>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                </div>
            </div>
        </form>
    </div>
    <div>

        <div id="container" style="min-width: 800px; height: 400px; margin: 0 auto"></div>
    </div>
</div>
</body>
</html>

<script src="<?php echo MODULE_URL;?>template/web/style/highcharts.js"></script>
<script>
    $(function () {
        $('#container').highcharts({
            chart: {
                type: 'line'
            },
            title: {
                text: '<?php  echo $charttitle;?>',
            },
            subtitle: {
                text: ''
            },
            colors: [
                '#0061a5',
                '#ff0000'
            ],
            xAxis: {
                categories: [    <?php  if(is_array($data)) { foreach($data as $key => $row) { ?>
                    <?php  if($key>0) { ?>,<?php  } ?>"<?php  echo $row['date'];?>"
                    <?php  } } ?>]
            },
            yAxis: {
                title: {
                    text: '人数'
                },allowDecimals:false
            },
            tooltip: {
                enabled: false,
                formatter: function() {
                    return '<b>'+ this.series.name +'</b><br>'+this.x +': '+ this.y +'°C';
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [
                {
                    name: '会员',
                    data: [
                        <?php  if(is_array($data)) { foreach($data as $key => $row) { ?>
                        <?php  if($key>0) { ?>,<?php  } ?><?php  echo $row['num'];?>
                        <?php  } } ?>
                    ]
                } ]
        });
    });
</script>