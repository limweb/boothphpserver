<?php
$start = microtime();
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../database.php';
date_default_timezone_set('UTC');
class Depositslip  extends Illuminate\Database\Eloquent\Model  {
    // id,app_id,RecID,BillDate,CASHAMT,CCAMT,CHECK,CHECKAMT,CHECKNO,chk,Debit,DEBITAMT,GrandTotal,MODEID,PAYID,PAYMODE,UID,Uname,VID,VName,NRCID
     protected $table = 'app_deposits';
     public $timestamps = true;
     protected $guarded = array('id');
     protected $hidden = ['created_at','updated_at'];
};

class Applog extends  Illuminate\Database\Eloquent\Model  {
     // id, numstart, numend, totalrows, totalsales, sumcash, sumcc, sumchk, sumdebit, sumtotal, datesearch
     protected $table = 'app_logs';
     public $timestamps = true;
     protected $guarded = array('id');
     protected $hidden = ['created_at','updated_at'];
};

class Appcfg extends  Illuminate\Database\Eloquent\Model  {
     // id, numstart, numend, totalrows, totalsales, sumcash, sumcc, sumchk, sumdebit, sumtotal, datesearch
     protected $table = 'app_config';
     public $timestamps = true;
     protected $guarded = array('id');
     protected $hidden = ['created_at','updated_at'];
};

class Depouser extends  Illuminate\Database\Eloquent\Model  {
     // id, numstart, numend, totalrows, totalsales, sumcash, sumcc, sumchk, sumdebit, sumtotal, datesearch
     protected $table = 'app_depousers';
     public $timestamps = true;
     protected $guarded = array('id');
     protected $hidden = ['created_at','updated_at'];
};

$date =  filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
consolelog('dateget=',$date);
if( !$date ) { 
    $date =  filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING); 
    consolelog('datepost =',$date);
}

