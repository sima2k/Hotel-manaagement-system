<?php

    require('admin/inc/db_config.php');
    require('admin/inc/essentials.php');

    if(isset($_GET['email']) && isset($_GET['v_code']))
    {
       
        $query="SELECT * FROM `user_cred` WHERE `email`='$_GET[email]' AND `verification_code`='$_GET[v_code]'";
        $result=mysqli_query($con,$query);
        if($result)
        {
            if(mysqli_num_rows($result)==1)
            {
                $result_fetch=mysqli_fetch_assoc($result);
                if($result_fetch['is_verified']==0)
                {
                    $update="UPDATE `user_cred` SET `is_verified`='1' WHERE `email`='$result_fetch[email]'";
                    if(mysqli_query($con,$update)){
                        echo"
                            <script>
                                alert('Email verification successful');
                                window.location.href='index.php';
                            </script>
                        ";
                    }
                    else{
                        echo"
                            <script>
                                alert('Email already registered');
                                window.location.href='index.php';
                            </script>
                        ";
                    }
                }
                else{
                    echo"
                        <script>
                            alert('Email already registered');
                            window.location.href='index.php';
                        </script>
                    ";
                }
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

?>