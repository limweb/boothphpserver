<?php
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../database.php';

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


//------- test--------------------------start
// $sv = new DepositslipService();
// $sv->genpdf('2014-10-07');
// $rs = $sv->getApplog();
// $rs = $sv->getLogbydate('2014-10-08');
// $rs = $sv->updatebydate('aaa');
// $rs = $sv->testday('2015-02-14');
// $rs = $sv->getDate();
// var_dump($rs);
// echo $rs;
// echo json_encode($rs);
// $amf = new AMF();
// echo json_encode($amf->encode($sv->getbydate('2014-10-05')));
// echo json_encode($sv->getbydate('2014-10-05'));
// exit();
//------- test--------------------------end


class  DepositslipService {

        public function  getbydate($date,$y) {
            $testday = $this->testday($date);
            consolelog($testday);
            if($testday->rs == 1 ) {  // ok สามารถค้นหาได้เลย มากกว่า lastday == 1
                    return $this->getby($date);
            } else if($testday->rs == -1 ){ //Dete is searched is Dupicated
                    // -1  Dupicated
                     return -1;
            } else if($testday->rs == -2 ){   // มากกว่า lastday มากกว่า 1 ต้องขอคำตอบ yes no ด้วย
                   // -2  Date > lastDate
                if($y == 1 ) { return $this->getby($date); } else {
                      return -2;        
                }
            } else if($testday->rs == -3 ){ // วันที่น้อยกว่า lastday 
                    // -3  Date < lastDate
                    return -3;
            }
        }// end getbydate

