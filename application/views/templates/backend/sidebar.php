<!-- Sidebar Admin -->
<?php if ($this->ion_auth_model->user()->row()->id == 1) : ?>
    <!-- Admin -->
    <li class="back-btn">
        <div class="mobile-back text-right"><span>Back</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
    </li>
    <li class="sidebar-list"><a class="nav-link sidebar-title sidebar-link <?= sidebar_active(3, 'dashboard') ?>" href="<?= base_admin('dashboard') ?>"><i data-feather="home"></i><span>Dashboard</span></a></li>

    <li class="sidebar-main-title">
        <div>
            <h6>Menu Data</h6>
        </div>
    </li>
    <li class="sidebar-list"><a class="nav-link sidebar-title sidebar-link <?= sidebar_active(3, 'mahasiswa') ?>" href="<?= base_admin('mahasiswa') ?>"><i data-feather="book-open"></i><span>Mahasiswa</span></a></li>

    <li class="sidebar-main-title">
        <div>
            <h6>Referensi</h6>
        </div>
    </li>
    <li class="sidebar-list"><a class="nav-link sidebar-title sidebar-link <?= sidebar_active(3, 'prodi') ?>" href="<?= base_admin('prodi') ?>"><i data-feather="bookmark"></i><span>Program Studi</span></a></li>
    <li class="sidebar-list"><a class="nav-link sidebar-title sidebar-link <?= sidebar_active(3, 'fakultas') ?>" href="<?= base_admin('fakultas') ?>"><i data-feather="bookmark"></i><span>Fakultas</span></a></li>

    <li class="sidebar-main-title">
        <div>
            <h6>Manajemen</h6>
        </div>
    </li>
    <li class="sidebar-list"><a class="nav-link sidebar-title sidebar-link <?= sidebar_active(1, 'auth') ?>" href="<?= base_auth() ?>"><i data-feather="users"></i><span>User</span></a></li>
<?php endif ?>

<!-- Sidebar ... -->
<?php if ($this->ion_auth_model->user()->row()->id == 2) : ?>

<?php endif ?>
<!-- CONTOH SIDEBAR -->

<!-- <li class="sidebar-main-title">
    <div>
        <h6 class="lan-1">General</h6>
        <p class="lan-2">Dashboards,widgets &amp; layout.</p>
    </div>
</li> -->
<!-- <li class="sidebar-list"><a class="nav-link sidebar-title" href="#"><i data-feather=""></i><span>...</span></a>
    <ul class="sidebar-submenu">
        <li><a class="submenu-title" href="#">...<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span></a>
            <ul class="nav-sub-childmenu submenu-content">
                <li><a href="#">...</a></li>
                <li><a href="#">...</a></li>
            </ul>
        </li>
    </ul>
</li> -->
<!-- <li class="sidebar-list"><a class="nav-link sidebar-title" href="#"><i data-feather=""></i><span>...</span></a></li> -->