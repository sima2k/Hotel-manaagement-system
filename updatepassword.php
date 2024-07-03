<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Password Update</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        form{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            background-color: #f0f0f0;
            width: 350px;
            border-radius: 5px;
            padding: 20px 25px 30px 25px;
        }
        form h3{
            margin-bottom: 15px;
            color: #30475e;
        }
        form input{
            background-color: transparent;
            border: none;
            border-bottom: 2px solid #30475e;
            border-radius: 0;
            padding: 5px 0;
            font-weight: 550;
            font-size: 14px;
            outline: none;
            width: 100%;
            margin-bottom: 20px;
        }
        form button{
            font-weight: 550;
            font-style: 15px;
            color: white;
            background-color: #30475e;
            padding: 4px 10px;
            border: none;
            outline: none;
        }
    </style>
</head>
<body>
    

    <?php
    
        require('admin/inc/db_config.php');
        require('admin/inc/essentials.php');

        if(isset($_GET['email']) && isset($_GET['reset_token']))
        {
            date_default_timezone_set("Asia/Kolkata");
            $date=date("Y-m-d");
            $query="SELECT * FROM `user_cred` WHERE `email`='$_GET[email]' AND `token`='$_GET[reset_token]' AND `t_expire`='$date'";
            $result=mysqli_query($con,$query);
            if($result)
            {
                if(mysqli_num_rows($result)==1)
                {
                    echo"
                        <form method='POST'>
                            <h3 class='modal-title d-flex align-items-center'><i class='bi bi-shield-lock fs-3 me-2'></i>Set up New Password</h3>
                            <br>
                            <input type='password' placeholder='New Password' name='pass'>
                            <br>
                            <button type='submit' name='updatepassword'>UPDATE</button>
                            <input type='hidden' name='email' value='$_GET[email]'>
                        </form>
                    ";
                }
                else
                {
                    echo"
                        <script>
                            alert('Invalid or Expired Link!');
                            window.location.href='index.php';
                        </script>
                    ";
                }
            }
            else
            {
                echo"
                    <script>
                        alert('Server Down! try again later');
                        window.location.href='index.php';
                    </script>
                ";
            }
        }

    ?>

    <?php

        if(isset($_POST['updatepassword']))
        {
            $pass=password_hash($_POST['pass'],PASSWORD_BCRYPT);
            $update="UPDATE `user_cred` SET `password`='$pass',`token`=NULL,`t_expire`=NULL WHERE `email`='$_POST[email]'";
            if(mysqli_query($con,$update))
            {
                echo"
                    <script>
                        alert('Password Updated Successfully !');
                        window.location.href='index.php';
                    </script>
                ";
            }
            else
            {
                echo"
                    <script>
                        alert('Server Down! try again later');
                        window.location.href='index.php';
                    </script>
                ";
            }
        }

    ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>