<?php 

    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');
    date_default_timezone_set("Asia/kolkata");
    

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;



    function send_mail($email,$name,$token)
    {
        require('ajax/PHPMailer/PHPMailer.php');
        require('ajax/PHPMailer/SMTP.php');
        require('ajax/PHPMailer/Exception.php');

        $mail = new PHPMailer(true);



        try {
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'infohms@gmail.com';                     //SMTP username
                $mail->Password   =  'werf hgdf wyus dosg';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Recipients
                $mail->setFrom('infohms@gmail.com', 'FENIX');
                $mail->addAddress($email,$name);     //Add a recipient
               


                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Account Verification Link';
                $mail->Body    = "Thanks for registration!
                
                    Click the link to confirm yoy email: <br>
                    <a href='http://localhost/emailverify/email_confirm.php?email_confirmation&email=$email&token=$token'>
                        CLICK ME
                    </a>
                
                ";
                

                $mail->send();
                return 1;
            
            } 
        catch (Exception $e) 
        {
            return 0;
        }

    }


    if(isset($_POST['register']))
    {
        $data = filteration($_POST);

        //match password and confirm password field

        if($data['pass'] != $data['cpass']){
            echo 'pass_mismatch';
            exit;
        }

       //check user exists or not
       $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1",
           [$data['email'],$data['phonenum']],"ss");
        
       if(mysqli_num_rows($u_exist)!=0){
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
        exit;
       }    

       //upload user image to server

       $img = uploadImage($_FILES['profile'],USERS_FOLDER);

       if($img == 'inv_img'){
        echo 'inv_img';
        exit;
       }
       else if($img == 'upd_failed'){
        echo 'upd_failed';
        exit;
       }

       //send confirmation link to user's email

       $token = bin2hex(random_bytes(16));

       if(!send_mail($data['email'],$data['name'],$token)){
        echo 'mail_failed';
        exit;
       }

       $enc_pass = password_hash($data['pass'],PASSWORD_BCRYPT);

       $query = "INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `pincode`, `dob`, 
       `profile`, `password`, `token`) VALUES (?,?,?,?,?,?,?,?,?)";

       $values = [$data['name'],$data['email'],$data['address'],$data['phonenum'],$data['pincode'],$data['dob'],
        $img,$enc_pass,$token];

       if(insert($query,$values,'sssssssss')) 
       {
        echo 1;
       }
       else{
        echo 'ins_failed';
       }

    }


    if(isset($_POST['login']))
    {
        $data = filteration($_POST);

        //check user exists or not
        $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1",
        [$data['email_mob'],$data['email_mob']],"ss");
        
        if(mysqli_num_rows($u_exist)==0){
            echo 'inv_email_mob';
        }    
        else{
            $u_fetch = mysqli_fetch_assoc($u_exist);
            if($u_fetch['is_verified']==0){
                echo 'not_verified';
            }
            else if($u_fetch['status']==0){
                echo 'inactive';
            }
            else{
                if(!password_verify($data['pass'],$u_fetch['password'])){
                    echo 'invalid_pass';
                }
                else{
                    session_start();
                    $_SESSION['login'] = true;
                    $_SESSION['uId'] = $u_fetch['id'];
                    $_SESSION['uName'] = $u_fetch['name'];
                    $_SESSION['uPic'] = $u_fetch['profile'];
                    $_SESSION['uPhone'] = $u_fetch['phonenum'];
                    echo 1;
                }
            }
        }
    }

    if(isset($_POST['forgot_pass']))
    {
        $data = filteration($_POST);

        $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? LIMIT 1", [$data['email']],"s");
        
        if(mysqli_num_rows($u_exist)==0){
            echo 'inv_email';
        }  
        else
        {
            $u_fetch = mysqli_fetch_assoc($u_exist);
            if($u_fetch['is_verified']==0){
                echo 'not_verified';
            }
            else if($u_fetch['status']==0){
                echo 'inactive';
            }
            else{
                //send reset link to email
                $token = bin2hex(random_bytes(16));

                if(!send_mail($data['email'],$token,'account_recovery')){
                    echo 'mail_failed';
                }
                else{
                    $date = date("Y-m-d");

                    $query=mysqli_query($con,"UPDATE `user_cred` SET `token`='$token',`t_expire`='$date' 
                        WHERE `id`='$u_fetch[id]'");

                    if($query){
                        echo 1;
                    }    
                    else{
                        echo 'upd_failed';
                    }
                }
            }
        }
    }

    if(isset($_POST['recover_user']))
    {
        $data = filteration($_POST);

        $enc_pass = password_hash($data['pass'],PASSWORD_BCRYPT);

        $query = "UPDATE `user_cred` SET `password`=?, `token`=?, `t_expire`=? 
        WHERE `email`=? AND `token`=?";

        $values = [$enc_pass,null,null,$data['email'],$data['token']];

        if(update($query,$values,'sssss'))
        {
            echo 1;
        }
        else{
            echo 'failed';
        }

    }

    //"name: $name <br> email: $email <br> phonenum: $phonenum <br> profile: $profile <br> address: $address <br> pincode: $pincode <br> dob: $dob <br> pass: $pass <br> cpass: $cpass"

?>