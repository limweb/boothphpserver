<?php 
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../database.php';
date_default_timezone_set('UTC');
$mpdf = new mPDF('utf-8');
 
 // 'C','A4','','','margin_left ,margin_right ,margin_top ,margin_bottom ,margin_header ,margin_footer'

$mpdf = new mPDF('booth','A4','','',8,3,30,20,10,10);
$mpdf->SetHTMLHeader('
    <div width="100%" valign="middle"  halign="center" height="30px" style="background:#C0C0C0;" >
    <table width="100%" height="50px">
        <tr>
            <td align="center" valign="middle" style="font-size:1.5em;font-weight:bold;">Deposit Slip</td>
        </tr>
    </table>
    </div>
    <table width="100%">
        <tr>
            <td align="left" ><span style="font-size:1em;font-weight:bold;">Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>'.converdate('2015-02-14').'</span></td>
            <td  align="center"></td>
            <td align="right"><span style="font-size:1em;font-weight:bold;">Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>'.date('g:i A').'</span></td>
        </tr>
        <tr>
            <td  colspan="3" align="right"><span style="font-size:1em;font-weight:bold;">Serial #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>3985</span></td>
        </tr>
    </table>
    ');


$mpdf->SetHTMLFooter(' <div width="100%" height="8px" style="background:#C0C0C0;" />
   <table width="100%">
       <tr>
           <td width="33%" style="font-size:6pt;"  align="left">Printed on : '.date('F j, Y, g: A').'</td>
           <td width="34%" style="font-size:6pt;" align="center">Page: {PAGENO} of {nb}</td>
           <td width="33%" style="font-size:6pt;" align="right">Sunny Flea Market Inc.</td>
       </tr>
   </table>
   ');

$headhtml = '
    <!DOCTYPE html>
        <head>
                <meta charset="utf-8">
                <style>
                        
                        // font-family FONT-FAMILY
                        // font-size   
                        // font-style  italic | oblique | normal
                        // font-weight bold | normal (only)

                     * {
                        margin:0;
                        padding:0;
                        font-family: mytime;
                        font-size:10pt;
                        color:#000;
                    }
                    
                    body {
                        font-family: mytime;
                        font-size: 10pt;
                    }

                    total {    
                        font-weight: normal; 
                        font-size: 26pt; color: #000066; 
                        font-family: "mytime"; 
                        margin-top: 18pt; margin-bottom: 6pt; 
                        border-top: 0.075cm solid #000000; 
                        border-bottom: 0.075cm solid #000000; 
                        text-align: ; page-break-after:avoid; 
                    }

                    .header {
                        font-size:1.2em;
                        font-weight:bold;
                    }

                </style>
        </head>
        <body>';

  $bodyhtml = '      
    <table width="100%">
        <tr>
            <td align="left" width="50px" style="font-size:1em;font-weight:bold;">User:</td>
            <td align="left">GARIO</td>
        </tr>
    </table>
    <table width="100%" style="font-size:30px">
        <tr>
            <th width="150"  align="left"  height="40px"><u><span  class="header">Rcpt Id</span></u></th>
            <th width="200"  align="left"  ><u><span  class="header" >Vendor ID</span></u></th>
            <th width="500"  align="left"  ><u><span  class="header" > Vendor Name</span></u></th>
            <th width="300"  align="right" ><u><span  class="header" > Cash Amt</span></u></th>
            <th width="300"  align="right" ><u><span  class="header" > Check Amt </span></u></th>
            <th width="20"    align="right" >&nbsp;</th>
            <th width="150"  align="left"  ><u><span  class="header" > Check #</span></u></th>
            <th width="300"  align="right" ><u><span  class="header" > CC Amt</span></u></th>
            <th width="300"  align="right" ><u><span  class="header" > Debit Amt </span></u></th>
            <th width="400"  align="right" ><u><span  class="header" > Total Amount</span></u></th>
        </tr>
    <tbody>';
    for($i=0;$i<10;$i++ ) {
     $bodyhtml .= '   
           <tr>
            <td  width="150"  align="center" valign="top"  style="font-size:1.2em;font-weight:normal;" height="40px" >248478</td>
            <td  width="200"  align="center" valign="top" style="font-size:1.2em;font-weight:normal;"  >198</td>
            <td  width="500"  valign="top"  align="left" style="font-size:1.2em;font-weight:normal;">Magdaleno Saragosa 4006 4007 4008 4009 +(4063,4064</td>
            <td  width="300"  align="right"   valign="top"  style="font-size:1.2em;font-weight:normal;" >$'.number_format(500.00,2).'</td>
            <td  width="300"  align="right"   valign="top"  style="font-size:1.2em;font-weight:normal;" >$'.number_format(0,2).'</td>
            <td  width="20"    align="right"> </td>
            <td  width="150"  align="center" valign="top"  style="font-size:1.2em;font-weight:normal;" >121</td>
            <td  width="300"  align="right"    valign="top"  style="font-size:1.2em;font-weight:normal;" >$'.number_format(0,2).'</td>
            <td  width="300"  align="right"    valign="top"  style="font-size:1.2em;font-weight:normal;" >$'.number_format(0,2).'</td>
            <td  width="400"  align="right"    valign="top"  style="font-size:1.2em;font-weight:normal;" >$'.number_format(0,2).'</td>
        </tr>   
        ';     
    }
  
    $foottable = ' 
    </tbody>
    </table>
     <div width="100%" height="3px" style="background:#C0C0C0;" />
    <table width="100%" style="font-size:30px;font-weight:bold;">
          <tr>
            <td  width="150" align="left"    ><span style="font-size:1.2em;font-weight:bold;">Total</span></td>
            <td  width="200"  align="center"  ><span style="font-size:1.2em;font-weight:bold;"></span></td>
            <td  width="560"  align="left"     ><span style="font-size:1.2em;font-weight:bold;"></span></td>
            <td  width="450"  align="right"   ><span style="font-size:1.2em;font-weight:bold;">$'.number_format(23121,2).'</span></td>
            <td  width="300"  align="right"  ><span style="font-size:1.2em;font-weight:bold;">$'.number_format(23,2).'</span></td>
            <td  width="300"  align="left"    ><span style="font-size:1.2em;font-weight:bold;"></span></td>
            <td  width="20"></td>
            <td  width="300"  align="right"   ><span  style="font-size:1.2em;font-weight:bold;">$'.number_format(23121,2).'</span></td>
            <td  width="300"  align="right"   ><span  style="font-size:1.2em;font-weight:bold;">$'.number_format(23121,2).'</span></td>
            <td  width="450"  align="right"    ><span style="font-size:1.2em;font-weight:bold;">$'.number_format(23121,2).'</span></td>
        </tr> 
         <tr>
            <td  width="150"></td>
            <td  width="200"></td>
            <td  width="560"></td>
            <td  width="450"></td>
            <td  width="300"></td>
            <td  width="300"></td>
            <td  width="20"></td>
            <td  width="300"></td>
            <td  width="300"></td>
            <td  width="450"></td>
        </tr>     
    </table>
     <div width="100%" height="3px" style="background:#C0C0C0;" />
    ';

$foothtml .= '
    <table width="100%" style="font-size:30px">
            <tr>
            <td  colspan="2" width="350" align="left"  ><span style="font-size:1.2em;font-weight:bold;">Grand Total</span></td>
            <td  align="left" width="560" ><span style="font-size:1.2em;font-weight:bold;"><span></td>
            <td  align="right" width="450"><span style="font-size:1.2em;font-weight:bold;">$'.number_format(23121,2).'</span></td>
            <td  align="right" width="200" ><span style="font-size:1.2em;font-weight:bold;">$'.number_format(23,2).'</span></td>
            <td  align="left" colspan="2" width="320" ><span style="font-size:1.2em;font-weight:bold;"></span></td>
            <td  align="right" width="300" ><span style="font-size:1.2em;font-weight:bold;">$'.number_format(23121,2).'</span></td>
            <td  align="right" width="300" ><span style="font-size:1.2em;font-weight:bold;">$'.number_format(23121,2).'</span></td>
            <td  align="right" width="450" ><span style="font-size:1.2em;font-weight:bold;">$'.number_format(23121,2).'</span></td>
        </tr> 
        <tr>
            <td  width="150"></td>
            <td  width="200"></td>
            <td  width="560"></td>
            <td  width="450"></td>
            <td  width="200"></td>
            <td  width="300"></td>
            <td  width="20"></td>
            <td  width="300"></td>
            <td  width="300"></td>
            <td  width="450"></td>
        </tr>  
    </table>
     <div width="100%" height="3px" style="background:#C0C0C0;" />
        </body>
    </html>
    ';

$html = $headhtml.$bodyhtml.$foottable.$bodyhtml.$foottable.$foothtml;
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
