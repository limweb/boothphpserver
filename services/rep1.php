<?php
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../database.php';
date_default_timezone_set('UTC');



$report = '
{
    "htmlhead":"<html><head></head><body>",
    "htmlfoot":"</body></html>",
    "css": "",
    "report":{
        "title":"title",
        "rhead":{
            "pageheader":"",
            "columnheaer":{
                "column":[
                    "AAAAA",
                    "BBBBBB",
                    "CCCCC",
                    "DDDDD",
                    "EEEEEE",
                    "FFFFFFF",
                    "GGGGG",
                    "HHHHH"
                ]
             }   
        },
        "rbody":{
            "details":{
                        "groups":[
                                        {
                                            "groupheader":"<pageheader name=\"MyHeader1\" content-right=\"My document\" header-style=\"font-weight: bold; color: #000000;\" line=\"on\" />",
                                            "groupdetail":"aaaaaa",
                                            "groupfooter":"<pagefooter name=\"MyFooter1\" content-left=\"{DATE j-m-Y}\" content-center=\"{PAGENO}/{nbpg}\" footer-style=\"font-size: 8pt;\" />"
                                        },
                                        {
                                            "groupheader":"<pageheader name=\"MyHeader2\" content-right=\"Chapter 2\" header-style=\"font-weight: bold; color: #000000;\" line=\"on\" />",
                                            "groupdetail":"bbbbbb",
                                            "groupfooter":"<pagefooter name=\"MyFooter2\" content-left=\"{DATE j-m-Y}\" content-center=\"2: {PAGENO}\" footer-style=\"font-size: 8pt;\" />"
                                        }
                        ],
                        "detail":[
                                [{"name":"aaaa"},{"name":"aaaa2"},{"name":"aaaa3"},{"name":"aaaa4"},{"name":"aaaa5"},{"name":"aaaa6"},{"name":"aaaa7"},{"name":"aaaa8"}],
                                [{"name":"aaaa"},{"name":"aaaa2"},{"name":"aaaa3"},{"name":"aaaa4"},{"name":"aaaa5"},{"name":"aaaa6"},{"name":"aaaa7"},{"name":"aaaa8"}],
                                [{"name":"aaaa"},{"name":"aaaa2"},{"name":"aaaa3"},{"name":"aaaa4"},{"name":"aaaa5"},{"name":"aaaa6"},{"name":"aaaa7"},{"name":"aaaa8"}],
                                [{"name":"aaaa"},{"name":"aaaa2"},{"name":"aaaa3"},{"name":"aaaa4"},{"name":"aaaa5"},{"name":"aaaa6"},{"name":"aaaa7"},{"name":"aaaa8"}],
                                [{"name":"aaaa"},{"name":"aaaa2"},{"name":"aaaa3"},{"name":"aaaa4"},{"name":"aaaa5"},{"name":"aaaa6"},{"name":"aaaa7"},{"name":"aaaa8"}],
                                [{"name":"aaaa"},{"name":"aaaa2"},{"name":"aaaa3"},{"name":"aaaa4"},{"name":"aaaa5"},{"name":"aaaa6"},{"name":"aaaa7"},{"name":"aaaa8"}],
                                [{"name":"aaaa"},{"name":"aaaa2"},{"name":"aaaa3"},{"name":"aaaa4"},{"name":"aaaa5"},{"name":"aaaa6"},{"name":"aaaa7"},{"name":"aaaa8"}],
                                [{"name":"aaaa"},{"name":"aaaa2"},{"name":"aaaa3"},{"name":"aaaa4"},{"name":"aaaa5"},{"name":"aaaa6"},{"name":"aaaa7"},{"name":"aaaa8"}],
                                [{"name":"aaaa"},{"name":"aaaa2"},{"name":"aaaa3"},{"name":"aaaa4"},{"name":"aaaa5"},{"name":"aaaa6"},{"name":"aaaa7"},{"name":"aaaa8"}]
                    ]
            }
        },
        "rfoot":{
            "columnfooter":["AAA"],
            "pagefooter":["pagefooter"]
        },
        "lastpagefooter":["lastpagefooter"],
        "summary":["aaaa"]
        },
    "datasource":["select * from xxxx "]
}
';

// <table>
//  <thead>
//   <tr>
//      <th>Month</th>
//      <th>Savings</th>
//   </tr>
//  </thead>
//  <tfoot>
//   <tr>
//      <td>Sum</td>
//      <td>$180</td>
//   </tr>
//  </tfoot>
//  <tbody>
//   <tr>
//      <td>January</td>
//      <td>$100</td>
//   </tr>
//   <tr>
//      <td>February</td>
//      <td>$80</td>
//   </tr>
//  </tbody>
// </table>
$report = json_decode($report);

