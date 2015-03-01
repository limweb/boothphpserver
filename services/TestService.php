<?php
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../database.php';

class AccountReceipts  extends Illuminate\Database\Eloquent\Model  {
        protected $table = 'AccountReceipts';
        protected $primaryKey = 'arc_seq_AccountReceiptId';
        // protected $fillable = ['usr_txt_Name'];
        public $timestamps = false;

}


$billdate = '2014-10-05 00:00:00.000';
$billnums = array(248362,248473);
// $rs = AccountReceipts::
$rs = Capsule::table('dbo.AccountReceipts')
// ->whereIn('dbo.AccountReceipts.arc_seq_AccountReceiptId',$billnums)
->leftjoin('dbo.Vendors','dbo.Vendors.ven_seq_VendorId','=','dbo.AccountReceipts.arc_num_VendorId')
->leftjoin('dbo.Payments','dbo.Payments.pay_num_AccountReceiptId','=','dbo.AccountReceipts.arc_seq_AccountReceiptId')
->leftjoin('dbo.PaymentModes','dbo.PaymentModes.pmd_num_PaymentModeId','=','dbo.Payments.pay_num_ModeOfPayment')
->leftjoin('dbo.Users','dbo.Users.usr_num_UserId','=','dbo.AccountReceipts.arc_num_UserId')
->where('arc_dtm_BillDate',$billdate);

