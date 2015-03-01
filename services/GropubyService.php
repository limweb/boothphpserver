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
->where('arc_dtm_BillDate',$billdate)
->select(array(
        'dbo.AccountReceipts.arc_num_UserId',
        Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 1 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as CASH'),
        Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 2 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as CHK'),
        Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 3 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as CC'),
        Capsule::raw('sum( CASE WHEN dbo.PaymentModes.pmd_num_PaymentModeId = 4 THEN    dbo.AccountReceipts.arc_cur_Paid ELSE   0.00 END ) as DEBIT'),
        Capsule::raw('sum( ( dbo.AccountReceipts.arc_cur_paid -  dbo.AccountReceipts.arc_cur_ChangeAmount  )  ) as GrandTotal'),
    ))->groupBy('dbo.AccountReceipts.arc_num_UserId')->get();
echo json_encode($rs);
echo "\n======================\n";


$rss = Capsule::table('dbo.AccountReceipts')
// ->whereIn('dbo.AccountReceipts.arc_seq_AccountReceiptId',$billnums)
->leftjoin('dbo.Vendors','dbo.Vendors.ven_seq_VendorId','=','dbo.AccountReceipts.arc_num_VendorId')
->leftjoin('dbo.Payments','dbo.Payments.pay_num_AccountReceiptId','=','dbo.AccountReceipts.arc_seq_AccountReceiptId')
->leftjoin('dbo.PaymentModes','dbo.PaymentModes.pmd_num_PaymentModeId','=','dbo.Payments.pay_num_ModeOfPayment')
->leftjoin('dbo.Users','dbo.Users.usr_num_UserId','=','dbo.AccountReceipts.arc_num_UserId')
->where('arc_dtm_BillDate',$billdate)
->select( array(
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
                        ))->orderBy('Uname','ASC')->orderBy('RecID','ASC')->get();
echo json_encode($rss);