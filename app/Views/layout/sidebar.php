      <!-- Main Sidebar Container -->
<!--<aside class="main-sidebar sidebar-bg-dark sidebar-color-primary shadow">-->
<?php helper('settings'); $seg = segment()->uri->getSegment(1)?>
<aside class="main-sidebar sidebar-bg-dark  sidebar-color-primary shadow">
  <div class="brand-container">
    <a href="javascript:;" class="brand-link">
      <img src="<?= base_url('asset/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="brand-image opacity-80 shadow">
      <span class="brand-text fw-light">Toko Buku Bekas</span>
    </a>
    <a class="pushmenu mx-1" data-lte-toggle="sidebar-mini" href="javascript:;" role="button"><i class="fas fa-angle-double-left"></i></a>
  </div>
  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-2">
      <!-- Sidebar Menu -->
      <ul class="nav nav-pills nav-sidebar flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="<?= base_url()?>" class="nav-link active">
            <i class="nav-icon fas fa-circle"></i>
            <p>
              Dashboard
              <!-- <i class="end fas fa-angle-left"></i> -->
            </p>
          </a>

        </li>
        <li class="nav-item <?= $seg == 'userbiodata' || $seg == 'user' ? 'menu-open menu-is-open':''?>">
          <a href="javascript:;" class="nav-link ">
            <i class="nav-icon fas fa-circle"></i>
            <p>
              Users
              <i class="end fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('userbiodata')?>" class="nav-link <?= $seg == 'userbiodata' ? 'active':''?>">
                <i class="nav-icon far fa-circle"></i>
                <p>User Biodata</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link ">
                <i class="nav-icon far fa-circle"></i>
                <p>User</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item ">
          <a href="javascript:;" class="nav-link ">
            <i class="nav-icon fas fa-circle"></i>
            <p>
              Layout Options
              <span class="badge bg-info float-end me-3">6</span>
              <i class="end fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="./pages/layout/fixed-sidebar.html" class="nav-link ">
                <i class="nav-icon far fa-circle"></i>
                <p>Fixed Sidebar</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">MULTI LEVEL EXAMPLE</li>
        <li class="nav-item">
          <a href="javascript:;" class="nav-link">
            <i class="nav-icon fas fa-circle"></i>
            <p>Level 1</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="javascript:;" class="nav-link">
            <i class="nav-icon fas fa-circle"></i>
            <p>
              Level 1
              <i class="end fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="javascript:;" class="nav-link">
                <i class="nav-icon far fa-circle"></i>
                <p>Level 2</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="javascript:;" class="nav-link">
                <i class="nav-icon far fa-circle"></i>
                <p>
                  Level 2
                  <i class="end fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="javascript:;" class="nav-link">
                    <i class="nav-icon far fa-dot-circle"></i>
                    <p>Level 3</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="javascript:;" class="nav-link">
                    <i class="nav-icon far fa-dot-circle"></i>
                    <p>Level 3</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="javascript:;" class="nav-link">
                    <i class="nav-icon far fa-dot-circle"></i>
                    <p>Level 3</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="javascript:;" class="nav-link">
                <i class="nav-icon far fa-circle"></i>
                <p>Level 2</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="javascript:;" class="nav-link">
            <i class="nav-icon fas fa-circle"></i>
            <p>Level 1</p>
          </a>
        </li>
        <li class="nav-header">LABELS</li>
        <li class="nav-item">
          <a href="javascript:;" class="nav-link">
            <i class="nav-icon far fa-circle text-danger"></i>
            <p class="text">Important</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="javascript:;" class="nav-link">
            <i class="nav-icon far fa-circle text-warning"></i>
            <p>Warning</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="javascript:;" class="nav-link">
            <i class="nav-icon far fa-circle text-info"></i>
            <p>Informational</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
  <!-- /.sidebar -->
</aside>