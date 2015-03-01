<?php 
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../database.php';
date_default_timezone_set('UTC');
$mpdf = new mPDF('utf-8');
 
 // 'C','A4','','','margin_left ,margin_right ,margin_top ,margin_bottom ,margin_header ,margin_footer'

$mpdf = new mPDF('c','A4','','',8,3,25,20,10,10);
$mpdf->SetHTMLHeader('
    <div width="100%" valign="middle"  halign="center" height="30px" style="background:#C0C0C0;" >
    <table width="100%" height="50px">
        <tr>
            <td align="center" valign="middle" style="fontsize:50px"><h3>Deposit Slip</h3></td>
        </tr>
    </table>
    </div>
    <table width="100%">
        <tr>
            <td align="left"><h6>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.converdate('2015-02-14').'</h6></td>
            <td  align="center"></td>
            <td align="right"><h6>Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.date('g:i A').'</h6></td>
        </tr>
        <tr>
            <td  colspan="3" align="right"><h6>Serial #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3985</h6></td>
        </tr>
    </table>
    ');


$mpdf->SetHTMLFooter(' <div width="100%" height="8px" style="background:#C0C0C0;" />
   <table width="100%">
       <tr>
           <td style="fontszie:8pt;"  align="left"><h6>Printed on : '.date('F j, Y, g: A').'</h6></td>
           <td style="fontszie:8pt;" align="center"><h6>Page: {PAGENO}/{nb}</h6></td>
           <td style="fontszie:8pt;" align="right"><h6>Sunny Flea Market Inc.</h6></td>
       </tr>
   </table>
   ');

$hh = '<thead>
        <tr>
            <th width="50px" align="left" height="30px"><u><h3>Rcpt Id</h3></u></th>
            <th align="center"><u><h3>Vendor ID</h3></u></th>
            <th align="left"><u><h3>Vendor Name</h3></u></th>
            <th align="right"><u><h3>Cash Amt</h3></u></th>
            <th  align="right"><u><h3>Check Amt</h3></u></th>
            <th width="100" align="left"><u><h3>Check #</h3></u></th>
            <th align="right" width="100"><u><h3>CC Amt</h3></u></th>
            <th align="right" width="100"><u><h3>Debit Amt </h3></u></th>
            <th align="right" width="150"><u><h3>Total Amount</h3></u></th>
        </tr>
    </thead>
';
$headhtml = '
    <!DOCTYPE html>
        <head>
                <meta charset="utf-8">
                <style>
                    body {
                        font-family: time;
                        font-size: 21pt;
                    }

                        total {    
                        font-weight: normal; 
                        font-size: 26pt; color: #000066; 
                        font-family: "DejaVu Sans Condensed"; 
                        margin-top: 18pt; margin-bottom: 6pt; 
                        border-top: 0.075cm solid #000000; 
                        border-bottom: 0.075cm solid #000000; 
                        text-align: ; page-break-after:avoid; 
                    }
                    table  { 
                        border-spacing: 0; 
                    }
                    td,th { 
                        border-spacing: 0; 
                        padding: 0; 
                    }

                    td {
                         font-size: 25pt;
                    }
                </style>
        </head>
        <body>';

  $bodyhtml = '      
    <table width="100%">
        <tr>
            <td align="left" width="50px"><h4>User:</h4></td>
            <td align="left"><h4>GARIO</h4></td>
        </tr>
    </table>
    <table width="100%" style="fontsize:30px">
        <tr>
            <th width="150" align="left" height="30px"><u><h2>Rcpt Id</h2></u></th>
            <th width="200" align="left"><u><h2>Vendor ID</h2></u></th>
            <th width="400" align="left"><u><h2>Vendor Name</h2></u></th>
            <th width="300"  align="right"><u><h2>Cash Amt</h2></u></th>
            <th width="300"  align="right"><u><h2>Check Amt    </h2></u></th>
            <th width="20"  align="right">&nbsp;</th>
            <th width="150"  align="left"><u><h2>Check #</h2></u></th>
            <th align="right"  width="300"><u><h2>CC Amt</h2></u></th>
            <th align="right"  width="300"><u><h2>Debit Amt </h2></u></th>
            <th align="right"  width="400"><u><h2>Total Amount</h2></u></th>
        </tr>
    <tbody>';
    for($i=0;$i<100;$i++ ) {
     $bodyhtml .= '   
             <tr>
            <td  width="150" valign="top"  height="40px" align="center" >248478</td>
            <td  width="200" valign="top"  align="center" >198</td>
            <td  style="fontsize:40pt" valign="top"  align="left" width="400">Magdaleno Saragosa 4006 4007 4008 4009 +(4063,4064</td>
            <td  align="right"  valign="top" width="300">$'.number_format(500.00,2).'</td>
            <td  align="right"  valign="top" width="300">$'.number_format(0,2).'</td>
            <td width="20"  align="right">&nbsp;</th>
            <td  align="center"  valign="top" width="150">121</td>
            <td  align="right"  valign="top" width="300">$'.number_format(0,2).'</td>
            <td  align="right"  valign="top" width="300">$'.number_format(0,2).'</td>
            <td  align="right"  valign="top" width="400">$'.number_format(0,2).'</td>
        </tr>   
        ';     
    }
  
    $foottable = ' 
    </tbody>
    </table>
     <div width="100%" height="3px" style="background:#000000;" />
    <table>
            <tr>
            <td  width="70px" align="left" ><h3>Total</h2></td>
            <td  align="center" width="100"></td>
            <td  style="fontsize:40pt" align="left" width="300"></td>
            <td  align="right" width="100"><h4>$'.number_format(23121,2).'</h4></td>
            <td  align="right" width="100"><h4>$'.number_format(23,2).'</h4></td>
            <td  align="left" width="100"></td>
            <td  align="right" width="120"><h4>$'.number_format(23121,2).'</h4></td>
            <td  align="right" width="120"><h4>$'.number_format(23121,2).'</h4></td>
            <td  align="right" width="120"><h4>$'.number_format(23121,2).'</h4></td>
        </tr> 
         <tr>
            <td  width="70px" align="right" ></td>
            <td  align="center" width="100"></td>
            <td  style="fontsize:40pt" align="left" width="300"></td>
            <td  align="right" width="100"></td>
            <td  align="right" width="100"></td>
            <td  align="left" width="100"></td>
            <td  align="right" width="120"></td>
            <td  align="right" width="120"></td>
            <td  align="right" width="120"></td>
        </tr>     
    </table>
     <div width="100%" height="3px" style="background:#000000;" />
    ';

$foothtml .= '
    <table>
            <tr>
            <td  colspan="2" align="left" ><h3>Grand Total</h2></td>
            <td  style="fontsize:40pt" align="left" width="300"></td>
            <td  align="right" width="100"><h4>$'.number_format(23121,2).'</h4></td>
            <td  align="right" width="100"><h4>$'.number_format(23,2).'</h4></td>
            <td  align="left" width="100"></td>
            <td  align="right" width="120"><h4>$'.number_format(23121,2).'</h4></td>
            <td  align="right" width="120"><h4>$'.number_format(23121,2).'</h4></td>
            <td  align="right" width="120"><h4>$'.number_format(23121,2).'</h4></td>
        </tr> 
        <tr>
            <td  width="70px" align="right" ></td>
            <td  align="center" width="100"></td>
            <td  style="fontsize:40pt" align="left" width="300"></td>
            <td  align="right" width="100"></td>
            <td  align="right" width="100"></td>
            <td  align="left" width="100"></td>
            <td  align="right" width="120"></td>
            <td  align="right" width="120"></td>
            <td  align="right" width="120"></td>
        </tr>  
    </table>
     <div width="100%" height="3px" style="background:#000000;" />
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
