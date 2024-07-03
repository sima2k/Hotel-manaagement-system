<!doctype html>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title> BOOKINGS </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

</head>
<body>
    <?php

        session_start();
        date_default_timezone_set("Asia/kolkata");

        require('admin/inc/db_config.php');
        require('admin/inc/essentials.php');

        if(!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
            redirect('index.php');
        }

    ?>

    <?php 
        $contact_q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
        $settings_q = "SELECT * FROM `settings` WHERE `sr_no`=?";
        $values = [1];
        $contact_r = mysqli_fetch_assoc(select($contact_q,$values,'i'));
        $settings_r = mysqli_fetch_assoc(select($settings_q,$values,'i'));

        if($settings_r['shutdown']){
            echo<<<alertbar
                <div class='bg-danger text-center p-2 fw-bold'>
                <i class="bi bi-exclamation-triangle-fill"></i>
                    Bookings are temporarily closed!
                </div>
            alertbar;
        }
        
    ?>

    
    

    <style>
            /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }

        .h-line{
        width: 150px;
        margin: 0 auto;
        height: 1.7px;
        }

        :root{
        --teal: #7fc142;
        --teal_hover: #7fc142;
        }

        .custom-bg{
        background-color: var(--teal);
        border: 1px solid var(--teal);
        }

        .custom-bg:hover{
        background-color: var(--teal_hover);
        background-color: var(--teal_hover);
        }

        .custom-alert{
        position: fixed;
        top: 80px;
        right: 30px;
        z-index: 1111;
        }
            
    </style>

    <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand me-5 fw-bold fs-3 h-font" href="index.php"><?php echo $settings_r['site_title'] ?></a>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link me-2" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link me-2" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active me-2"  aria-current="page"  href="room.php">Rooms</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link me-2" href="facilities.php">Facilities</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link me-2" href="GALLERY.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link me-2" href="CONTACT US.php">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    
                    <?php
                    
                        $path = USERS_IMG_PATH;

                        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)
                        {
                            
                            echo<<<data
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-dark shadow-none dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                    <img src="$path$_SESSION[profile]" style="width: 25px; height:25px;" class="me-1">    
                                    $_SESSION[name]
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-lg-end">
                                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                        <li><a class="dropdown-item" href="bookings.php">Bookings</a></li>
                                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                        
                                    </ul>
                                </div>
                            data;
                        }
                        else{
                            echo<<<data
                                <button type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    Login
                                </button>
                                <button type="button" class="btn btn-outline-dark shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal">
                                    Register
                                </button>
                            data;
                        }
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="login-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-person-circle fs-3 me-2"></i> User Login
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Email / Mobile</label>
                            <input type="text" name="email_mob" required class="form-control shadow-none">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="pass" required class="form-control shadow-none">
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <button type="submit" class="btn btn-dark shadow-none">LOGIN</button>
                            <button type="button" class="btn text-secondary text-decoration-none shadow-none p-0" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal">
                                Forget Password?
                            </button>
                        </div>
                    </div>
                    
                </form>
            
            </div>
        </div>
    </div>


    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="register-form" method="POST" action="register.php">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-person-lines-fill fs-3 me-2"></i>User Registeration
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span class="badge bg-light text-dark mb-3 text-wrap lh-base">
                        Note: Your details must match with your ID (Aadhaar card, passport, driving license, etc.)
                        that will required during check-in.
                        </span>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" type="text" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 p-0 mb-3">
                                    <label class="form-label">Email </label>
                                    <input name="email" type="email" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input name="phonenum" type="number" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 p-0 mb-3">
                                    <label class="form-label">Picture</label>
                                    <input name="profile" type="file" accept=".jpg, .jpeg, .png, .webp" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-12 p-0 mb-3">
                                    <label class="form-label">Address</label>
            
                                    <textarea name="address" class="form-control shadow-none" rows="1" required></textarea>
                                </div>
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label">Pincode</label>
                                    <input name="pincode" type="number" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 p-0 mb-3">
                                    <label class="form-label">Date of birth</label>
                                    <input name="dob" type="date" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label">Password</label>
                                    <input name="pass" type="password" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 p-0 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input name="cpass" type="password" class="form-control shadow-none" required>
                                </div>
                            
                            </div>
                        </div>
                        <div class="text-center my-1">
                            <button type="submit" class="btn btn-dark shadow-none">REGISTER</button>
                        </div>
                    </div>
            </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="forgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="forgot-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-person-circle fs-3 me-2"></i> Forgot Password
                        </h5>
                    </div>
                    <div class="modal-body">
                        <span class="badge bg-light text-dark mb-3 text-wrap lh-base">
                            Note: A link will be sent to your email to reset your password!
                        </span>
                        <div class="mb-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" required class="form-control shadow-none">
                        </div>
                        <div class="mb-2 text-end">
                            <button type="button" class="btn shadow-none p-0 me-2" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="submit" class="btn btn-dark shadow-none">SEND LINK</button>
                        </div>
                    </div>
                    
                </form>
            
            </div>
        </div>
    </div>


     

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">BOOKINGS</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">BOOKINGS</a>
                </div>
            </div>  

            
            <?php 

                $query = "SELECT bo.*, bd.* FROM `booking_order` bo
                INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
                WHERE ((bo.booking_status='booked')
                OR (bo.booking_status='cancelled')
                OR (bo.booking_status='payment failed'))
                AND (bo.user_id=?)
                ORDER BY bo.booking_id DESC";

                $result = select($query,[$_SESSION['id']],'i');

                while($data = mysqli_fetch_assoc($result))
                {
                    $date = date("d-m-Y",strtotime($data['datentime']));
                    $checkin = date("d-m-Y",strtotime($data['check_in']));
                    $checkout = date("d-m-Y",strtotime($data['check_out']));

                    $status_bg = "";
                    $btn = "";

                    if($data['booking_status']=='booked')
                    {
                        $status_bg = "bg-success";

                        if($data['arrival']==1)
                        {
                            $btn="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
                            
                            if($data['rate_review']==0)
                            {
                                $btn.="<button type='button' onclick='review_room($data[booking_id],$data[room_id])' data-bs-toggle='modal' data-bs-target='#reviewModal' class=' btn btn-dark btn-sm shadow-none ms-2'>Rate & Review</button>";
                            }
                        }
                        else{
                            $btn="<button onclick='cancel_booking($data[booking_id])' type='button' class=' btn btn-danger btn-sm shadow-none'>Cancel</button>";
                        }
                    }
                    else if($data['booking_status']=='cancelled')
                    {
                        $status_bg = 'bg-danger';

                        if($data['refund']==0){
                            $btn="<span class='badge bg-primary'>Refund in process!</span>";
                        }
                        else{
                            $btn="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
                        }
                    }
                    else
                    {
                        $status_bg = "bg-warning";
                        $btn="<a href='generate_pdf.php&gen_pdf?id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";

                    }


                    echo<<<bookings
                        <div class='col-md-4 px-4 mb-4'>
                            <div class='bg-white p-3 rounded shadow-sm'>
                                <h5 class='fw-bold'>$data[room_name]</h5>
                                <p>Rs.$data[price] per night</p>
                                <p>
                                    <b>Check in: </b> $checkin <br>
                                    <b>Check out: </b> $checkout
                                </p>
                                <p>
                                    <b>Amount: </b> Rs.$data[price] <br>
                                    <b>Order ID: </b> $data[order_id] <br>
                                    <b>Date: </b> $date
                                </p>
                                <p>
                                    <span class='badge $status_bg'>$data[booking_status]</span>
                                </p>
                                $btn
                            </div>
                        </div>
                    bookings;

                }

            ?>

                   
        </div>
    </div>
    

    <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-chat-square-heart-fill fs-3 me-2"></i> Rate & Review
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select class="form-select shadow-none" name="rating">
                                <option value="5">Excellent</option>
                                <option value="4">Good</option>
                                <option value="3">Ok</option>
                                <option value="2">Poor</option>
                                <option value="1">Bad</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Review</label>
                            <textarea type="password" name="review" rows="3" required class="form-control shadow-none"></textarea>
                        </div>
                        <input type="hidden" name="booking_id">
                        <input type="hidden" name="room_id">

                        <div class="text-end">
                            <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                           
                        </div>
                    </div>
                    
                </form>
            
            </div>
        </div>
    </div>


    <?php 
        if(isset($_GET['cancel_status'])){
            alert('success','Booking Cancelled!');
        }
        else if(isset($_GET['review_status'])){
            alert('success','Thank you for Rating & Review!');
        }
    ?>



    <div class="container-fluid bg-light mt-5 text-dark">
        <div class="row">
            <div class="col-lg-4 p-4">
                <h3 class="h-font fw-bold fs-3 mb-2"><?php echo $settings_r['site_title'] ?></h3>
                <p>
                <?php echo $settings_r['site_about'] ?>
                </p>
            </div>
            <div class="col-lg-4 p-4">
                <h5 class="mb-3">Links</h5>
                <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a><br>
                <a href="" class="d-inline-block mb-2 text-dark text-decoration-none">Rooms</a><br>
                <a href="" class="d-inline-block mb-2 text-dark text-decoration-none">Facilities</a><br>
                <a href="CONTACT US.php" class="d-inline-block mb-2 text-dark text-decoration-none">Contact Us</a><br>
                <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none">About</a>
            </div>
            <div class="col-lg-4 p-4">
                <h5 class="mb-3">Follow us</h5>
                <a href="" class="d-inline-block text-dark text-decoration-none mb-2">
                    <i class="bi bi-twitter me-1"></i> Twitter 
                </a><br>
                <a href="" class="d-inline-block text-dark text-decoration-none mb-2">
                    <i class="bi bi-facebook me-1"></i> Facebook 
                </a><br>
                <a href="" class="d-inline-block text-dark text-decoration-none">
                    <i class="bi bi-instagram me-1"></i> Instagram
                </a><br>
            </div>
        </div>
    </div>

    <h6 class="text-center bg-dark text-white p-3 m-0">Designed and Developed by FENIX.</h6>

   <script>
        function cancel_booking(id)
        {
            if(confirm('Are you sure to cancel booking?'))
            {
                let xhr = new XMLHttpRequest();
                xhr.open("POST","ajax/cancel_booking.php",true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function(){
                    if(this.responseText==1){
                        window.location.href="bookings.php?cancel_status=true";
                    }
                    else{
                        alert('error','Cancellation Failed!');
                    }
                }

                xhr.send('cancel_booking&id='+id);
                }
        }

        let review_form = document.getElementById('review-form');

        function review_room(bid,rid){
            review_form.elements['booking_id'].value = bid;
            review_form.elements['room_id'].value = rid;
        }

        review_form.addEventListener('submit',function(e){
            e.preventDefault();

            let data = new FormData();

            data.append('review_form','');
            data.append('rating',review_form.elements['rating'].value);
            data.append('review',review_form.elements['review'].value);
            data.append('booking_id',review_form.elements['booking_id'].value);
            data.append('room_id',review_form.elements['room_id'].value);

            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/review_room.php",true);
            
            xhr.onload = function()
            {
                if(this.responseText == 1)
                {
                    window.location.href = 'bookings.php?review_status=true';
                }
                else{
                    var myModal = document.getElementById('reviewModal');
                    var modal = bootstrap.Modal.getInstance(myModal);
                    modal.hide();
    
                    alert('error',"Rating & Review Failed!");
                }
            }

            xhr.send(data);
        })
   </script>


    <script>

        function alert(type,msg,position='body'){
            let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
            let element = document.createElement('div');
            element.innerHTML = `
            <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
                <strong class="me-3">${msg}</strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            `;
            if(position=='body'){
                document.body.append(element);
                element.classList.add('custom-alert');
            }
            else{
                document.getElementById(position).appendChild(element);
            }

            
            setTimeout(remAlert, 3000);
        }

        function remAlert(){
            document.getElementsByClassName('alert')[0].remove();
        }


        let register_form = document.getElementById('register-form');

        register_form.addEventListener('submit',(e)=>{
            e.preventDefault();

            let data = new FormData();

            data.append('name',register_form.elements['name'].value);
            data.append('email',register_form.elements['email'].value);
            data.append('phonenum',register_form.elements['phonenum'].value);
            data.append('address',register_form.elements['address'].value);
            data.append('pincode',register_form.elements['pincode'].value);
            data.append('dob',register_form.elements['dob'].value);
            data.append('pass',register_form.elements['pass'].value);
            data.append('cpass',register_form.elements['cpass'].value);
            data.append('profile',register_form.elements['profile'].files[0]);
            data.append('register','');

            var myModal = document.getElementById('registerModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            
            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/register.php",true);
        
            xhr.onload = function(){
                if(this.responseText == 'pass_mismatch'){
                    alert('error',"Password Mismatch!");
                }
                else if(this.responseText == 'email_already'){
                    alert('error',"Email is already registered!");
                }
                else if(this.responseText == 'phone_already'){
                    alert('error',"Phone number is already registered!");
                }
                else if(this.responseText == 'inv_img'){
                    alert('error',"Only JPG,WEBP & PNG images are allowed!");
                }
                else if(this.responseText == 'upd_failed'){
                    alert('error',"Image upload failed!");
                }
                else if(this.responseText == 'mail_failed'){
                    alert('error',"Cannot send confirmation email! Server down!");
                }
                else if(this.responseText == 'ins_failed'){
                    alert('error',"Registration failed!");
                }
                else{
                    alert('success',"Registration successful. Confirmation Link sent to email!");
                    register_form.reset();
                }
                
            }

            xhr.send(data);


        });


        let login_form = document.getElementById('login-form');

        login_form.addEventListener('submit',(e)=>{
            e.preventDefault();

            let data = new FormData();
            
            data.append('email_mob',login_form.elements['email_mob'].value);
            data.append('pass',login_form.elements['pass'].value);
            data.append('login','');

            var myModal = document.getElementById('loginModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            
            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/register.php",true);
        
            xhr.onload = function(){
                if(this.responseText == 'inv_email_mob'){
                    alert('error',"Invalid Email or Mobile Number!");
                }
                else if(this.responseText == 'not_verified'){
                    alert('error',"Email is not verified!");
                }
                else if(this.responseText == 'inactive'){
                    alert('error',"Account Suspended! Please contact Admin.");
                }
                else if(this.responseText == 'invalid_pass'){
                    alert('error',"Incorrect Password!");
                }
                else{
                    let fileurl = window.location.href.split('/').pop().split('?').shift();
                    if(fileurl == 'room_details.php'){
                        window.location = window.location.href;
                    }
                    else
                    {
                        window.location = window.location.pathname;
                    }
                }
                
            }

            xhr.send(data);


        });
        
        let forgot_form = document.getElementById('forgot-form');

        forgot_form.addEventListener('submit',(e)=>{
            e.preventDefault();

            let data = new FormData();
            
            data.append('email',forgot_form.elements['email'].value);
            data.append('forgot_pass','');

            var myModal = document.getElementById('forgotModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            
            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/forgotpassword.php",true);
        
            xhr.onload = function(){
                if(this.responseText == 'inv_email'){
                    alert('error',"Email not found!");
                }
                else if(this.responseText == 'not_verified'){
                    alert('error',"Email is not verified! Please contact Admin");
                }
                else if(this.responseText == 'inactive'){
                    alert('error',"Account Suspended! Please contact Admin.");
                }
                else if(this.responseText == 'mail_failed'){
                    alert('error',"Cannot send email. Server Down!");
                }
                else if(this.responseText == 'upd_failed'){
                    alert('error',"Server Down! Try again later!");
                }
                else{
                    alert('success',"Reset link sent to email!");
                    forgot_form.reset();
                }
                
            }

            xhr.send(data);


        });

        function checkLoginToBook(status,room_id){
            if(status){
                window.location.href='confirm_booking.php?id='+room_id;
            }
            else{
                alert('error','Please login to book room!')
            }
        }

    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   

</body>
</html>