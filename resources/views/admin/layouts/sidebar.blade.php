<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.html">Stisla</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
          </div>
          <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users') }}">
                    <i class="fas fa-users"></i> <span>Kelola Users</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.game-services*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.game-services.index') }}">
                    <i class="fas fa-gamepad"></i> <span>Layanan Game</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.game-account-fields*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.game-account-fields.index') }}">
                <i class="fas fa-id-card"></i> <span>Field Akun Game</span>
              </a>
            </li>

            <li class="{{ request()->routeIs('admin.prepaid-services*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.prepaid-services.index') }}">
                    <i class="fas fa-mobile-alt"></i> <span>Layanan Pulsa & PPOB</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.payment-gateways*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.payment-gateways.index') }}">
                    <i class="fas fa-credit-card"></i> <span>Kelola Payment Gateway</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.news*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.news.index') }}">
                    <i class="fas fa-newspaper"></i> <span>Kelola Berita</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.banners.index') }}">
                    <i class="fas fa-images"></i> <span>Kelola Banner</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.website-settings*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.website-settings.index') }}">
                    <i class="fas fa-cog"></i> <span>Kelola Website</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.vip-reseller-settings*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.vip-reseller-settings.index') }}">
                <i class="fas fa-link"></i> <span>VIP Reseller API</span>
              </a>
            </li>

            <li class="{{ request()->routeIs('admin.contacts*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                    <i class="fas fa-envelope"></i> <span>Kelola Contact Us</span>
                </a>
            </li>

            <li class="dropdown {{ request()->routeIs('admin.game-transactions*') || request()->routeIs('admin.prepaid-transactions*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-receipt"></i> <span>Kelola Transaksi</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('admin.game-transactions*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.game-transactions.index') }}">Transaksi Game</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.prepaid-transactions*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.prepaid-transactions.index') }}">Transaksi Pulsa & PPOB</a>
                    </li>
                </ul>
            </li>

            <li class="dropdown {{ request()->routeIs('admin.deposits*') || request()->routeIs('admin.mutations*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-wallet"></i> <span>Kelola Saldo</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('admin.deposits*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.deposits.index') }}">Kelola Deposit</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.mutations*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.mutations.index') }}">Kelola Mutasi</a>
                    </li>
                </ul>
            </li>
          </ul>        </aside>
      </div>