<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="MSU Admin Dashboard" />
    <meta name="author" content="Marinduque State University" />
    <title>BMS Admin Dashboard</title>
    <link href="../../css/style.min.css" rel="stylesheet" />
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/button-fixes.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <script src="../../js/all.js" crossorigin="anonymous"></script>
    <style>
        /* Peach/Brown Color Scheme - Applied System-wide */
        :root {
            --primary-color: #451429;
            --primary-light: #FFBE98;
            --primary-medium: #FFBE98;
            --secondary-color: #FFA036;
            --accent-color: #C95E58;
            --dark-accent: #451429;
        }

        .sb-topnav {
            background-color: #451429 !important;
        }
        
        .modal-title {
            color: #451429;
        }
        
        /* Primary Buttons */
        .bg-primary {
            background-color: #451429 !important;
        }
        
        .btn-primary {
            background-color: #451429;
            border-color: #451429;
        }
        
        .btn-primary:hover {
            background-color: #5a1a35;
            border-color: #5a1a35;
        }
        
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #5a1a35 !important;
            border-color: #5a1a35 !important;
        }
        
        /* Info/Secondary Buttons and Cards */
        .bg-info {
            background-color: #FFA036 !important;
        }
        
        .btn-info {
            background-color: #FFA036;
            border-color: #FFA036;
            color: white;
        }
        
        .btn-info:hover {
            background-color: #e68a1f;
            border-color: #e68a1f;
        }
        
        /* Success Buttons and Cards */
        .bg-success {
            background-color: #FFBE98 !important;
            color: #333 !important;
        }
        
        .btn-success {
            background-color: #FFBE98;
            border-color: #FFBE98;
            color: #333;
        }
        
        .btn-success:hover {
            background-color: #ffa878;
            border-color: #ffa878;
        }
        
        /* Warning Buttons and Cards */
        .bg-warning {
            background-color: #FEDCAC !important;
            color: #333 !important;
        }
        
        .btn-warning {
            background-color: #FEDCAC;
            border-color: #FEDCAC;
            color: #333;
        }
        
        .btn-warning:hover {
            background-color: #fec78c;
            border-color: #fec78c;
        }
        
        /* Danger/Accent */
        .bg-danger {
            background-color: #C95E58 !important;
        }
        
        .btn-danger {
            background-color: #C95E58;
            border-color: #C95E58;
        }
        
        .btn-danger:hover {
            background-color: #b04a44;
            border-color: #b04a44;
        }
        
        /* Links */
        a {
            color: #451429;
        }
        
        a:hover {
            color: #5a1a35;
        }
        
        /* Sidebar */
        .sb-sidenav-dark {
            background-color: #2c2c2c !important;
        }
        
        .sb-sidenav-dark .sb-sidenav-menu .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover {
            background-color: rgba(255, 190, 152, 0.1);
            color: #FFBE98;
        }
        
        .sb-sidenav-dark .sb-sidenav-menu .nav-link.active {
            background-color: rgba(69, 20, 41, 0.3);
            color: #FFBE98;
        }
        
        /* Cards */
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(69, 20, 41, 0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(69, 20, 41, 0.075);
        }
        
        .card-header.bg-primary,
        .card-header.text-white {
            background-color: #451429 !important;
        }
        
        /* Tables */
        .table thead th {
            border-bottom: 2px solid #451429;
        }
        
        /* Breadcrumbs */
        .breadcrumb-item.active {
            color: #451429;
        }
        
        .breadcrumb-item a {
            color: #FFA036;
        }
        
        /* Badges */
        .badge.bg-primary {
            background-color: #451429 !important;
        }
        
        .badge.bg-info {
            background-color: #FFA036 !important;
        }
        
        .badge.bg-success {
            background-color: #FFBE98 !important;
            color: #333 !important;
        }
        
        /* Modals */
        .modal-header.bg-primary {
            background-color: #451429 !important;
        }
        
        .modal-header.bg-info {
            background-color: #FFA036 !important;
        }
        
        .modal-header.bg-success {
            background-color: #FFBE98 !important;
            color: #333 !important;
        }
        
        .modal-header.bg-danger {
            background-color: #C95E58 !important;
        }
        
        /* Progress Bars */
        .progress-bar {
            background-color: #451429;
        }
        
        /* Form Focus */
        .form-control:focus {
            border-color: #FFBE98;
            box-shadow: 0 0 0 0.2rem rgba(255, 190, 152, 0.25);
        }
        
        /* Pagination */
        .page-item.active .page-link {
            background-color: #451429;
            border-color: #451429;
        }
        
        .page-link {
            color: #451429;
        }
        
        .page-link:hover {
            color: #5a1a35;
        }
        
        .sb-nav-fixed #layoutSidenav #layoutSidenav_nav {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(69, 20, 41, 0.15);
        }
    </style>
</head>