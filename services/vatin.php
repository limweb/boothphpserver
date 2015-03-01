<?php
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../database.php';
date_default_timezone_set('UTC');

$htmlhead = '
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Print Invoice</title>
';

$htmlcss = '<style>
        *
        {
            margin:0;
            padding:0;
            font-family:Arial;
            font-size:9pt;
            color:#000;
        }
        body
        {
            width:100%;
            font-family:Arial;
            font-size:9pt;
            margin:0;
            padding:0;
        }
        
        p
        {
            margin:0;
            padding:0;
        }
        
        #wrapper
        {
            width:180mm;
            margin:0 15mm;
        }
        
        .page
        {
            height:297mm;
            width:210mm;
            page-break-after:always;
        }

        table
        {
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
            
            border-spacing:0;
            border-collapse: collapse; 
            
        }
        
        table td 
        {
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 2mm;
        }
        
        table.heading
        {
            height:25mm;
        }
        
        h1.heading
        {
            font-size:14pt;
            color:#000;
            font-weight:normal;
        }
        
        h2.heading
        {
            font-size:9pt;
            color:#000;
            font-weight:normal;
        }
        
        hr
        {
            color:#ccc;
            background:#ccc;
        }
        
        #invoice_body
        {
            height: 149mm;
        }
        
        #invoice_body , #invoice_total
        {   
            width:100%;
        }
        #invoice_body table , #invoice_total table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
    
            border-spacing:0;
            border-collapse: collapse; 
            
            margin-top:5mm;
        }
        
        #invoice_body table td , #invoice_total table td
        {
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding:2mm 0;
        }
        
        #invoice_body table td.mono  , #invoice_total table td.mono
        {
            font-family:monospace;
            text-align:right;
            padding-right:3mm;
            font-size:10pt;
        }
        
        #footer
        {   
            width:180mm;
            margin:0 15mm;
            padding-bottom:3mm;
        }
        #footer table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
            
            background:#eee;
            
            border-spacing:0;
            border-collapse: collapse; 
        }
        #footer table td
        {
            width:25%;
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }
    </style>';
$htmlbodystart='    
</head>
<body>
    <div id="wrapper">
    
    <p style="text-align:center; font-weight:bold; padding-top:5mm;">ภาษีซื้อ</p>
    <br />
 ';
 
 $reportheading = '   
    <table class="heading" style="width:100%;">
        <tr>
            <td style="width:80mm;" valign="top">
                <h1 class="heading">บริษัท TomatoIdeas จำกัด</h1>
                <h2 class="heading">
                    สำนักงานใหญ่<br />
                    เลขประจำตัวผู้เสียภาษี: 1234567890123<br />
                    เดือนภาษี: มกราคม ปี พ.ศ. 2557
                </h2>
            </td>
            <td rowspan="1" valign="top" align="right" style="padding:3mm;">
                <table>
                    <tr  valign="top"><td>Dated : </td><td>2014-12-31</td></tr>
                </table>
            </td>
        </tr>
        <tr>
        </tr>
    </table>
    <div id="content">
        <div id="invoice_body">
            <table>
              <tr style="background:#eee;">
                    <td height="30" colspan="2">ใบกำกับภาษี</td>
                    <td width="28%" rowspan="2">ชื่อผู้ขายสินค้าผู้รับบริการ</td>
                    <td width="13%" rowspan="2"><p>เลขประจำตัวผู้เสียภาษี</p></td>
                    <td colspan="2"><p>สถานประกอบการ</p></td>
                    <td width="10%" rowspan="2">มูลค่าสินค้าหรือบริการ</td>
                    <td width="11%" rowspan="2">จำนวนเงินภาษีมูลค่าเพิ่ม</td>
              </tr>
              <tr style="background:#eee;">
                    <td width="11%" height="29">ปี/เดือน/วัน</td>
                    <td width="11%">เลขที่</td>
                    <td width="8%">สำนักงาน ใหญ่</td>
                    <td width="8%">สาขาที่</td>
              </tr>
            </table>
            <table>
    ';

    $reportbody = '
                <tr>
                    <td width="11%">2557/12/15</td>
                    <td width="11%">57001</td>
                    <td width="28%">บริษัท ไทเทเนี่ยม จำกัด</td>
                    <td width="13%">1234567890123</td>
                    <td width="8%">x</td>
                    <td width="8%" ></td>
                    <td width="10%" align="right">'.numfotmat(1000,2,1,'').'</td>
                    <td width="11%" align="right">'.numfotmat(70.00,2,1,'').'</td>
                </tr>
        ';
      $reportfoot = '      
            <tr>
                <td colspan="6" style="text-align:right; padding-right:3mm;">Total:</td>
                <td align="right">'.numfotmat(157.00,2,1,'').'</td>
                <td class="mono">'.numfotmat(157.00,2,1,'').'</td>
            </tr>
        </table>
        </div>';
       $invoice = ' 
        <div id="invoice_total">
            Total Amount :
            <table>
                <tr>
                    <td style="text-align:left; padding-left:10px;">One  Hundred And Fifty Seven  only</td>
                    <td style="width:15%;">USD</td>
                    <td style="width:15%;" class="mono"><?=numfotmat(157.00,2,1);?></td>
                </tr>
            </table>
        </div>
        <br />
        <hr />
        <br />
        
        <table style="width:100%; height:35mm;">
            <tr>
                <td style="width:65%;" valign="top">
                    Payment Information :<br />
                    Please make cheque payments payable to : <br />
                    <b>ABC Corp</b>
                    <br /><br />
                    The Invoice is payable within 7 days of issue.<br /><br />
                </td>
                <td>
                <div id="box">
                    E &amp; O.E.<br />
                    For ABC Corp<br /><br /><br /><br />
                    Authorised Signatory
                </div>
                </td>
            </tr>
        </table>
    </div>
    ';

    $mpdffooter = '
    <htmlpagefooter name="footer">
        <hr />
        <div id="footer">   
            <table>
                <tr><td>Software Solutions</td><td>Mobile Solutions</td><td>Web Solutions</td></tr>
            </table>
        </div>
    </htmlpagefooter>
    ';
    $mpdffooterset = '
    <sethtmlpagefooter name="footer" value="on" />
    ';

    $htmlfoot ='
    <br />
    </div>
    <br />
    </div>
    </body>
    </html>
     ';

 $h = $htmlhead.$htmlcss.$htmlbodystart.$reportheading;
 for($i=0;$i<80;$i++) {
   $h .= $reportbody;
   consolelog("i=",$i,':',$i);
 }
 $h .= $reportfoot.$htmlfoot;
 echo $h;
// .$mpdffooter.$mpdffooterset.

 exit();    

// function converdate($date){
//     if($date == 'Present') return $date;
//     $de = new DateTime($date);
//     return $de->format('M d, Y');    
// }

function converttime($time) {

}

function numfotmat($number,$digi=2,$showzero=0,$curr='$') {
    if(!$showzero){
        if($number == 0 ) return;
    }
    if($number >= 0 ) {
        return $curr.number_format($number,$digi);
    } else {
        return  '-'.$curr.number_format(abs($number),$digi);
    }
}
