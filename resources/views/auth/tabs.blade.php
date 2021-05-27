<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ Route::is('login') ? 'active' : null }}" href="{{ route('login') }}">Вход</a>
    </li>
    <li class="nav-item">
       <a class="nav-link {{ Route::is('register') ? 'active' : null }}" href="{{ route('register') }}">Регистрация</a>
    </li>
</ul>
