<?php 
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../database.php';
date_default_timezone_set('UTC');
$mpdf = new mPDF('utf-8');
 
 // 'C','A4','','','margin_left ,margin_right ,margin_top ,margin_bottom ,margin_header ,margin_footer'

$mpdf = new mPDF('booth','A4','','',8,3,30,20,10,10);
$headhtml = '
    <!DOCTYPE html>
        <head>
                <meta charset="utf-8">
                <style>
                        
                    body {
                        font-family: mytime;
                        font-size: 10pt;
                    }

                    .header  {
                        font-size:35pt;
                        font-weight:bold;
                        color:#000;
                        // text-decoration: none;
                        // border-bottom: 0.2cm solid #000;  
                        // border-left:0.05cm solid #f0f0f0;
                        // border-right:0.05cm solid #ff00ff;
                    }

                    .detail  {
                        font-size:33pt;
                        font-weight:normal;
                        color:000;
                        // border-left:0.05cm solid #f0f0f0;
                        // border-right:0.05cm solid #ff00ff;
                    }

                    .total  {
                        font-size:35pt;
                        font-weight:bold;
                        color:000;
                        // border-left:0.05cm solid #f0f0f0;
                        // border-right:0.05cm solid #ff00ff;
                    }
            


                    .headerrow td, .headerrow th { 
                        border-top: 0.4cm solid #c0c0c0; 
                        border-bottom: 0.4cm solid #c0c0c0;  
                     }

                    .footerrow td, .footerrow th {   
                        border-bottom: 0.5cm solid #c0c0c0;  
                    }

                </style>
        </head>
        <body>
        <!--mpdf
        <htmlpageheader name="myheader">
            <div width="100%" valign="middle"  halign="center" height="30px" style="background:#C0C0C0;" >
            <table width="100%" height="50px">
                <tr>
                    <td align="center" valign="middle" style="font-size:1.5em;font-weight:bold;">Deposit Slip</td>
                </tr>
            </table>
            </div>
            <table width="100%">
                <tr>
                    <td  align="left" ><span style="font-size:1em;font-weight:bold;">Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>'.converdate('2015-02-14').'</span></td>
                    <td  align="center"></td>
                    <td align="right"><span style="font-size:1em;font-weight:bold;">Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>'.date('g:i A').'</span></td>
                </tr>
                <tr>
                    <td  align="left" ><span style="font-size:1em;font-weight:bold;">User:&nbsp;&nbsp;&nbsp;&nbsp;</span><span>Mr.AAA</span></td>
                    <td  align="center"></td>
                    <td  align="right"><span style="font-size:1em;font-weight:bold;">Serial #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>3985</span></td>
                </tr>
            </table>
        </htmlpageheader>

        <htmlpagefooter name="myfooter">
            <div width="100%" height="8px" style="background:#C0C0C0;" />
           <table width="100%">
               <tr>
                   <td width="33%" style="font-size:10pt;"  align="left">Printed on : '.date('F j, Y, g: A').'</td>
                   <td width="34%" style="font-size:10pt;" align="center">Page: {PAGENO} of {nb}</td>
                   <td width="33%" style="font-size:10pt;" align="right">Sunny Flea Market Inc.</td>
               </tr>
           </table>
        </htmlpagefooter>

        <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
        <sethtmlpagefooter name="myfooter" value="on" />
        mpdf-->  ';
  $bodyhtml = '      
    <htmlpageheader>
     <div width="100%" valign="middle"  halign="center" height="30px" style="background:#C0C0C0;" >
    <table width="100%" height="50px">
        <tr>
            <td align="center" valign="middle" style="font-size:1.5em;font-weight:bold;">Deposit Slip</td>
        </tr>
    </table>
    </div>
    <table width="100%">
        <tr>
            <td align="left" ><span>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>'.converdate('2015-02-14').'</span></td>
            <td  align="center"></td>
            <td align="right"><span>Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>'.date('g:i A').'</span></td>
        </tr>
        <tr>
            <td  colspan="3" align="right"> <span style="font-size:1em;font-weight:bold;">Serial #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>3985</span></td>
        </tr>
    </table>
    </htmlpageheader>
    <table width="100%" >
        <tr>
            <th  align="left"  height="40px"><u><span  class="header">Rcpt Id</span></u></th>
            <th  align="left"  ><u>  <span  class="header" >Vendor ID</span></u></th>
            <th  align="left"  ><u><span    class="header" > Vendor Name</span></u></th>
            <th  align="right" ><u>  <span  class="header" > Cash Amt</span></u></th>
            <th  align="right" ><u>  <span  class="header" > Check Amt </span></u></th>
            <th  align="right" >&nbsp;</th>
            <th  align="left"  ><u>  <span  class="header" > Check #</span></u></th>
            <th  align="right" ><u>  <span  class="header" > CC Amt</span></u></th>
            <th  align="right" ><u>  <span  class="header" > Debit Amt </span></u></th>
            <th  align="right" ><u>  <span  class="header" > Total Amount</span></u></th>
        </tr>
    <tbody>';
    for($i=0;$i<50;$i++ ) {
     $bodyhtml .= '   
           <tr>
            <td  class="detail" align="center" valign="top" height="40px" >248478</td>
            <td  class="detail" align="center" valign="top" >198</td>
            <td  class="detail" align="left"  valign="top" >Magdaleno Saragosa 4006 4007 4008 4009 +(4063,4064</td>
            <td  class="detail" align="right" valign="top" >$'.number_format(500.00,2).'</td>
            <td  class="detail" align="right" valign="top" >$'.number_format(0,2).'</td>
            <td  class="detail" align="right" > </td>
            <td  class="detail" align="center" valign="top" >121</td>
            <td  class="detail" align="right"  valign="top" >$'.number_format(0,2).'</td>
            <td  class="detail" align="right"  valign="top" >$'.number_format(0,2).'</td>
            <td  class="detail" align="right"  valign="top" >$'.number_format(0,2).'</td>
        </tr>   
        ';     
    }
  
    $foottable = ' 
          <tr  class="headerrow" >
            <td class="total" align="left">Total</td>
            <td class="total" align="center"></td>
            <td class="total" align="left"></td>
            <td class="total" align="right">$'.number_format(23121,2).'</td>
            <td class="total" align="right">$'.number_format(23,2).'</td>
            <td class="total" align="left"></td>
            <td class="total"></td>
            <td class="total" align="right">$'.number_format(23121,2).'</td>
            <td class="total" align="right">$'.number_format(23121,2).'</td>
            <td class="total" align="right">$'.number_format(23121,2).'</td>
        </tr> 
    ';

$chkamt = 350;
$ccamt = 350;
$foothtml .= '
            <tr   class="footerrow">
            <td  class="total" colspan="2"  align="left" >Grand Total</td>
            <td  class="total" align="left"></td>
            <td  class="total" align="right" >$'.number_format(23121,2).'</td>
            <td  class="total" align="right" >$'.number_format(23,2).'</td>
            <td  class="total" ></td><td></td>
            <td  class="total" align="right" >$'.number_format(23121,2).'</td>
            <td  class="total" align="right" >$'.number_format(23121,2).'</td>
            <td  class="total" align="right" >$'.number_format(23121,2).'</td>
        </tr> 
            <tr>
                <td style="width:180px"></td>
                <td style="width:300px"></td>
                <td style="width:550px"></td>
                <td style="width:300px"></td>
                <td style="width:350px"></td>
                <td style="width:20px"></td>
                <td style="width:220px"></td>
                <td style="width:300px"></td>
                <td style="width:380px"></td>
                <td style="width:500px;"></td>
            </tr>
            </tbody>
            </table>
        </body>
    </html>
    ';

$html = $headhtml.$bodyhtml.$foottable.$foothtml;
$mpdf->WriteHTML($html);
$mpdf->Output();

// function converdate($date){
//     if($date == 'Present') return $date;
//     $de = new DateTime($date);
//     return $de->format('M d, Y');    
// }

function converttime($time) {

}

 ?> 
