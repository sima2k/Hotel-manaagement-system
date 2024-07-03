<?php 

    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    function sendMail($email,$v_code)
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
                Click the link to confirm your email: <br>
                <a href='http://localhost/Hotel Management System/email_confirm.php?&email=$email&v_code=$v_code'>
                    VERIFY
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

    #for login
    if(isset($_POST['login']))
    {
        $query="SELECT * FROM `user_cred` WHERE `email`='$_POST[email_mob]' OR `phonenum`='$_POST[email_mob]'";
        $result=mysqli_query($con,$query);

        if($result)
        {
            if(mysqli_num_rows($result)==1)
            {
                $result_fetch=mysqli_fetch_assoc($result);
                if($result_fetch['is_verified']==1)
                {
                    if(password_verify($_POST['pass'],$result_fetch['password']))
                    {
                        #if password matched
                        
                        $_SESSION['logged_in']=true;
                        $_SESSION['id']=$result_fetch['id'];
                        $_SESSION['name']=$result_fetch['name'];
                        $_SESSION['profile']=$result_fetch['profile'];
                        header("location: index.php");
                    }
                    else
                    {
                        #if incorrect password
                        echo'invalid_pass';                 
                    }
                }
                else{
                    echo 'not_verified';
                }
            }
            else
            {
                echo 'inv_email_mob';
            }
        }
        else{
            echo"
                <script>
                    alert('Cannot Run Query');
                    window.location.href='index.php';
                </script>
            ";
        }
    }

    #for registration
    if(isset($_POST['register']))
    {
        //match password and confirm password field

        if($_POST['pass'] != $_POST['cpass']){
            echo 'pass_mismatch';
            exit;
        }

        //check user exists or not
        $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1",
        [$_POST['email'],$_POST['phonenum']],"ss");
        
        if(mysqli_num_rows($u_exist)!=0){
            $u_exist_fetch = mysqli_fetch_assoc($u_exist);
            echo ($u_exist_fetch['email'] == $_POST['email']) ? 'email_already' : 'phone_already';
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

       

        $user_exist_query="SELECT * FROM `user_cred` WHERE `email`='$_POST[email]' OR `phonenum`='$_POST[phonenum]'";
        $result=mysqli_query($con,$user_exist_query);

        if($result)
        {
            if(mysqli_num_rows($result)>0) #it will be executed if name or email is already registered
            {
                $result_fetch=mysqli_fetch_assoc($result);
                if($result_fetch['phonenum']==$_POST['phonenum'])
                {
                    #error for name already registered
                    echo"
                        <script>
                            alert('$result_fetch[phonenum] - name already taken');
                            window.location.href='index.php';
                        </script>
                    ";
                }
                else{
                    #error for email already registered
                    echo"
                        <script>
                            alert('$result_fetch[email] - E-mail already registered');
                            window.location.href='index.php';
                        </script>
                    ";
                }
            }
            else #it will executed if no one has taken name or email before
            {
                
                $v_code = bin2hex(random_bytes(16));

                $password=password_hash($_POST['pass'],PASSWORD_BCRYPT);

                $query="INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `pincode`, `dob`, `profile`, `password`, `verification_code`, `is_verified`) VALUES
                 ('$_POST[name]','$_POST[email]','$_POST[address]','$_POST[phonenum]','$_POST[pincode]','$_POST[dob]','$img','$password','$v_code','0')";

                if(mysqli_query($con,$query) && sendMail($_POST['email'],$v_code))
                {
                    #if data inserted successfully
                    echo"
                        <script>
                            alert('Registration successful');
                            window.location.href='index.php';
                        </script>
                    ";
                }
                else
                {
                     #if data cannot be inserted successfully
                    echo 'mail_failed';
                } 
            }
        }
        else{
            echo 'mail_failed';
        }
    }

?>