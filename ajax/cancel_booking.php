<?php 

    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');

    date_default_timezone_set("Asia/kolkata");
    session_start();


    if(!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        redirect('index.php');
    }


    if(isset($_POST['cancel_booking']))
    {
        $frm_data = filteration($_POST);
        
        $query = "UPDATE `booking_order` SET `booking_status`=?, `refund`=?
            WHERE `booking_id`=? AND `user_id`=?";
           
        $values = ['cancelled',0,$frm_data['id'],$_SESSION['id']];
        
        $result = update($query,$values,'siii');

        echo $result;
    }

?>    