if ($date) {
consolelog('gen pdf is start');
$applog = Applog::where('datesearch',$date)->first();
if( $applog == null  ) return -1;
$appuser = Depouser::where('app_id',$applog->id)->orderBy('p','ASC')->get();
$deposit =  Depositslip::where('app_id',$applog->id)->orderBy('UID','ASC')->orderBy('NRCID','ASC')->get();
//--------------------- pdf -----------------------------------------
$mpdf = new mPDF('utf-8','A4','','',10,10,30,20,10,10);
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
            <td align="left"><h6>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.converdate($date).'</h6></td>
            <td  align="center"></td>
            <td align="right"><h6>Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.date('g:i A').'</h6></td>
        </tr>
        <tr>
            <td  colspan="3" align="right"><h6>Serial #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$applog->id.'</h6></td>
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
                    body {font-family: time;
                    font-size: 12pt;
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

                </style>
        </head>
        <body>';

     $h = '';   
     $count = count($appuser);
     $i=0;  
    foreach ($appuser as $auser) {
        $top = '      
    <table width="100%">
        <tr>
            <td align="left" width="50px"><h4>User:</h4></td>
            <td align="left"><h4>'.$auser->userid.':'.$auser->uname.'</h4></td>
        </tr>
    </table>
    <table width="100%" style="fontsize:30px">
        <tr>
            <th nowrap="nowrap" width="50px" align="left" height="30px"><u><h3>Rcpt Id</h3></u></th>
            <th nowrap="nowrap" align="center"><u><h3>Vendor ID</h3></u></th>
            <th nowrap="nowrap" align="left"><u><h3>Vendor Name</h3></u></th>
            <th nowrap="nowrap" width="100"  align="right"><u><h3>Cash Amt</h3></u></th>
            <th nowrap="nowrap" width="100"  align="right"><u><h3>Check Amt</h3></u></th>
            <th nowrap="nowrap" width="100"  align="left"><u><h3>Check #</h3></u></th>
            <th nowrap="nowrap" align="right"  width="120"><u><h3>CC Amt</h3></u></th>
            <th nowrap="nowrap" align="right"  width="120"><u><h3>Debit Amt </h3></u></th>
            <th nowrap="nowrap" align="right"  width="120"><u><h3>Total Amount</h3></u></th>
        </tr>
    <tbody>';
          foreach ($deposit as $depo) {
              if($depo->UID == $auser->userid) {
     $body .= '   
             <tr>
            <td valign="top"   width="70px" height="40px" align="right" >'.$depo->NRCID.'</td>
            <td valign="top"   align="center" width="100">'.$depo->VID.'</td>
            <td valign="top"   style="fontsize:40pt" align="left" width="300">'.$depo->VName.'</td>
            <td valign="top"   align="right" width="100">'.numzero($depo->CASHAMT,2,1).'</td>
            <td valign="top"   align="right" width="100">'.numzero($depo->CHECKAMT,2,1).'</td>
            <td valign="top"   align="left" width="100">'.$depo->CHECK.'</td>
            <td valign="top"   align="right" width="120">'.numzero($depo->CCAMT,2,1).'</td>
            <td valign="top"   align="right" width="120">'.numzero($depo->DEBITAMT,2,1).'</td>
            <td valign="top"   align="right" width="120">'.numzero($depo->GrandTotal,2,1).'</td>
        </tr>   
        ';     
            // <td  align="left" width="100">'.$depo->CHECKNO.'</td>
        }
     } 
    $foot = ' 
    </tbody>
    </table>
     <div width="100%" height="3px" style="background:#000000;" />
    <table>
            <tr>
            <td  width="70px" align="left" ><h3>Total</h2></td>
            <td  align="center" width="100"></td>
            <td  style="fontsize:40pt" align="left" width="300"></td>
            <td  align="right" width="100"><h4>'.numzero($auser->amtcash,2).'</h4></td>
            <td  align="right" width="100"><h4>'.numzero($auser->chkamt,2).'</h4></td>
            <td  align="left" width="100"></td>
            <td  align="right" width="120"><h4>'.numzero($auser->ccamt,2).'</h4></td>
            <td  align="right" width="120"><h4>'.numzero($auser->debitamt,2).'</h4></td>
            <td  align="right" width="120"><h4>'.numzero($auser->sum,2,1).'</h4></td>
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
    $i++;
    if($i < $count) $foot .= '<br/>';
    if($body != '') {
      $h  .=  $top . $body . $foot;
    }
  }

$foothtml .= '
    <table>
            <tr>
            <td  colspan="2" align="left" ><h3>Grand Total</h2></td>
            <td  style="fontsize:40pt" align="left" width="300"></td>
            <td  align="right" width="100"><h4>'.numzero($applog->sumcash,2).'</h4></td>
            <td  align="right" width="100"><h4>'.numzero($applog->sumchk,2).'</h4></td>
            <td  align="left" width="100"></td>
            <td  align="right" width="120"><h4>'.numzero($applog->sumcc,2).'</h4></td>
            <td  align="right" width="120"><h4>'.numzero($applog->sumdebit,2).'</h4></td>
            <td  align="right" width="120"><h4>'.numzero($applog->sumtotal,2,1).'</h4></td>
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
  $html = $headhtml.$h.$foothtml;
  $mpdf->WriteHTML($html);
  $mpdf->Output();
  consolelog(microtime()-$start);
  }
else
  {
    consolelog('no date for pdf');
    return -1;
  }

// function converdate($date){
//     if($date == 'Present') return $date;
//     $de = new DateTime($date);
//     return $de->format('M d, Y');    
// }

function converttime($time) {

}

function numzero($number,$digi=2,$showzero=0,$curr='$') {
    if(!$showzero){
        if($number == 0 ) return;
    }
    if($number >= 0 ) {
        return $curr.number_format($number,$digi);
    } else {
        return  '-'.$curr.number_format(abs($number),$digi);
    }
}
 ?> 
