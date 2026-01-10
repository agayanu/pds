<li class="nav-item">
    <a class="nav-link logout" href="{!! route('logout') !!}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="nav-icon cil-account-logout logout"></i> Logout
    </a>
</li>
<li class="nav-item {{ Request::is('home') ? 'active' : '' }}">
    <a class="nav-link" href="{!! route('home') !!}">
        <i class="nav-icon cil-home"></i> Beranda
    </a>
</li>
<li class="nav-title">===</li>
