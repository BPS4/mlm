  <meta name="robots" content="noindex">
  <!--To prevent only Google web crawlers from indexing a page:-->
  <meta name="googlebot" content="noindex">

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Admin Dashboard For Admin">
  <meta name="author" content="Maxizone">

  <meta name="description" content="" />
  <meta name="keyword" content="">
  <meta name="theme-color" content="#002147">

  <link rel="stylesheet" type="text/css" href="../../assets/gradient.css">

  <link rel="shortcut icon" href="../../assets/images/favicon.svg" type="image/x-icon">

  <link href="../assets/css/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

  <!-- Choose your prefered color scheme -->
  <!-- <link href="css/light.css" rel="stylesheet"> -->
  <!-- <link href="css/dark.css" rel="stylesheet"> -->

  <!-- BEGIN SETTINGS -->
  <!-- Remove this after purchasing -->
  <link class="js-stylesheet" href="../assets/css/light.css?v=1.0" rel="stylesheet">
  <!-- <script src="js/settings.js"></script> -->
  <!-- END SETTINGS -->

  <!-- FONT AWESOME ALL ICONS -->
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

  <!--
    HOW TO USE: 
    data-theme: default (default), dark, light
    data-layout: fluid (default), boxed
    data-sidebar-position: left (default), right
    data-sidebar-behavior: sticky (default), fixed, compact
  -->

  <style>
    .dataTables_length {
      margin-bottom: 10px;
    }

    .btn-group-vertical>.btn,
    .btn-group>.btn {
      margin: 5px;
    }

    @media print {

      div.dt-buttons,
      .no_print {
        display: none;
      }

      /*------------FOR HIDING URL OF HREF IN PRINTING-------------*/
      a[href]:after {
        content: none !important;
      }

      /* .section_to_print a[href]:before { display:none; visibility:hidden; }*/
      @page {
        size: A4 landscape;
        /*//auto, portrait, landscape or length (2 parameters width and height. sets both equal if only one is provided. % values not allowed.)*/

        margin-top: 1cm;
        margin-bottom: 1cm;
      }
    }
  </style>

  <style>
    #datatables-column-search-text-inputs,
    .card {
      display: none;
    }

    .card {
      /* text-align: center; */
    }

    /* Loader CSS */
    #loader {
      /* position: absolute;
          left: 50%;
          top: 50%; */
      z-index: 1;

      /* margin: -76px 0 0 -76px; */
      /* border: 16px solid #f3f3f3;
          border-radius: 50%; */

      /* CENTER THE ELEMENT */
      display: block;
      margin-left: auto;
      margin-right: auto;
      /* CENTER THE ELEMENT */

      border: 4px solid yellow;
      border-radius: 50%;
      border-top: 4px solid blue;
      border-right: 4px solid green;
      border-bottom: 4px solid red;
      width: 30px;
      height: 30px;
      -webkit-animation: spin 2s linear infinite;
      animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
      0% {
        -webkit-transform: rotate(0deg);
      }

      100% {
        -webkit-transform: rotate(360deg);
      }
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    /* Loader CSS */

    /* Add animation to "CARD" */
    .animate-bottom,
    .card {
      position: relative;
      -webkit-animation-name: animatebottom;
      -webkit-animation-duration: 1s;
      animation-name: animatebottom;
      animation-duration: 1s
    }

    @-webkit-keyframes animatebottom {
      from {
        bottom: -100px;
        opacity: 0
      }

      to {
        bottom: 0px;
        opacity: 1
      }
    }

    @keyframes animatebottom {
      from {
        bottom: -100px;
        opacity: 0
      }

      to {
        bottom: 0;
        opacity: 1
      }
    }

    .show-pointer {
      cursor: pointer;
    }

    /* Align Items Center */
      .align-items-center {
        display: flex !important;
        justify-content: center !important;
      }
    /* Align Items Center */

    .text-justify {
      text-align: justify !important;
    }

    .text-italic {
      font-style: italic;
    }

    .text-bold {
      font-weight: bold;
    }

    #checkNoRobot+label {
      color: red;
    }

    #checkNoRobot:checked+label {
      color: green !important;

    }

    .text-readonly {
      background-color: #EFF2F6;
      font-weight: bold;
      font-style: italic;
      color: green;
    }

    .form-label-font {
      font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }

    @media (min-width: 576px) {
      .ms-md-100 {
        margin-left: -100px !important;
      }
    }

    .no-wrap {
      white-space: nowrap !important;
    }
  </style>
  
  <style>
    tfoot {
      display: none !important;
    }
  </style>