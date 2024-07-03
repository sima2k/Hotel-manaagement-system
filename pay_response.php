<?php 

    require('admin/inc/db_config.php');
    require('admin/inc/essentials.php');

    require('inc/paytm/config_paytm.php');
    require('inc/paytm/encdec_paytm.php');

    date_default_timezone_set("Asia/kolkata");

    session_start();
    unset($_SESSION['room']);

    function regenrate_session($uid)
    {
        $user_q = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$uid],'i');
        $user_fetch = mysqli_fetch_assoc($user_q);

        $_SESSION['logged_in']=true;
        $_SESSION['id']=$user_fetch['id'];
        $_SESSION['name']=$user_fetch['name'];
        $_SESSION['profile']=$user_fetch['profile'];
        $_SESSION['phonenum']=$user_fetch['phonenum'];

    }

    header("Pragma: no-cache");
    header("Cache-Control: no-cache");
    header("Expires: 0");

    $paytmChecksum = "";
    $paramList = array();
    $isValidChecksum = "FALSE";

    $paramList = $_POST;
    $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

    $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

    if($isValidChecksum == "FALSE") 
    {
        $slct_query = "SELECT `booking_id`, `user_id` FROM `booking_order` WHERE `order_id`='$_POST[ORDERID]'";

        $slct_res = mysqli_query($con,$slct_query);

        if(mysqli_num_rows($slct_res)==0){
            redirect('index.php');
        }

        $slct_fetch = mysqli_fetch_assoc($slct_res);

        if(!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
            //regenerate session
            regenrate_session($slct_fetch['user_id']);
        }


        if ($_POST["STATUS"] == "TXN_SUCCESS") 
        {
           $upd_query = "UPDATE `booking_order` SET `booking_status`='booked',
            `trans_id`='$_POST[TXNID]',`trans_amt`='$_POST[TXNAMOUNT]',
            `trans_status`='$_POST[STATUS]',`trans_resp_msg`='$_POST[RESPMSG]' 
            WHERE `booking_id`='$slct_fetch[booking_id]'";

            mysqli_query($con,$upd_query);

        }
        else 
        {
            $upd_query = "UPDATE `booking_order` SET `booking_status`='booked',
            `trans_id`='$_POST[TXNID]',`trans_amt`='$_POST[TXNAMOUNT]',
            `trans_status`='$_POST[STATUS]',`trans_resp_msg`='$_POST[RESPMSG]' 
            WHERE `booking_id`='$slct_fetch[booking_id]'";

            mysqli_query($con,$upd_query);

            redirect('pay_status.php?order='.$_POST['ORDERID']);
        }
        
    }
    else 
    {
        $upd_query = "UPDATE `booking_order` SET `booking_status`='booked',
            `trans_id`='$_POST[TXNID]',`trans_amt`='$_POST[TXNAMOUNT]',
            `trans_status`='$_POST[STATUS]',`trans_resp_msg`='$_POST[RESPMSG]' 
            WHERE `booking_id`='$slct_fetch[booking_id]'";

            mysqli_query($con,$upd_query);

        redirect('pay_status.php?order='.$_POST['ORDERID']);
    }

?>

