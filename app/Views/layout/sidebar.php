      <!-- Main Sidebar Container -->
      <!--<aside class="main-sidebar sidebar-bg-dark sidebar-color-primary shadow">-->
      <?php helper('settings');
      $seg = segment()->uri->getSegment(1) ?>
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
                <a href="<?= base_url() ?>" class="nav-link <?= $seg ? '' : 'active' ?> ">
                  <i class="nav-icon fa fa-desktop"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              <?php if (session()->get('username') == 'admin') : ?>
                <li class="nav-item <?= $seg == 'userbiodata' || $seg == 'user' ? 'menu-open menu-is-open' : '' ?>">
                  <a href="#" class="nav-link ">
                    <i class="nav-icon fa fa-user"></i>
                    <p>
                      Users
                      <i class="end fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url('userbiodata') ?>" class="nav-link <?= $seg == 'userbiodata' ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>User Biodata</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url('user') ?>" class="nav-link <?= $seg == 'user' ? 'active' : '' ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>User</p>
                      </a>
                    </li>
                  </ul>
                </li>
              <li class="nav-item">
                <a href="<?= base_url('toko') ?>" class="nav-link <?= $seg == 'toko' ? 'active' : '' ?>">
                  <i class="nav-icon fa fa-store"></i>
                  <p class="text">Toko</p>
                </a>
              </li>
              <?php endif; ?>
              <li class="nav-item">
                <a href="<?= base_url('alamatkirim') ?>" class="nav-link <?= $seg == 'alamatkirim' ? 'active' : '' ?>">
                  <i class="nav-icon fa fa-map-location-dot"></i>
                  <p class="text">Alamat</p>
                </a>
              </li>
              <li class="nav-item <?= $seg == 'product' || $seg == 'kategori' ? 'menu-open menu-is-open' : '' ?>">
                <a href="#" class="nav-link ">
                  <i class="nav-icon fas fa-archive"></i>
                  <p>
                    Produk
                    <i class="end fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= base_url('kategori') ?>" class="nav-link <?= $seg == 'kategori' ? 'active' : '' ?>">
                      <i class="nav-icon far fa-circle"></i>
                      <p>Kategori</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= base_url('product') ?>" class="nav-link <?= $seg == 'product' ? 'active' : '' ?>">
                      <i class="nav-icon far fa-circle"></i>
                      <p>Produk</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('keranjang') ?>" class="nav-link <?= $seg == 'keranjang' ? 'active' : '' ?>">
                  <i class="nav-icon fa fa-cart-shopping"></i>
                  <p class="text">Keranjang</p>
                </a>
            </li>
              <li class="nav-item">
                <a href="<?= base_url('order') ?>" class="nav-link <?= $seg == 'order' ? 'active' : '' ?>">
                  <i class="nav-icon fa fa-bag-shopping"></i>
                  <p class="text">Pemesanan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('pengiriman') ?>" class="nav-link <?= $seg == 'pengiriman' ? 'active' : '' ?>">
                  <i class="nav-icon fa fa-truck"></i>
                  <p class="text">Pengiriman</p>
                </a>
              </li>
              <?php if (session()->get('username') == 'admin') : ?>
              
              <li class="nav-item">
                <a href="<?= base_url('rekening') ?>" class="nav-link <?= $seg == 'rekening' ? 'active' : '' ?>">
                  <i class="nav-icon fa fa-credit-card"></i>
                  <p class="text">Referensi Rekening</p>
                </a>
              </li>
              <?php endif;?>
              <li class="nav-item">
                <a href="<?= base_url('report') ?>" class="nav-link <?= $seg == 'report' ? 'active' : '' ?>">
                  <i class="nav-icon fa fa-print"></i>
                  <p class="text">Laporan</p>
                </a>
              </li>

          </nav>
        </div>
        <!-- /.sidebar -->
      </aside>