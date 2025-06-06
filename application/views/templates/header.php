<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/img/favicon.ico">


    <title><?= isset($title) ? $title : 'Admin Panel' ?></title> <!-- Title dinamis -->

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url(); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url(); ?>assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Moment.js -->
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <!-- Date Range Picker -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap CSS (jika belum ada) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Bundle JS (yang termasuk Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/maroon-theme.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">




</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <?php if ($this->session->userdata('role') === 'admin'): ?>
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url(); ?>">
                <?php endif; ?>
                <?php if ($this->session->userdata('role') !== 'admin'): ?>
                <a class="sidebar-brand d-flex align-items-center justify-content-center"
                    href="<?php echo base_url(); ?>">
                    <?php endif; ?>
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-laugh-wink"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">NAMUA <br> POS</div>
                </a>

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link text-white" href="<?php echo base_url('beranda'); ?>">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Interface
                </div>
                <!-- Nav Item -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/kasir') ?>">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>POS</span></a>
                </li>

                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item">

                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
                        aria-expanded="true" aria-controls="collapseLaporan">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Laporan</span>
                    </a>
                    <div id="collapseLaporan" class="collapse" aria-labelledby="headingLaporan"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="<?= site_url('/kasir/transaksi_pending') ?>"><i
                                    class="far fa-id-card"></i>
                                Transaksi Pending</a>
                            <a class="collapse-item" href="<?= site_url('/kasir/pesanan_terbayar') ?>"><i
                                    class="far fa-id-card"></i>
                                Pesanan Terbayar</a>
                            <a class="collapse-item" href="<?= site_url('/laporan/laporan_refund') ?>"><i
                                    class="far fa-id-card"></i>
                                Laporan Refund</a>
                            <a class="collapse-item" href="<?= site_url('/laporan/laporan_void') ?>"><i
                                    class="far fa-id-card"></i>
                                Laporan Void</a>

                            <a class="collapse-item" href="<?= site_url('/stok') ?>"><i class="far fa-id-card"></i> Stok
                                Bahan Baku</a>
                            <a class="collapse-item" href="<?= site_url('/stok/log') ?>"><i class="far fa-id-card"></i>
                                Log Stok Bahan Baku</a>
                            <a class="collapse-item" href="<?= site_url('/resep') ?>"><i class="far fa-id-card"></i>
                                Resep</a>
                            <a class="collapse-item" href="<?= site_url('/resep/input') ?>"><i
                                    class="far fa-id-card"></i> Input Resep</a>
                            <a class="collapse-item" href="<?= site_url('/kasir/riwayat_shift') ?>"><i
                                    class="far fa-id-card"></i> Riwayat Shift</a>

                        </div>
                    </div>
                </li>
                <!-- 
                <li class="nav-item">

                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePelanggan"
                        aria-expanded="true" aria-controls="collapsePelanggan">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Pelanggan</span>
                    </a>
                    <div id="collapsePelanggan" class="collapse" aria-labelledby="headingPelanggan"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="<?= site_url('/customer') ?>"><i class="far fa-id-card"></i>
                                Daftar Pelanggan</a>
                        </div>
                    </div>
                </li> -->


                <!-- <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProduk"
                        aria-expanded="true" aria-controls="collapseProduk">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Produk</span>
                    </a>
                    <div id="collapseProduk" class="collapse" aria-labelledby="headingProduk"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="<?= site_url('/buku_menu') ?>"><i class="far fa-id-card"></i>
                                Buku Menu</a>
                            <a class="collapse-item" href="<?= site_url('/divisi') ?>"><i class="fas fa-user-cog"></i>
                                Daftar Divisi</a>
                            <a class="collapse-item" href="<?= site_url('/kategori') ?>"><i class="fas fa-user-cog"></i>
                                Daftar Kategori</a>
                            <a class="collapse-item" href="<?= site_url('/produk') ?>"><i class="fas fa-user-cog"></i>
                                Daftar Produk</a>
                            <a class="collapse-item" href="<?= site_url('/extra') ?>"><i class="fas fa-user-cog"></i>
                                Produk Ekstra</a>
                            <a class="collapse-item" href="<?= site_url('/Produk Paket') ?>"><i
                                    class="fas fa-user-cog"></i>
                                Produk Paket</a>
                            <a class="collapse-item" href="<?= site_url('/resep') ?>"><i class="fas fa-user-cog"></i>
                                Master Resep</a>
                        </div>
                    </div>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePromo"
                        aria-expanded="true" aria-controls="collapsePromo">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Promo</span>
                    </a>
                    <div id="collapsePromo" class="collapse" aria-labelledby="headingPromo"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="<?= site_url('/Promo_voucher_auto') ?>"><i
                                    class="far fa-id-card"></i>
                                Promo</a>
                            <a class="collapse-item" href="<?= site_url('/stamp') ?>"><i class="far fa-id-card"></i>
                                Stamp</a>
                            <a class="collapse-item" href="<?= site_url('/voucher') ?>"><i class="fas fa-user-cog"></i>
                                Daftar voucher</a>
                            <a class="collapse-item" href="<?= site_url('/poin') ?>"><i class="fas fa-user-cog"></i>
                                Daftar Poin</a>
                        </div>
                    </div>
                </li> -->

                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSeven"
                        aria-expanded="true" aria-controls="collapseSeven">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <!-- <a class="collapse-item" href="<?= site_url('/kasir') ?>"><i class="far fa-id-card"></i>
                                POS</a>
                            <a class="collapse-item" href="<?= site_url('/produk') ?>"><i class="far fa-id-card"></i>
                                Daftar Produk</a>
                            <a class="collapse-item" href="<?= site_url('/divisi') ?>"><i class="fas fa-user-cog"></i>
                                Daftar Divisi</a>
                            <a class="collapse-item" href="<?= site_url('/kategori') ?>"><i class="fas fa-user-cog"></i>
                                Daftar Kategori</a>
                            <a class="collapse-item" href="<?= site_url('/voucher') ?>"><i class="fas fa-user-cog"></i>
                                Daftar Voucher</a>
                            <a class="collapse-item" href="<?= site_url('/poin') ?>"><i class="fas fa-user-cog"></i>
                                Daftar Poin</a> -->
                            <!-- <a class="collapse-item" href="<?= site_url('/setting') ?>"><i class="fas fa-user-cog"></i>
                                Pengaturan</a> -->
                            <a class="collapse-item" href="<?= site_url('/printer') ?>"><i class="fas fa-user-cog"></i>
                                Printer</a>
                        </div>
                    </div>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link" href="/sync_data/ambil_semua">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Sinkronisasi Server</span></a>
                </li> -->

                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/sync_data') ?>">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Sinkronisasi Data Server</span></a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="/retry_sync">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Retry Sync</span></a>
                </li>
                <li class="nav-item">

                    <a class="nav-link" href="<?= site_url('/auth/logout') ?>">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Logout</span></a>
                </li>


                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">

                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>

                <!-- Sidebar Message -->
                <div class="sidebar-card d-none d-lg-flex">
                    <img class="sidebar-card-illustration mb-2"
                        src="<?php echo base_url(); ?>assets/img/undraw_rocket.svg" alt="...">
                    <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features,
                        components, and more!</p>
                    <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to
                        Pro!</a>
                </div>

        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle"
                                            src="<?php echo base_url(); ?>assets/img/undraw_profile_1.svg" alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle"
                                            src="<?php echo base_url(); ?>assets/img/undraw_profile_2.svg" alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle"
                                            src="<?php echo base_url(); ?>assets/img/undraw_profile_3.svg" alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= $this->session->userdata('nama'); ?>
                                    <!-- Nama User -->
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="<?= base_url('uploads/' . $this->session->userdata('avatar')); ?>" width="30"
                                    height="30">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="/profil">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= site_url('auth/logout') ?>">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <div class="container-fluid">