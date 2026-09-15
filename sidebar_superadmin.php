<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
      <li class="nav-item">
        <a href="../home_superadmin/" class="nav-link <?= ($hal == 'beranda_admin') ? 'active' : '' ?>">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>Beranda</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="../supplier_superadmin/" class="nav-link <?= ($hal == 'supplier_superadmin') ? 'active' : '' ?>">
          <i class="nav-icon fas fa-truck"></i>
          <p>Data Supplier</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="../d_barang_superadmin/" class="nav-link <?= ($hal == 'barang_superadmin') ? 'active' : '' ?>">
          <i class="nav-icon fas fa-boxes"></i>
          <p>Data Barang</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="../d_barang_konsinyasi_superadmin/" class="nav-link <?= ($hal == 'barang_konsinyasi_superadmin') ? 'active' : '' ?>">
          <i class="nav-icon fas fa-boxes"></i>
          <p>Data Barang konsinyasi</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="../nota_beli_superadmin/" class="nav-link <?= ($hal == 'nota_beli_superadmin') ? 'active' : '' ?>">
          <i class="nav-icon fas fa-shopping-cart"></i>
          <p>Nota Beli</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="../nota_jual_superadmin/" class="nav-link <?= ($hal == 'nota_jual_superadmin') ? 'active' : '' ?>">
          <i class="nav-icon fas fa-cash-register"></i>
          <p>Nota Jual</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="../nota_konsinyasi_superadmin/" class="nav-link <?= ($hal == 'nota_konsinyasi_superadmin') ? 'active' : '' ?>">
          <i class="nav-icon fas fa-cash-register"></i>
          <p>Nota Konsinyasi</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="../ganti_pass_superadmin/" class="nav-link <?= ($hal == 'sandi_superadmin') ? 'active' : '' ?>">
          <i class="nav-icon fas fa-key"></i>
          <p>Ganti Password</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="../logout.php" class="nav-link">
          <i class="nav-icon fas fa-sign-out-alt"></i>
          <p>Keluar</p>
        </a>
      </li>
  </ul>
</nav>