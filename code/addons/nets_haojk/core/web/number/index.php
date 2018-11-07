<?php
if (!(defined('IN_IA'))) 
{
    exit('Access Denied');
}

class Index_NetsHaojkPage extends WebPage
{
    public function main() 
    {
        global $_GPC;
        global $_W;

        if ($_W['action']=='number.index'||$_W['action']=='number')
            $this->index();
        elseif ($_W['action']=='mumber.position_delete')
            $this->number_delete();
        elseif ($_W['action']=='mumber.number_add')
            $this->number_add();
        elseif ($_W['action']=='mumber.upExecel')
            $this->upExecel();
        elseif ($_W['action']=='mumber.expExecel')
            $this->expExecel();
    }
    //列表
    public function index()
    {
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $date = array();
        $where='';
        //筛选          
            $where = " em.uniacid=:uniacid";       
        if(!empty($_GPC['keyword'])){
            $where .= " AND (em.phone like '%".$_GPC['keyword']."%' ) ";
        }
        if(!empty($_GPC['number'])){
            $phonenumber=$_GPC['number'];
             $this->number_add();
        }
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $list = pdo_fetchall("SELECT em.*,m.memberid,m.openid,m.nickname,m.from_uid FROM ".tablename("nets_hjk_numbers")." AS em left join ".tablename("nets_hjk_members")." AS m ON em.memberid=m.memberid where ".$where." ORDER BY id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize,array(':uniacid'=>$uniacid));
        $totalcount = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_numbers")." AS em  WHERE  " .$where,array(":uniacid"=>$_W["uniacid"]));
        $pager = pagination($totalcount, $pindex, $psize);
        
        include $this->template('haojingke/number/index');
    }

    //号码添加
    public function number_add(){
        global $_GPC, $_W;
         $phonenumber=$_GPC['number'];
         $uniacid=$_W["uniacid"];
         $created_at=time();
        if(!empty($phonenumber)){
            if(preg_match("/^1[34578]{1}\d{9}$/",$phonenumber)){
        $list = pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_numbers")." AS em where em.phone like '%".$_GPC['number']."%' ",array(':uniacid'=>$uniacid));
        if (empty($list)) {
        $user_data = array(
         'uniacid' => $uniacid,
         'phone' => $phonenumber,
         'created_at' => $created_at, 
        );
        $result = pdo_insert('nets_hjk_numbers', $user_data);
        if (!empty($result)){
        echo "<script>alert('添加号码成功')</script>";
        }
        else if(empty($result)){
            echo "<script>alert('添加号码失败')</script>";
        } 
        }else{
            echo "<script>alert('添加号码已存在')</script>";
        }
            }else{  
               echo "<script>alert('请输入正确手机号码')</script>";   
            }   
        }else if(empty($phonenumber)){
           echo "<script>alert('请输入正确手机号码')</script>"; 
        }

    }



    //会员删除-删除
    public function number_delete()
    {
           
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $phone=$_GPC['phone'];
        // echo "<script>alert($phone)</script>"; 
        $number_data = pdo_fetch("SELECT b.*,m.* FROM ".tablename('nets_hjk_numbers')." as b left join ".tablename('nets_hjk_members')." as m ON b.memberid=m.memberid  where b.phone = ".$_GPC['phone']." and b.uniacid = ".$uniacid);

        if (!empty($number_data)) {
            $j = pdo_delete('nets_hjk_numbers',array('phone'=>$_GPC['phone'],'uniacid'=>$uniacid));
            //$k = pdo_delete('mc_members',array('uid'=>$member_data['uid'],'uniacid'=>$uniacid));
            //$i = pdo_delete('mc_mapping_fans',array('uid'=>$member_data['uid'],'uniacid'=>$uniacid));
            if ($j>0) {
                show_json(1,"删除成功！");
            }else{
                show_json(1,"删除失败！");
            }
        }
        else
            show_json(1,"删除失败,会员不存在或已删除！");

    }
   
    //手机号码excel导出获取数据
    public  function expExecel(){
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $list = pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_numbers")." AS em where em.uniacid=$uniacid ORDER BY id DESC " ,array(':uniacid'=>$uniacid));
        if (!empty($list)) {
        //excel表格名
        $name = "号码表";
        //调用
       $this->expMobile($list,$name);
       }else{
       echo "<script>alert('号码池无号码')</script>"; 
       }
    }


    //导出excel
    public  function expMobile($list,$name){ 
    require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
     $objPHPExcel = new PHPExcel();
     /*右键属性所显示的信息*/
     $objPHPExcel->getProperties()->setCreator("zxf")       //作者
                           ->setLastModifiedBy("zxf")       //最后一次保存者
                           ->setTitle('数据EXCEL导出')      //标题
                           ->setSubject('数据EXCEL导出')    //主题
                           ->setDescription('导出数据')     //描述
                           ->setKeywords("excel")           //标记
                          ->setCategory("result file");     //类别
     //设置当前的表格 
    $objPHPExcel->setActiveSheetIndex(0);
    // 设置表格第一行显示内容
    $objPHPExcel->getActiveSheet()
       
        ->setCellValue('A1', '会员id')
        ->setCellValue('B1', '手机号码')
    //设置第一行为红色字体
        ->getStyle('A1:B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

    $key = 1;
    /*以下就是对处理Excel里的数据，横着取数据*/
    foreach($list as $v){

    //设置循环从第二行开始
    $key++;
    $objPHPExcel->getActiveSheet()

                 //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                   
                  ->setCellValue('A'.$key, $v['memberid'])
                  ->setCellValue('B'.$key, $v['phone']);          
    }
    //设置当前的表格 
     $objPHPExcel->setActiveSheetIndex()->setTitle('User');
            $objPHPExcel->setActiveSheetIndex(0);
            ob_end_clean();
             header('Content-Type: application/vnd.ms-excel');
             header('Content-Disposition: attachment;filename="'.$name.'.xls"');
             header('Cache-Control: max-age=0');
             $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
             $objWriter->save('php://output');
             exit;

    
    }




     //手机号码excel导入
    public  function upExecel(){
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
    //判断是否选择了要上传的表格
    if (empty($_POST['myfile'])) {
    echo "<script>alert(您未选择表格);history.go(-1);</script>";
    }

     //获取表格的大小，限制上传表格的大小5M
    $file_size = $_FILES['myfile']['size'];

    if ($file_size>5*1024*1024) {
    echo "<script>alert('上传失败，上传的表格不能超过5M的大小');history.go(-1);</script>";
    exit();
    }

//限制上传表格类型
$file_type = $_FILES['myfile']['type'];
//application/vnd.ms-excel  为xls文件类型
if ($file_type!='application/vnd.ms-excel') {
    echo "<script>alert('上传失败，只能上传excel2003的xls格式!');history.go(-1)</script>";
 exit();
}

//判断表格是否上传成功
if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
    require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
		require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';
		require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Reader/Excel5.php';

    //以上三步加载phpExcel的类
    $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format 
    //接收存在缓存中的excel表格
    $filename = $_FILES['myfile']['tmp_name'];
    $objPHPExcel = $objReader->load($filename); //$filename可以是上传的表格，或者是指定的表格
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow(); // 取得总行数 
    // $highestColumn = $sheet->getHighestColumn(); // 取得总列数
    //循环读取excel表格,读取一条,插入一条
    //j表示从哪一行开始读取  从第二行开始读取，因为第一行是标题不保存
    //$a表示列号
     for($j=2;$j<=$highestRow;$j++)  
    {
           $created_at=time();
       $a = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//获取A(业主名字)列的值
        $list = pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_numbers")." AS em where em.phone like '%".$a."%' ",array(':uniacid'=>$uniacid));
        if (empty( $list)) {
            if(preg_match("/^1[34578]{1}\d{9}$/",$a)){
            $result = pdo_insert('nets_hjk_numbers',array('uniacid'=>$uniacid,'phone'=>$a,'created_at'=>$created_at));
        }
        }
               
    }
       if (!empty($result)){
        echo "<script>alert('添加号码成功');history.go(-1);</script>";
        }
        else if(empty($result)){
        echo "<script>alert('添加号码失败');history.go(-1);</script>";
        } 
    
}
} 




}
?>