$report->report->rhead->pageheader = '<table class="heading" style="width:100%;">
        <tr>
            <td style="width:80mm;">
                <h1 class="heading">ABC Corp</h1>
                <h2 class="heading">
                    123 Happy Street<br />
                    CoolCity - Pincode<br />
                    Region , Country<br />
                    
                    Website : www.website.com<br />
                    E-mail : info@website.com<br />
                    Phone : +1 - 123456789
                </h2>
            </td>
            <td rowspan="2" valign="top" align="right" style="padding:3mm;">
                <table>
                    <tr><td>Invoice No : </td><td>11-12-17</td></tr>
                    <tr><td>Dated : </td><td>01-Aug-2011</td></tr>
                    <tr><td>Currency : </td><td>USD</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <b>Buyer</b> :<br />
                Client Name<br />
            Client Address
                <br />
                City - Pincode , Country<br />
            </td>
        </tr>
    </table><br>';

$report->css = '<style>
        *
        {
            margin:0;
            padding:0;
            font-family:Arial;
            font-size:10pt;
            color:#000;
        }
        body
        {
            width:100%;
            font-family:Arial;
            font-size:10pt;
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
        table th
        {
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 2mm;
        }
        
        table.heading
        {
            height:50mm;
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


$head = '<table>
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
            </table>';


// var_dump($report);
$html = '';
if($report){
    if($report->datasource) {
        // $html .= toString($report->datasource);
     }
    $html .= $report->htmlhead;
    if($report->report){
        if($report->report->title){
            $html .=' <p style="text-align:center; font-weight:bold; padding-top:5mm;">'.$report->report->title.'</p>';
        }
        // $html .= '<table width="100%">';
        if($report->report->rhead){
            if($report->report->rhead->pageheader) {
                $html .= '<caption>'.$report->report->rhead->pageheader.'</caption>';
            }
            if($report->report->rhead->columnheaer){
                $columnheaer = $report->report->rhead->columnheaer;
                // $html .= '<thead><tr style="background:#eee;">';
                // if(count($columnheaer->column) > 0 ) {
                //     foreach ($columnheaer->column as $column) {
                        // $html .= '<th>'.toString($column) .'</th>';
                        // $html .= '<th colspan="8">'. $head . '</th>';
                //     }

                    // $html .= '</tr></thead>';
                // }
                $html .= $head;
            }

        $html .= '<table width="100%">';
        }
        if($report->report->rbody){
            $html .= '<tbody>';
            if($report->report->rbody->details){
                    $groups = $report->report->rbody->details->groups;
                    foreach ($groups as $group) {
                        $html .= '<tr>';
                        // $html .= '<td>'.toString($groups).'</td>';
                        $html .= '</tr>';
                    }
                    $detail = $report->report->rbody->details->detail;
                    foreach ($detail as $items) {
                        $html .= '<tr>';
                         foreach ($items as $item) {
                            $html .= '<td>'.$item->name.'</td>';
                         }
                        $html .= '</tr>';
                    }
            }
            $html .= '</tbody>';
        }

        if($report->report->rfoot){
            $html .= ' <tfoot><tr><td colspan="6" /><td align="center" >   Sum  </td><td  align="right">$180</td></tr></tfoot></table>';
            if($report->report->rfoot->columnfooter){
                $html .= '<br>'.toString($report->report->rfoot->columnfooter);
            }
            if($report->report->rfoot->pagefooter){
                $html .= '<br>'.toString($report->report->rfoot->pagefooter);
            }
        }
        if($report->report->lastpagefooter){
               $html .= '<br>'. toString($report->report->lastpagefooter);
        }
        if($report->report->summary) {
               $html .= '<br>'.toString($report->report->summary);
        }
    }
    $html .=$report->htmlfoot;
} else {
    echo 'Exit';
    exit();
}
// echo $html;

// exit();

/*$mpdf = new mPDF('',    // mode - default ''
 '',    // format - A4, for example, default ''
 0,     // font size - default 0
 '',    // default font family
 15,    // margin_left
 15,    // margin right
 16,     // margin top
 16,    // margin bottom
 9,     // margin header
 9,     // margin footer
 'L');  // L - landscape, P - portrait*/





$mpdf=new mPDF('utf-8'); 
// $a4 = $mpdf->_getPageFormat('A4');
$mpdf->_setPageSize('A4');
// $mpdf->SetTopMargin(100);
$mpdf->orig_bMargin =200;
// var_dump($a4);
// exit();
$mpdf->WriteHTML($report->css,1);
$style = '<style>
@page { margin-bottom: 1.5cm; }
@page :first { margin-bottom: 18cm; }
</style>';
$mpdf->WriteHTML($style);
$mpdf->WriteHTML($html);
$mpdf->Output();


function toString($obj) {
    if( gettype($obj) == 'object' || gettype($obj) == 'array' ){
        return json_encode($obj);
    } else {
        return $obj;
    }
}