$rss = $rs;
$rs->select( array(
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

$rs = $rs->get();

// $rss->select(array(
//                 'dbo.AccountReceipts.arc_num_UserId', 
//                 Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 1 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as CASH'),
//                 Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 2 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as CHK'),
//                 Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 3 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as CC'),
//                 Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 4 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as DEBIT'),
//     ))->groupBy('dbo.AccountReceipts.arc_num_UserId')->get();

dd($rs);
// echo $rs;
// echo "\n---------------------------------------\n";
// echo $rss->get();





// echo json_encode($rs);
// dd($rs);

// $sql = "SELECT dbo.AccountReceipts.arc_seq_AccountReceiptId AS [Rcpt ID], dbo.Vendors.ven_seq_VendorId AS [VID], dbo.Vendors.ven_txt_VendorName AS [VName], dbo.AccountReceipts.arc_dtm_BillDate AS [DATE], dbo.Users.usr_num_UserId AS UID, dbo.Users.usr_txt_Name AS Uname, dbo.Payments.pay_seq_PaymentId AS PAYID, dbo.PaymentModes.pmd_num_PaymentModeId [MODEID], dbo.PaymentModes.pmd_txt_Description AS PAYMODE, CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 1 THEN dbo.AccountReceipts.arc_cur_Paid ELSE 0.00 END AS [CASHAMT], CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 2 THEN dbo.AccountReceipts.arc_cur_Paid ELSE 0.00 END AS [CHECKAMT], dbo.Vendors.ven_txt_ResaleNo [CHECKNO], dbo.Payments.pay_txt_InstrumentNo AS [CHECK], CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 3 THEN dbo.AccountReceipts.arc_cur_Paid ELSE 0.00 END AS [CC AMT], CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 4 THEN dbo.AccountReceipts.arc_cur_Paid ELSE 0.00 END AS [DEBIT AMT], dbo.Vendors.ven_cur_OnAccount AS Debit, ( [arc_cur_paid] - [arc_cur_ChangeAmount] ) AS GrandTotal FROM dbo.AccountReceipts LEFT JOIN dbo.Vendors ON dbo.Vendors.ven_seq_VendorId = dbo.AccountReceipts.arc_num_VendorId LEFT JOIN dbo.Payments ON dbo.Payments.pay_num_AccountReceiptId = dbo.AccountReceipts.arc_seq_AccountReceiptId JOIN dbo.Users ON dbo.Users.usr_num_UserId = dbo.AccountReceipts.arc_num_UserId LEFT JOIN dbo.PaymentModes ON dbo.PaymentModes.pmd_num_PaymentModeId = dbo.Payments.pay_num_ModeOfPayment WHERE dbo.AccountReceipts.arc_dtm_BillDate = '2014-10-05 00:00:00.000' AND dbo.AccountReceipts.arc_cur_Paid != 0 ORDER BY Uname ASC, [Rcpt ID] ASC";
// $rs = Capsule::select($sql)->where('arc_dtm_BillDate','=','2014-10-05 00:00:00.000');//->get();
// $rs = Capsule::select($sql);//->whereIn('dbo.AccountReceipts.arc_seq_AccountReceiptId',array(248362,248473))->get();
// $rs = AccountReceipts::join('dbo.Vendors','dbo.Vendors.ven_seq_VendorId','=','dbo.AccountReceipts.arc_num_VendorId')
// ->leftjoin('dbo.Payments','dbo.Payments.pay_num_AccountReceiptId','=','dbo.AccountReceipts.arc_seq_AccountReceiptId')
// ->leftjoin('dbo.PaymentModes','dbo.PaymentModes.pmd_num_PaymentModeId','=','dbo.Payments.pay_num_ModeOfPayment')
// ->leftjoin('dbo.Users','dbo.Users.usr_num_UserId','=','dbo.AccountReceipts.arc_num_UserId')
// ->where('arc_dtm_BillDate','=','2014-10-05 00:00:00.000')->whereIn('dbo.AccountReceipts.arc_seq_AccountReceiptId',array(248362,248473))->select( array(
//                             'arc_seq_AccountReceiptId as RecID',
//                             'arc_dtm_BillDate as BillDate',
//                             'dbo.Vendors.ven_seq_VendorId AS VID',
//                             'dbo.Vendors.ven_txt_VendorName AS VName',
//                             'dbo.Users.usr_num_UserId AS UID',
//                             'dbo.Users.usr_txt_Name AS Uname',
//                             'dbo.Payments.pay_seq_PaymentId AS PAYID',
//                              'dbo.PaymentModes.pmd_num_PaymentModeId MODEID',
//                             'dbo.PaymentModes.pmd_txt_Description AS PAYMODE',
//                             'dbo.Vendors.ven_txt_ResaleNo as CHECKNO',
//                             'dbo.Payments.pay_txt_InstrumentNo AS CHECK',
//                             'dbo.Vendors.ven_cur_OnAccount AS Debit'
//                             // Capsule::raw(' ( [dbo.AccountReceipts.arc_cur_paid -  dbo.AccountReceipts.arc_cur_ChangeAmount ] ) AS GrandTotal')
//                             ) 
//             );

// 'CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 1 THEN     dbo.AccountReceipts.arc_cur_Paid  ELSE     0.00   END AS [CASHAMT]',
//  'CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 2 THEN     dbo.AccountReceipts.arc_cur_Paid
// ELSE     0.00 END AS [CHECKAMT]',

//  CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 3 THEN     dbo.AccountReceipts.arc_cur_Paid 
// ELSE     0.00  END AS [CC AMT]',
//  'CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 4 THEN     dbo.AccountReceipts.arc_cur_Paid
// ELSE     0.00 END AS [DEBIT AMT]',

// echo json_encode($rs);

// $sql = "SELECT dbo.AccountReceipts.arc_seq_AccountReceiptId AS [Rcpt ID], dbo.Vendors.ven_seq_VendorId AS [VID], dbo.Vendors.ven_txt_VendorName AS [VName], dbo.AccountReceipts.arc_dtm_BillDate AS [DATE], dbo.Users.usr_num_UserId AS UID, dbo.Users.usr_txt_Name AS Uname, dbo.Payments.pay_seq_PaymentId AS PAYID, dbo.PaymentModes.pmd_num_PaymentModeId [MODEID], dbo.PaymentModes.pmd_txt_Description AS PAYMODE, CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 1 THEN dbo.AccountReceipts.arc_cur_Paid ELSE 0.00 END AS [CASHAMT], CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 2 THEN dbo.AccountReceipts.arc_cur_Paid ELSE 0.00 END AS [CHECKAMT], dbo.Vendors.ven_txt_ResaleNo [CHECKNO], dbo.Payments.pay_txt_InstrumentNo AS [CHECK], CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 3 THEN dbo.AccountReceipts.arc_cur_Paid ELSE 0.00 END AS [CC AMT], CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 4 THEN dbo.AccountReceipts.arc_cur_Paid ELSE 0.00 END AS [DEBIT AMT], dbo.Vendors.ven_cur_OnAccount AS Debit, ( [arc_cur_paid] - [arc_cur_ChangeAmount] ) AS GrandTotal FROM dbo.AccountReceipts LEFT JOIN dbo.Vendors ON dbo.Vendors.ven_seq_VendorId = dbo.AccountReceipts.arc_num_VendorId LEFT JOIN dbo.Payments ON dbo.Payments.pay_num_AccountReceiptId = dbo.AccountReceipts.arc_seq_AccountReceiptId JOIN dbo.Users ON dbo.Users.usr_num_UserId = dbo.AccountReceipts.arc_num_UserId LEFT JOIN dbo.PaymentModes ON dbo.PaymentModes.pmd_num_PaymentModeId = dbo.Payments.pay_num_ModeOfPayment WHERE dbo.AccountReceipts.arc_dtm_BillDate = :billdate  AND dbo.AccountReceipts.arc_cur_Paid != 0 ORDER BY Uname ASC, [Rcpt ID] ASC";

// $results = Capsule::select($sql, array(
//    'billdate' => '2014-10-05 00:00:00.000',
//  ));

// dd($results);

// where('dbo.AccountReceipts.arc_seq_AccountReceiptId','in','(248362,248473)');
// $rsamf = AMF::encode($rs);
// var_dump(json_encode($rsamf));

// class  User extends Illuminate\Database\Eloquent\Model {
//     protected $table = 'Users';
//     protected $primaryKey = 'usr_num_UserId';
//     protected $guarded = ['usr_txt_Password', 'usr_num_UserId'];
//     // protected $fillable = ['usr_txt_Name'];
//     public $timestamps = false;
//     protected $hidden = ['usr_txt_Password', 'usr_num_UserId'];
//  }

// $users = json_encode( Capsule::table('Users')->get() );
// $users = User::all()->first();
// $users = User::where('usr_num_UserId','=','1')->get(['usr_txt_Name','usr_dtm_TicketDate']);
// $users = User::get(['usr_txt_Name','usr_dtm_TicketDate']);
// $uamf =AMF::encode($users);
// var_dump($uamf);
// foreach ($users as $user) {
//     echo $user,"\n";
// }


// var_dump($users);
// try {
//     $dbh = new PDO("sqlsrv:Server=127.0.0.1;Database=test", 'sa', 'roottoor');
// }
// catch(PDOException $e) {
//     echo $e->getMessage();
// }

// if( $dbh ) {
//     echo "Database Connected.";
// } else {
//     echo "Database Connect Failed.";
// }

// $stmt = $dbh->prepare("select * from dbo.Users");
//   $stmt->execute();
//   while ($row = $stmt->fetch()) {
//     print_r($row);
//   }
//   unset($dbh); unset($stmt);