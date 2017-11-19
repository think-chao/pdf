<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 2017/11/8
 * Time: 0:38
 */
namespace Home\Controller;
use Think\Controller;
class SearchController extends Controller
{
    public function index()
    {
        $this->display();//显示页面
    }
    public function export_pdf($header=array(),$data=array(),$fileName='Newfile'){
        set_time_limit(120);
        if(empty($header) || empty($data)) $this->error("导出的数据为空！",'index');
        vendor("tcpdf.tcpdf");
        require_cache(VENDOR_PATH . 'tcpdf/examples/lang/eng.php');
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);//新建pdf文件
        //设置文件信息
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("Author");
        $pdf->SetTitle("pdf test");
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        //设置页眉页脚
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'www.thinkphp.com','Copyright © 2014-2015 by xxx, Ltd. All Rights reserved',array(66,66,66), array(0,0,0));
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);//设置默认等宽字体
        $pdf->SetMargins(PDF_MARGIN_LEFT, 24, PDF_MARGIN_RIGHT);//设置页面边幅
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);//设置自动分页符
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setLanguageArray($l);
        $pdf->SetFont('droidsansfallback', '');
        $pdf->AddPage();

        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(66, 66, 66);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('droidsansfallback', '',9);
        // Header
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $pdf->Cell(180/$num_headers, 8, $header[$i], 1, 0, 'C', 1);
        }
        $pdf->Ln();

        // 填充数据
        $fill = 0;
        foreach($data as $list) {
            //每頁重复表格标题行
            if(($pdf->getPageHeight()-$pdf->getY())<($pdf->getBreakMargin()+2)){
                $pdf->SetFillColor(245, 245, 245);
                $pdf->SetTextColor(0);
                $pdf->SetDrawColor(66, 66, 66);
                $pdf->SetLineWidth(0.3);
                $pdf->SetFont('droidsansfallback', '',9);
                // Header
                for($i = 0; $i < $num_headers; ++$i) {
                    $pdf->Cell(180/$num_headers, 8, $header[$i], 1, 0, 'C', 1);
                }
                $pdf->Ln();
            }
            // Color and font restoration
            $pdf->SetFillColor(245, 245, 245);
            $pdf->SetTextColor(40);
            $pdf->SetLineWidth(0.1);
            $pdf->SetFont('droidsansfallback', '');
            foreach($list as $key=>$row){
                //$pdf->Cell($width, 6, $row, 'LR', 0, 'C', $fill);
                $pdf->MultiCell(180/$num_headers, 6, $row, $border=1, $align='C',$fill, $ln=0, $x='', $y='',  $reseth=true, $stretch=0,$ishtml=false, $autopadding=true, $maxh=0, $valign='C', $fitcell=true);
            }
            $pdf->Ln();
            $fill=!$fill;
        }
        $showType= 'D';//PDF输出的方式。I，在浏览器中打开；D，以文件形式下载；F，保存到服务器中；S，以字符串形式输出；E：以邮件的附件输出。
        $pdf->Output("{$fileName}.pdf", $showType);
        exit;
    }
    public function pdf(){
        $list = M('imformation')->select();
        //print_r($list);
        //$title=array('ID','用户名','密码');
        //$data=array('','','');
        $row=array();
        $row[0]=array('序号','用户名','密码');
        $i=1;
        foreach($list as $v){
            $row[$i]['i'] = $i;
            $row[$i]['username'] = $v['username'];
            $row[$i]['password'] = $v['password'];
            $i++;
        }
        //print_r($row);
        $title=array('ID','用户名','密码');
        $this->export_pdf($title ,$row);

    }
    public function search()
    {
        //获取post的数据，根据数据组装查询的条件，根据条件从数据库中获取数据，返回给页面中遍历
        if(isset($_POST['username']) && $_POST['username']!=null)
        {
            $where['username']=array('like',"{$_POST['username']}");
        }

        $m=M("imformation");
        $total = $m -> count();
        $per = 7;
        //2. 实例化分页类对象
        $page = new \Component\Page($total, $per); //autoload
        $sql = "select * from shzu_imformation ".$page->limit;
        $info = $m -> query($sql);
       // print_r($info);
        $arry=$m->where($where)->select();
        $pagelist = $page -> fpage();
        $this->assign('data',$arry);
        $this -> assign('info', $info);
        $this -> assign('pagelist', $pagelist);
        $this->display('index');

    }
}