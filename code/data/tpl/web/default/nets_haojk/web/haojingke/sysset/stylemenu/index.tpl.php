<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<link href="./resource/css/bootstrap.min.css?v=20170719" rel="stylesheet">
<link href="./resource/css/common.css?v=20170719" rel="stylesheet">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">
    
    <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
        
        <div class="layui-container">
            <blockquote class="layui-elem-quote">自定义购物首页</blockquote>
            <br/>
            <div class="layui-form-item" pane="">
                    <label class="layui-form-label">是否启用</label>
                    <div class="layui-input-block">
                            <input title="关闭" type="radio" name="isopen_diypage"  style="width: 20px;height: 34px;" value="0"  id="111" <?php  if($r['isopen_diypage']==0 ) { ?>checked="checked"<?php  } ?>><span  style="text-align: center;position: relative;bottom: 10px;left: 5px;" ></span>
                            <input title="启用" type="radio" name="isopen_diypage"  style="width: 20px;height: 34px;" value="1"  id="111" <?php  if($r['isopen_diypage']==1 ) { ?>checked="checked"<?php  } ?>><span  style="text-align: center;position: relative;bottom: 10px;left: 5px;" ></span>
                    </div>
                    <div class="layui-form-mid layui-word-aux">启用后将替换原有购物首页模板</div>
                  </div>
                  <br/>
            <blockquote class="layui-elem-quote">首页风格设置</blockquote>
            <div class="layui-row layui-col-space5">
                <div class="layui-col-md4">
                    <label><br/>
                        <img src="<?php  echo $style;?>" style="width: 250px;height: 450px;" ><br/></label>
                    <br>
                    <label>
                        <input title="风格一" type="radio" name="homeskinid"  style="width: 20px;height: 34px;" value="0"  id="111" <?php  if($r['homeskinid']==0 ) { ?>checked="checked"<?php  } ?>><span  style="text-align: center;position: relative;bottom: 10px;left: 5px;" ></span>
                    </label>
                </div>
                <div class="layui-col-md4">
                    <label><br/>
                        <img src="<?php  echo $style2;?>" style="width: 250px;height: 450px;" ><br/></label>
                    <br>
                    <label ><input title="风格二" type="radio" name="homeskinid" style="width: 20px;height: 34px;"  value="1" <?php  if($r['homeskinid']==1 ) { ?>checked="checked"<?php  } ?>><lable style="text-align: center;position: relative;bottom: 10px;left: 5px;"  ></lable>
                    </label>
                </div>
                <div class="layui-col-md4">
                    <label><br/>
                        <img src="<?php  echo $style3;?>" style="width: 250px;height: 450px;"><br/></label>
                    <br>
                    <label ><input title="风格三" type="radio" name="homeskinid" style="width: 20px;height: 34px;"  value="2" <?php  if($r['homeskinid']==2 ) { ?>checked="checked"<?php  } ?>><lable style="text-align: center;position: relative;bottom: 10px;left: 5px;"  ></lable>
                    </label>
                </div>
            </div>
            <br/>
            <blockquote class="layui-elem-quote">会员中心风格设置</blockquote>
            <div class="layui-row layui-col-space5">
                <div class="layui-col-md4">
                    <label><br/>
                        <img src="<?php  echo $memberstyle;?>" style="width: 250px;height: 450px;" ><br/></label>
                    <br>
                    <label>
                        <input title="风格一" type="radio" name="memberskin"  style="width: 20px;height: 34px;" value="0"  id="111" <?php  if($r['memberskin']==0 ) { ?>checked="checked"<?php  } ?>><span  style="text-align: center;position: relative;bottom: 10px;left: 5px;" ></span>
                    </label>
                </div>
                <div class="layui-col-md4">
                    <label><br/>
                        <img src="<?php  echo $memberstyle2;?>" style="width: 250px;height: 450px;" ><br/></label>
                    <br>
                    <label ><input title="风格二" type="radio" name="memberskin" style="width: 20px;height: 34px;"  value="1" <?php  if($r['memberskin']==1 ) { ?>checked="checked"<?php  } ?>><lable style="text-align: center;position: relative;bottom: 10px;left: 5px;"  ></lable>
                    </label>
                </div>
            </div>
            <div class="layui-row layui-col-space5">
                <div class="layui-col-md4">
                    <input type="hidden" name="op" value="post"/>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <input type="hidden" name="menu_name" value="风格设置"/>
                    </label>
                    <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                        提交
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</body>
</html>