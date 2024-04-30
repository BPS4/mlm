<?php
    if (isset($submit_contact_form)) {
        $name = mysqli_escape_string($conn, $_POST['name']);
        $mobile = mysqli_escape_string($conn, $_POST['mobile']);
        $email = mysqli_escape_string($conn, $_POST['email']);
        $message = mysqli_escape_string($conn, $_POST['message']);
        $query_insert = "INSERT INTO `leads`(`source`, `category`, `name`, `mobile`, `email`, `message`, `create_date`) VALUES ('contact', 'contact', '$name', '$mobile', '$email', '$message', '$current_date')";
        
        if(mysqli_query($conn, $query_insert)) {
            $msg = "Thank you! We will contact you soon.";
            $msg_type = "success";
            stop_form_resubmit();
        }
    }
?>

<a href="https://wa.me//919354329813" target="_blank" title="Whatsapp" class="" style="position: fixed; left:10px; bottom:40px; z-index:99;">
    <i class="lni-whatsapp fa-lg glow" style="display:flex; justify-content:center; align-items:center; text-align:center;width:50px; height:50px; font-size: 2rem;"></i> 
</a>

    <!--====== FOOTER PART START ======-->
        <footer id="footer" class="footer-area pt-120">
            <div class="container">
                <div class="subscribe-area wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s" id="contact">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="subscribe-content mt-45">
                                <h2 class="subscribe-title">Contact us <span>for your queries</span></h2>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="subscribe-formm mt-50">
                                <form action="" method="post">
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <input type="text" name="name" id="name" placeholder="Full Name" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <input type="mobile" name="mobile" id="mobile" placeholder="Mobile No." class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <input type="email" name="email" id="email" placeholder="Working Email ID" class="form-control" required>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <textarea name="message" id="message" rows="3" placeholder="Enter Your Brief Message" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                        <input type="submit" value="Submit" class="main-btn" name="submit_contact_form">
                                    </fieldset>
                                    <!-- <button type="submit" class="main-btn" name="submit_contact_form">Submit</button> -->
                                </form>
                            </div>
                        </div>
                    </div> <!-- row -->
                </div> <!-- subscribe area -->
                <div class="footer-widget pb-100">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-8">
                            <div class="footer-about mt-50 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                                <a class="logo" href="#">
                                    <!-- <img src="assets/images/logo.svg" alt="logo"> -->
                                    <h2 class="text-white">Maxizone</h2>
                                </a>
                                <p class="text">
                                    This platform is designed keeping a thought in mind that all the services we are offering should reach our members at its ease without any hassle in the process.
                                </p>
                                <ul class="social">
                                    <li><a href="#"><i class="lni-facebook-filled"></i></a></li>
                                    <li><a href="#"><i class="lni-twitter-filled"></i></a></li>
                                    <li><a href="#"><i class="lni-instagram-filled"></i></a></li>
                                    <li><a href="#"><i class="lni-linkedin-original"></i></a></li>
                                </ul>
                            </div> <!-- footer about -->
                        </div>
                        <div class="col-lg-4 col-md-7 col-sm-7">
                            <div class="footer-link mt-50">
                                <div class="link-wrapper wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s">
                                    <div class="footer-title">
                                        <h4 class="title">Quick Link</h4>
                                    </div>
                                    <ul class="link">
                                        <li><a href="./">Home</a></li>
                                        <li><a href="#about">About</a></li>
                                        <li><a href="#services">Services</a></li>
                                        <li><a href="#contact">Contact</a></li>
                                    </ul>
                                </div>
                                <!-- <div class="link-wrapper wow fadeIn" data-wow-duration="1s" data-wow-delay="0.6s">
                                    <div class="footer-title">
                                        <h4 class="title">Resources</h4>
                                    </div>
                                    <ul class="link">
                                        <li><a href="#">Home</a></li>
                                        <li><a href="#">Page</a></li>
                                        <li><a href="#">Portfolio</a></li>
                                        <li><a href="#">Blog</a></li>
                                        <li><a href="#">Contact</a></li>
                                    </ul>
                                </div> -->
                            </div> <!-- footer link -->
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-5">
                            <div class="footer-contact mt-50 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s">
                                <div class="footer-title">
                                    <h4 class="title">Contact Us</h4>
                                </div>
                                <ul class="contact">
                                    <li>+91-8888888888</li>
                                    <li>mtclub@gmail.com</li>
                                    <li>www.mtclub.in</li>
                                    <li>Delhi, New Delhi <br>India</li>
                                </ul>
                            </div> <!-- footer contact -->
                        </div>
                    </div> <!-- row -->
                </div> <!-- footer widget -->
                <div class="footer-copyright">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="copyright d-sm-flex justify-content-between">
                                <div class="copyright-content">
                                    <p class="text">Copyright ©️ 2023 All Rights Reserved <a href="./" rel="nofollow">Maxizone</a></p>
                                </div> <!-- copyright content -->
                            </div> <!-- copyright -->
                        </div>
                    </div> <!-- row -->
                </div> <!-- footer copyright -->
            </div> <!-- container -->
            <div id="particles-2"></div>
        </footer>
    <!--====== FOOTER PART ENDS ======-->