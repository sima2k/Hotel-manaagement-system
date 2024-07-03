<?php

    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function sendMail($email,$reset_token)
    {
        require('PHPMailer/PHPMailer.php');
        require('PHPMailer/SMTP.php');
        require('PHPMailer/Exception.php');

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'binddaa776@gmail.com';                     //SMTP username
            $mail->Password   =  'tklg lqll qkns febv';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom('binddaa776@gmail.com', 'FENIX');
            $mail->addAddress($email);     //Add a recipient
           


            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Password Reset Link from FENIX';
            $mail->Body    = "We got a request from you to reset your password! <br>
                Click the link below: <br>
                <a href='http://localhost/Hotel Management System/updatepassword.php?&email=$email&reset_token=$reset_token'>
                    Reset Password
                </a>        
            ";
            

            $mail->send();
            return true;
        } 
        catch (Exception $e) 
        {
            return false;
        }

    }

    if(isset($_POST['forgot_pass']))
    {
        $query="SELECT * FROM `user_cred` WHERE `email`='$_POST[email]'";
        $result=mysqli_query($con,$query);
        if($result)
        {
            if(mysqli_num_rows($result)==1)
            {
                /**email found */

                $reset_token=bin2hex(random_bytes(16));
                date_default_timezone_set("Asia/Kolkata");
                $date=date("Y-m-d");
                $query="UPDATE `user_cred` SET `token`='$reset_token',`t_expire`='$date' WHERE `email`='$_POST[email]'";
                if(mysqli_query($con,$query) && sendMail($_POST['email'],$reset_token))
                {
                    echo"
                    <script>
                        alert('Password Reset link sent to mail ');
                        window.location.href='index.php';
                    </script>
                ";
                }
                else{
                    echo 'upd_failed';
                }
            }
            else
            {
                echo 'inv_email';
            }
        }
        else
        {
            echo"
                <script>
                    alert('Cannot Run Query');
                    window.location.href='index.php';
                </script>
            ";
        }
    }
    //$token = 

?>