        private  function getby($date) {
                 consolelog('start getby');
                 Capsule::enableQueryLog();
                 consolelog($obj->total->searchdate);
                 $searchdate = Applog::where('datesearch',$date)->first();
                 $loq = Capsule::getQueryLog();
                 consolelog($log);
                 consolelog($searchdate);
                 if(isset($searchdate->id) && $searchdate->id > 0  ){
                            return -1;
                 } else {
                    //-------- start --  ok ----------------------
                    $result = new stdClass();
                    //-------------user ----------------
                    $users =  Capsule::select('SELECT DISTINCT sum=0.00,dbo.AccountReceipts.arc_num_UserId AS UID, dbo.Users.usr_txt_Name AS UNAME FROM dbo.AccountReceipts INNER JOIN dbo.Users ON dbo.Users.usr_num_UserId = dbo.AccountReceipts.arc_num_UserId WHERE dbo.AccountReceipts.arc_dtm_BillDate = ? ',[$date]);
                    $result->users = $users;

                    //------ last invoice no -------------------

                    //------ last Data -------------------------

                    //-----  dupicate --------------------------

                    // $billdate = '2014-10-05 00:00:00.000';
                    $billdate = $date;
                    $billnums = array(248362,248473);

                    foreach ($users as $user) {
                          $item = [];
                          $item['user'] = $user;

                    // ------------ deposit slip -----------------
                          $rs = Capsule::table('dbo.AccountReceipts')
                                // ->whereIn('dbo.AccountReceipts.arc_seq_AccountReceiptId',$billnums)
                          ->leftjoin('dbo.Vendors','dbo.Vendors.ven_seq_VendorId','=','dbo.AccountReceipts.arc_num_VendorId')
                          ->leftjoin('dbo.Payments','dbo.Payments.pay_num_AccountReceiptId','=','dbo.AccountReceipts.arc_seq_AccountReceiptId')
                          ->leftjoin('dbo.PaymentModes','dbo.PaymentModes.pmd_num_PaymentModeId','=','dbo.Payments.pay_num_ModeOfPayment')
                          ->leftjoin('dbo.Users','dbo.Users.usr_num_UserId','=','dbo.AccountReceipts.arc_num_UserId')
                          ->where('arc_dtm_BillDate',$billdate)
                          ->where('usr_num_UserId',$user['UID'])
                          ->select( array(
                            Capsule::raw('chk=1'),
                            'arc_seq_AccountReceiptId as RecID',
                            'arc_dtm_BillDate as BillDate',
                            'dbo.Vendors.ven_seq_VendorId AS VID',
                            'dbo.Vendors.ven_txt_VendorName AS VName',
                            'dbo.Users.usr_num_UserId AS UID',
                            'dbo.Users.usr_txt_Name AS Uname',
                            'dbo.Payments.pay_seq_PaymentId AS PAYID',
                            'dbo.PaymentModes.pmd_num_PaymentModeId as MODEID',
                            'dbo.PaymentModes.pmd_txt_Description AS PAYMODE',
                            'dbo.Vendors.ven_txt_ResaleNo as CHECKNO',
                            'dbo.Payments.pay_txt_InstrumentNo AS CHECK',
                            'dbo.Vendors.ven_cur_OnAccount AS Debit',
                            Capsule::raw('CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 1 THEN  dbo.AccountReceipts.arc_cur_Paid  ELSE     0.00   END AS CASHAMT'),
                            Capsule::raw('CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 2 THEN     dbo.AccountReceipts.arc_cur_Paid
                                ELSE     0.00 END AS CHECKAMT'),
                            Capsule::raw('CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 3 THEN   dbo.AccountReceipts.arc_cur_Paid  
                                ELSE     0.00  END AS CCAMT'),
                            Capsule::raw('CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 4 THEN     dbo.AccountReceipts.arc_cur_Paid
                                ELSE     0.00 END AS DEBITAMT'),
                            Capsule::raw( '( dbo.AccountReceipts.arc_cur_paid -  dbo.AccountReceipts.arc_cur_ChangeAmount  ) AS GrandTotal' ),
                            ))->orderBy('Uname','ASC')->orderBy('RecID','ASC');

                          $item['items'] = $rs->get();

                          //--------- sum --------------------
                          $rss = Capsule::table('dbo.AccountReceipts')
                          ->leftjoin('dbo.Payments','dbo.Payments.pay_num_AccountReceiptId','=','dbo.AccountReceipts.arc_seq_AccountReceiptId')
                          ->leftjoin('dbo.PaymentModes','dbo.PaymentModes.pmd_num_PaymentModeId','=','dbo.Payments.pay_num_ModeOfPayment')
                          ->where('arc_dtm_BillDate',$billdate)
                          ->where('arc_num_UserId',$user['UID'])
                          ->select(array(
                            Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 1 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as CASH'),
                            Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 2 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as CHK'),
                            Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 3 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as CC'),
                            Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 4 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as DEBIT'),
                            ));
                          $item['sum'] = $rss->first();
                          $result->items[] = $item;
                    } // foreach
                    return $result;
                 } // else 
        }

        public function updatebydate($obj) {
              consolelog('start update');
              try {
                 Capsule::enableQueryLog();
                 consolelog($obj->total->searchdate);
                 
                 $searchdate = Applog::where('datesearch',$obj->total->searchdate)->first();
                 
                 $loq = Capsule::getQueryLog();
                 consolelog($log);
                 consolelog($searchdate);
                 if ( isset($searchdate->id) && $searchdate->id > 0 ){
                        return -1;  //dupicat date;
                 }  else {
                      Capsule::beginTransaction();
                      //consolelog('Updatebydate');
                      $cfg = (object) Appcfg::find(1)->first();
                      // //consolelog($cfg);
                      // id, numstart, numend, totalrows, totalsales, sumcash, sumcc, sumchk, sumdebit, sumtotal, datesearch
                      $totalrows = count($obj->sumAc);
                      $applog = [
                                  'numstart'=>$cfg->runnumbar,
                                  'numend'=>$cfg->runnumbar+$totalrows, 
                                  'totalrows'=>$totalrows, 
                                  'totalsales'=>count($obj->sumuser), 
                                  'sumcash'=>$obj->total->sumcash, 
                                  'sumcc'=>$obj->total->sumcc, 
                                  'sumchk'=>$obj->total->sumchk, 
                                  'sumdebit'=>$obj->total->sumdebit, 
                                  'sumtotal'=>$obj->total->sumtotal, 
                                  'datesearch'=> $obj->total->searchdate,
                                  'updated_by'=>$obj->user,
                                  'created_by'=>$obj->user
                            ];
                      $apl = Applog::create($applog);


                      //consolelog('start random array');
                      shuffle($obj->sumAc);
                      $runnumber = $cfg->runnumbar;
                      //consolelog($apl->id);
                      foreach ($obj->sumAc as  $item) {
                              //consolelog($item);
                              $item['app_id'] = $apl->id;
                              $item['NRCID'] = $runnumber;
                              $item['created_by'] = $obj->user;
                              $item['updated_by'] = $obj->user;
                              $dp =Depositslip::create($item);
                              //consolelog($runnumbar);
                              //consolelog($item);
                              $runnumber++; 
                      }
                      //consolelog('end');
                      consolelog('user=');
                      consolelog($obj->user);
                      foreach ($obj->sumuser as $user) {
                            $user->app_id = $apl->id;
                            $user->created_by = $obj->user;
                            $user->updated_by = $obj->user;
                            $u = Depouser::create(  json_decode(json_encode($user),true) );
                            //consolelog($u);
                      }
                      $cfg->runnumbar =  $apl->numend ;
                      $cfg->save();
                      Capsule::commit();
                      return 1;

                 }
              } catch (Exception $e) {
                  consolelog($e);
                  Capsule::rollback();
                  throw new  Exception($e, 1);
              }
        }

        public function delbyid(){

        }


        public function getApplog($search) {
            consolelog('getApplog search');
            consolelog($search);
            if($search == null ){
                    $rs = Applog::orderBy('datesearch','DESC')->take(40)->get()->toArray();
            }  else {
                    $rs = Applog::orderBy('datesearch','DESC')
                    ->whereBetween('datesearch', array($search->sdate, $search->tdate))
                    ->take(40)->get()->toArray();
            }
            return $rs;
        }


         public function getLogbydate($date){
            if($date == null ) return -1;
            $o = new \stdClass();
            $app = Applog::where('datesearch',$date)->first();
            $depoar = Depositslip::where('app_id',$app->id)->get()->toArray();
            $duserar = Depouser::where('app_id',$app->id)->get()->toArray();
            $o->app = $app->toArray();
            $o->deposlit = $depoar;
            $o->user = $duserar;
            return $o;
         } 


        public function getDate() {
            $lastdate = Capsule::select('select  DATEADD(DAY,1,MAX(datesearch)) as sdate from app_logs');
            if($lastdate[0]['sdate'] == null ){
                $lastdate = Appcfg::find(1)->first();
                return $lastdate->sdate;
            }
            return $lastdate[0]['sdate'];
        }

        public function test($obj) {

        }

          // '2014-10-05'
        public function genpdf($date){
               $applog = Applog::where('datesearch',$date)->first();
               consolelog('applog=',$applog);
               if( $applog == null  ) return -1;
               $appuser = Depouser::where('app_id',$applog->id)->orderBy('p','ASC')->get();
               $deposit =  Depositslip::where('app_id',$applog->id)->orderBy('UID','ASC')->orderBy('NRCID','ASC')->get();
//--------------------- pdf -----------------------------------------
$mpdf = new mPDF('c','A4','','',10,10,30,20,10,10);
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
            <th width="50px" align="left" height="30px"><u><h3>Rcpt Id</h3></u></th>
            <th align="center"><u><h3>Vendor ID</h3></u></th>
            <th align="left"><u><h3>Vendor Name</h3></u></th>
            <th width="100"  align="right"><u><h3>Cash Amt</h3></u></th>
            <th width="100"  align="right"><u><h3>Check Amt</h3></u></th>
            <th width="100"  align="left"><u><h3>Check #</h3></u></th>
            <th align="right"  width="120"><u><h3>CC Amt</h3></u></th>
            <th align="right"  width="120"><u><h3>Debit Amt </h3></u></th>
            <th align="right"  width="120"><u><h3>Total Amount</h3></u></th>
        </tr>
    <tbody>';
          foreach ($deposit as $depo) {
              if($depo->UID == $auser->userid) {
     $body .= '   
             <tr>
            <td valign="top"   width="70px" height="40px" align="right" >'.$depo->NRCID.'</td>
            <td valign="top"   align="center" width="100">'.$depo->VID.'</td>
            <td valign="top"   style="fontsize:40pt" align="left" width="300">'.$depo->VName.'</td>
            <td valign="top"   align="right" width="100">$'.number_format($depo->CASHAMT,2).'</td>
            <td valign="top"   align="right" width="100">$'.number_format($depo->CHECKAMT,2).'</td>
            <td valign="top"   align="left" width="100">'.$depo->CHECK.'</td>
            <td valign="top"   align="right" width="120">$'.number_format($depo->CCAMT,2).'</td>
            <td valign="top"   align="right" width="120">$'.number_format($depo->DEBITAMT,2).'</td>
            <td valign="top"   align="right" width="120">$'.number_format($depo->GrandTotal,2).'</td>
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
            <td  align="right" width="100"><h4>$'.number_format($auser->amtcash,2).'</h4></td>
            <td  align="right" width="100"><h4>$'.number_format($auser->chkamt,2).'</h4></td>
            <td  align="left" width="100"></td>
            <td  align="right" width="120"><h4>$'.number_format($auser->ccamt,2).'</h4></td>
            <td  align="right" width="120"><h4>$'.number_format($auser->debitamt,2).'</h4></td>
            <td  align="right" width="120"><h4>$'.number_format($auser->sum,2).'</h4></td>
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
            <td  align="right" width="100"><h4>$'.number_format($applog->sumcash,2).'</h4></td>
            <td  align="right" width="100"><h4>$'.number_format($applog->sumchk,2).'</h4></td>
            <td  align="left" width="100"></td>
            <td  align="right" width="120"><h4>$'.number_format($applog->sumcc,2).'</h4></td>
            <td  align="right" width="120"><h4>$'.number_format($applog->sumdebit,2).'</h4></td>
            <td  align="right" width="120"><h4>$'.number_format($applog->sumtotal,2).'</h4></td>
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

//--------------------- pdf -----------------------------------------

        }

        private function testday($obj) {
            $rs = Capsule::select('select  MAX(datesearch) as ldate,  DATEDIFF(DAY,MAX(datesearch) , ? ) as div from app_logs ',[$obj]);
            $div = $rs[0]['div'];
            $o = new \stdClass();
            $o->lastdate = $rs[0]['ldate'];
            $o->div = $div;
            if ($div == 1) {
                $o->rs = 1;  // ok 
            } else if( $div > 1 ) {
                $o->rs = -2; //warning Date is  > last search then 1 y/?
            } else if( $div < 0 ) {
                $o->rs = -3;  // Dete is < lastsearch can't  used this date
            } else if($o->div == null & $o->lastdate == null ) {
                $o->rs = 1;
            }else{
                $o->rs = -1;  //Dete is searched is Dupicated
            }
            return  $o; 
        } // end test day



} // class
