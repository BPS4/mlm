<!-- Page Sidebar Start-->
<div class="sidebar-wrapper">
    <div>
        <div class="logo-wrapper">
            <a href="./">
                <h4>Maxizone</h4>
                <!-- <img class="img-fluid for-light" src="assets/images/logo/small-logo.png" alt="">
                <img class="img-fluid for-dark" src="assets/images/logo/small-white-logo.png" alt=""> -->
            </a>
            <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="./">
                Maxizone
                <!-- <img class="img-fluid" src="assets/images/logo-icon.png" alt=""> -->
            </a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn">
                        <a href="./">
                            Maxizone
                            <!-- <img class="img-fluid" src="assets/images/logo-icon.png" alt=""> -->
                        </a>
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"> </i></div>
                    </li>
                    
                    <li class="sidebar-list text-muted">
                        <label>User Dashboard</label>
                    </li>
                    
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav active" href="./">
                            <!-- <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <g>
                                        <path d="M9.07861 16.1355H14.8936" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.3999 13.713C2.3999 8.082 3.0139 8.475 6.3189 5.41C7.7649 4.246 10.0149 2 11.9579 2C13.8999 2 16.1949 4.235 17.6539 5.41C20.9589 8.475 21.5719 8.082 21.5719 13.713C21.5719 22 19.6129 22 11.9859 22C4.3589 22 2.3999 22 2.3999 13.713Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </g>
                            </svg> -->
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="view_profile.php">
                            <i data-feather="user"></i>
                            <span>Profile</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="view_member.php">
                            <i data-feather="users"></i>
                            <span>Referred Members</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <!-- <a class="sidebar-link sidebar-title link-nav" href="../assets/M T Club.pdf" title="Download Maxizone User Manual"> -->
                        <a class="sidebar-link sidebar-title link-nav" href="javascript:void(0); alert('Feature will be available soon...');" title="Download Maxizone User Manual">
                            <i data-feather="download"></i>
                            <span>User Guide</span>
                        </a>
                    </li>

                    <li class="sidebar-list text-muted">
                        <label>Finance</label>
                    </li>
                    
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="view_investment.php">
                            <i class="icofont icofont-money"></i>
                            <span>My Investments</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="view_transaction.php">
                            <i class="icofont icofont-money"></i>
                            <span>All Income</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="view_transaction.php?type=<?php echo base64_encode(json_encode('roi')); ?>">
                            <i class="icofont icofont-money"></i>
                            <span>ROI on Investment</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="view_transaction.php?type=<?php echo base64_encode(json_encode('lc')); ?>">
                            <i class="icofont icofont-money"></i>
                            <span>Level Commission</span>
                        </a>
                    </li>


                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="view_transaction.php?type=<?php echo base64_encode(json_encode('sc')); ?>">
                            <i class="icofont icofont-money"></i>
                            <span>Sponsor Commission</span>
                        </a>
                    </li>

                    <li class="sidebar-list d-none">
                        <a class="sidebar-link sidebar-title link-nav" href="view_fixed_deposit.php">
                            <i class="icofont icofont-money"></i>
                            <span>Fixed Deposits</span>
                        </a>
                    </li>

                    <li class="sidebar-list text-muted">
                        <label>Withdrawal</label>
                    </li>

                   <!-- <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="view_superwallet.php">
                            <i class="icofont icofont-money"></i>
                            <span>Superwallet Txn</span>
                        </a>
                    </li> -->

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="view_withdrawal.php">
                            <i class="icofont icofont-money"></i>
                            <span>Withdrawal</span>
                        </a>
                    </li>
                </ul>
                <div class="sidebar-img-section d-none">
                    <div class="sidebar-img-content">
                        <img class="img-fluid" src="assets/images/side-bar.png" alt="">
                        <h4>Need Help ?</h4>
                        <a class="txt" href="mailto:mtclub01@yahoo.com">Write to Us: mtclub01@yahoo.com</a>
                        <a class="btn btn-secondary" href="mailto:mtclub01@yahoo.com">Write To Us</a>
                    </div>
                </div>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
<!-- Page Sidebar Ends-->