<?php
    // COLLEGE DASHBOARD PAGE
    ob_start();
    require_once("../../db_connect.php");

    mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

    //-------CURRENT DATE AND TIME TO FEED---------//
    date_default_timezone_set('Asia/Kolkata');
    $current_date = date('Y-m-d H:i:s');
    $print_date = date('d-M-Y H:ia');
?>

<nav id="sidebar" class="sidebar no_print">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="./">
            <!-- <img src="../assets/img/wealthride_text.jpg" style="width: 100%;" class="rounded"> -->
            <span class="align-middle me-3"><?php echo $name; ?></span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Dashboard
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="./">
                    <i class="fa-solid fa-home fa-xl" style="color: #3f80ea !important;"></i>
                    Home
                </a>
            </li>

            <li class="sidebar-header">
                Members
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="view_member_total.php">
                    <i class="fa-solid fa-users fa-xl" style="color: #3f80ea !important;"></i>
                    All Members
                </a>
            </li>
            <li class="sidebar-item">
                <a data-bs-target="#Members" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="fa-solid fa-users fa-xl" style="color: #3f80ea !important;"></i>
                    KYC
                </a>
                <ul id="Members" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_member.php?type=<?php echo base64_encode(json_encode("kyc")); ?>">
                            <i class="fa-solid fa-users fa-xl" style="color: #3f80ea !important;"></i>
                            Pending
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_member.php?type=<?php echo base64_encode(json_encode("kyc_done")); ?>">
                            <i class="fa-solid fa-users fa-xl" style="color: #3f80ea !important;"></i>
                            Approved
                        </a>
                    </li>
                    <!-- <li class="sidebar-item">
                        <a class="sidebar-link" href="view_member.php?type=<?php echo base64_encode(json_encode("renewal")); ?>">
                            <i class="fa-solid fa-users fa-xl" style="color: #3f80ea !important;"></i>
                            Renewal Pending
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_member.php?type=<?php echo base64_encode(json_encode("upgrade")); ?>">
                            <i class="fa-solid fa-users fa-xl" style="color: #3f80ea !important;"></i>
                            Upgrade Pending
                        </a>
                    </li> -->
                </ul>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="view_member.php?type=<?php echo base64_encode(json_encode("inactive")); ?>">
                    <i class="fa-solid fa-users fa-xl" style="color: #3f80ea !important;"></i>
                    Blocked Members
                </a>
            </li>

            <li class="sidebar-header">
                Finance
            </li>
            <li class="sidebar-item">
                <a data-bs-target="#Finance" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                    Investments
                </a>
                <ul id="Finance" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_transaction_pending.php">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            Pending
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_transaction.php">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            By Member
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_transaction.php?type=<?php echo base64_encode(json_encode("superwallet")); ?>">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            By Superwallet
                        </a>
                    </li>


                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_transaction.php?type=<?php echo base64_encode(json_encode("admin_credit")); ?>">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            By Admin
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="si debar-link" href="package_control.php">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            Package Control
                        </a>
                    </li>
                    <!-- <li class="sidebar-item">
                        <a class="sidebar-link" href="view_transaction_pending.php?type=<?php echo base64_encode(json_encode("fresh")); ?>">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            Pending Joining
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_transaction_pending.php?type=<?php echo base64_encode(json_encode("renewal")); ?>">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            Pending Renewal
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_transaction_pending.php?type=<?php echo base64_encode(json_encode("upgrade")); ?>">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            Pending Upgrade
                        </a>
                    </li> -->
                </ul>
            </li>
            
            <li class="sidebar-item">
                <a data-bs-target="#SuperWallet" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                    SuperWallet
                </a>
                <ul id="SuperWallet" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                    <li class="sidebar-item mb-0">
                        <a class="sidebar-link" href="view_wallet_transaction.php">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            To Others
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_wallet_transaction.php?type=<?php echo base64_encode(json_encode("credit")); ?>">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            To Self
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item">
                <a data-bs-target="#Funds" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                    Funds +/- Summary
                </a>
                <ul id="Funds" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                    <li class="sidebar-item mb-0">
                        <a class="sidebar-link" href="view_fund_summary.php?type=<?php echo base64_encode(json_encode("admin_roi")); ?>">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            ROI Wallet
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_fund_summary.php?type=<?php echo base64_encode(json_encode("admin_commission")); ?>">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            Commission Wallet
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_fund_summary.php?type=<?php echo base64_encode(json_encode("admin_investment")); ?>">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            Investment Wallet
                        </a>
                    </li>
                </ul>
            </li>


            <li class="sidebar-header">
                Withdrawal
            </li>
            <li class="sidebar-item">
                <a data-bs-target="#Withdrawal" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                    Withdrawal
                </a>
                <ul id="Withdrawal" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">

                <li class="sidebar-item mb-0">
                        <a class="sidebar-link" href="do_withdrawal.php">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            Do User Withdrawals
                        </a>
                    </li>


                    <li class="sidebar-item mb-0">
                        <a class="sidebar-link" href="view_withdrawal_pending.php">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            Pending Withdrawals
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_withdrawal.php">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            All Withdrawal
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="tds_history.php">
                            <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                            TDS History
                        </a>
                    </li>
                </ul>
            </li>

            <!-- <li class="sidebar-item">
                <a class="sidebar-link" href="view_withdrawal.php">
                    <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                    All Withdrawal
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="view_withdrawal_pending.php">
                    <i class="fa-solid fa-indian-rupee fa-xl" style="color: #3f80ea !important;"></i>
                    Pending Withdrawals
                </a>
            </li> -->

            <li class="sidebar-header">
                Admin
            </li>
            <li class="sidebar-item">
                <a data-bs-target="#Dashboard" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="fa-solid fa-dashboard fa-xl" style="color: #3f80ea !important;"></i>
                    Member Dashboard
                </a>
                <ul id="Dashboard" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_roi_calendar.php">
                            <i class="fa-solid fa-list fa-xl" style="color: #3f80ea !important;"></i>
                            ROI Calendar
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="view_notification.php">
                            <i class="fa-solid fa-bell fa-xl" style="color: #3f80ea !important;"></i>
                            Notifications
                        </a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="view_bankinfo.php">
                    <i class="fa-solid fa-stream fa-xl" style="color: #3f80ea !important;"></i>
                    Bank Information
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="view_service.php">
                    <i class="fa-solid fa-stream fa-xl" style="color: #3f80ea !important;"></i>
                    Services
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="maintenance.php">
                    <i class="fa-solid fa-mortar-pestle fa-xl" style="color: #3f80ea !important;"></i>
                    Maintenance
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="view_leads.php">
                    <i class="fa-solid fa-message fa-xl" style="color: #3f80ea !important;"></i>
                    Leads/Messages
                </a>
            </li>
        </ul>
    </div>
</nav>