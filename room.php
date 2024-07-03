<!doctype html>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title> ROOMS</title>
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

        $checkin_default="";
        $checkout_default="";
        $adult_default="";
        $children_default="";

        if(isset($_GET['check_availability']))
        {
            $frm_data = filteration($_GET);

            $checkin_default=$frm_data['checkin'];
            $checkout_default=$frm_data['checkout'];
            $adult_default=$frm_data['adult'];
            $children_default=$frm_data['children'];
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
                    <a class="nav-link active me-2" aria-current="page" href="room.php">Rooms</a>
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
                                    <img src="$path$_SESSION[profile]" style="width: 25px; height:25px;" class="me-1 rounded-circle">    
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




    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR ROOMS</h2>
        <div class="h-line bg-dark"></div>
    </div>    

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">FILTERS</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                            <!-- check availability -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>CHECK AVAILABILITY</span>
                                    <button id="chk_avail_btn" onclick="chk_avail_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <label class="form-label">Check-in</label>
                                <input type="date" class="form-control shadow-none mb-3" value="<?php echo $checkin_default?>" id="checkin" onchange="chk_avail_filter()">
                                <label class="form-label">Check-out</label>
                                <input type="date" class="form-control shadow-none" value="<?php echo $checkout_default?>" id="checkout" onchange="chk_avail_filter()">
                            </div>

                            <!-- Facilities -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>FACILITIES</span>
                                    <button id="facilities_btn" onclick="facilities_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <?php 
                                
                                    $facilities_q = selectAll('facilities');
                                    while($row = mysqli_fetch_assoc($facilities_q))
                                    {
                                        echo<<<facilities
                                        
                                        <div class="mb-2">
                                            <input type="checkbox" onclick="fetch_rooms()" name="facilities" value="$row[id]" class="form-check-input shadow-none me-1" id="$row[id]">
                                            <label class="form-check-label" for="$row[id]">$row[name]</label>
                                        </div>

                                        facilities;
                                    }

                                ?>
                            </div>

                            <!-- Guests -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>GUESTS</span>
                                    <button id="guests_btn" onclick="guests_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">Adults</label>
                                        <input type="number" min="1" id="adults" value="<?php echo $adult_default?>" oninput="guests_filter()" class="form-control shadow-none">
                                    </div>
                                    <div>
                                        <label class="form-label">Children</label>
                                        <input type="number" min="1" id="children" value="<?php echo $children_default?>" oninput="guests_filter()" class="form-control shadow-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9 col-md-12 px-4" id="rooms-data">
            </div>

        </div>
    </div>




    <script>

        let rooms_data = document.getElementById('rooms-data');

        let checkin = document.getElementById('checkin');
        let checkout = document.getElementById('checkout');
        let chk_avail_btn = document.getElementById('chk_avail_btn');

        let adults = document.getElementById('adults');
        let children = document.getElementById('children');
        let guests_btn = document.getElementById('guests_btn');

        let facilities_btn = document.getElementById('facilities_btn');


        function fetch_rooms()
        {
            let chk_avail = JSON.stringify({
                checkin: checkin.value,
                checkout: checkout.value
            });

            let guests = JSON.stringify({
                adults: adults.value,
                children: children.value
            });

            let facility_list = {"facilities":[]};

            let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
            if(get_facilities.length>0)
            {
                get_facilities.forEach((facility)=>{
                    facility_list.facilities.push(facility.value);
                });
                facilities_btn.classList.remove('d-none');
            }
            else{
                facilities_btn.classList.add('d-none');
            }

            facility_list = JSON.stringify(facility_list);

            let xhr = new XMLHttpRequest();
            xhr.open("GET","ajax/rooms.php?fetch_rooms&chk_avail="+chk_avail+"&guests="+guests+"&facility_list="+facility_list,true);

            xhr.onprogress = function(){
                rooms_data.innerHTML = `<div class="spinner-border text-info mb-3 d-block mx-auto" id="loader" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>`;
            }

            xhr.onload = function(){
                rooms_data.innerHTML = this.responseText;
            }

            xhr.send();
        }

        function chk_avail_filter()
        {
            if(checkin.value!='' && checkout.value!=''){
                fetch_rooms();
                chk_avail_btn.classList.remove('d-none');
            }
        }

        function chk_avail_clear()
        {
            checkin.value='';
            checkout.value='';
            chk_avail_btn.classList.add('d-none');
            fetch_rooms();     
        }

        function guests_filter(){
            if(adults.value>0 || children.value>0){
                fetch_rooms();
                guests_btn.classList.remove('d-none');
            }
        }

        function guests_clear(){
            adults.value='';
            children.value='';
            guests_btn.classList.add('d-none');
            fetch_rooms();
        }

        function facilities_clear(){
            let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
            get_facilities.forEach((facility)=>{
                facility.checked=false;
            });
            facilities_btn.classList.add('d-none');
            fetch_rooms();
        }

        window.onload = function(){
            fetch_rooms();
        }


    </script>









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