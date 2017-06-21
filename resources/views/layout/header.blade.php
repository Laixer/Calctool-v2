<header id="topNav" class="topHead">
    <div class="container">

        <button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
            <i class="fa fa-bars"></i>
        </button>

        @if (defined('APP_LOGO'))
        <a class="logo" href="/">@logo</a>
        @endif

        <div class="navbar-collapse nav-main-collapse collapse pull-right">
            <nav class="nav-main mega-menu">
                <ul class="nav nav-pills nav-main scroll-menu" id="topMain">

                    @if (Auth::check() && !Auth::user()->isSystem())
                    <li class="{{ request()->is('/') ? 'active' : '' }}">
                        <a href="/">Dashboard</a>
                    </li>
                    <li class="{{ request()->is('account') ? 'active' : '' }}">
                        <a href="/account">Account </a>
                    </li>
                    @endif

                    @if (Auth::check() && Auth::user()->isAdmin() && !session()->has('swap_session'))
                    <li class="{{ request()->is('admin') ? 'active' : '' }}">
                        <a href="/admin">Admin Dashboard</a>
                    </li>
                    @endif

                    <li class="{{ (request()->is('support') || request()->is('support/gethelp')) ? 'active' : '' }}">
                        @if (!Auth::check())
                        <a href="/support">Support</a>
                        @else
                        <a href="/support/gethelp">Support</a>
                        @endif
                    </li>

                    @if (Auth::check())
                        @if (!Auth::user()->isDemo())
                            @if (session()->has('swap_session'))
                            <li><a href="/admin/switch/back">Terugkeren</a></li>
                            @else
                            <li><a href="/auth/signout">Uitloggen</a></li>
                            @endif
                        @endif
                    @else
                    <li class="{{ (request()->is('auth/signin')) ? 'active' : '' }}"><a href="/auth/signin">Login</a></li>
                    @endif

                    @unless (Auth::check())
                    <li class="visible-xs visible-sm">
                        <a href="/auth/signup">Account aanmaken</a>
                    </li>
                    @endunless

                    @if (Auth::check())
                    @if (0)
                    <li class="search">
                        <form method="get" action="/search" class="input-group pull-right">
                            <input type="text" class="form-control" name="q" id="q" value="" placeholder="Zoeken">
                            <span class="input-group-btn">
                                <button class="btn btn-primary notranssubjectition"><i class="fa fa-search"></i></button>
                            </span>
                        </form>
                    </li>
                    @endif

                    <li class="quick-cart">
                        @if ($notifications->count() > 0)
                        <span class="badge pull-right">{{ $notifications->count() }}</span>
                        @endif
                        <div class="quick-cart-content">

                            @if ($notifications->count() == 0)
                            <p> Geen nieuwe meldingen</p>
                            @elseif ($notifications->count() == 1)
                            <p> 1 ongelezen notificatie</p>
                            @else
                            <p> {{ $notifications->count() }} ongelezen notificaties</p>
                            @endif

                            @foreach($notifications as $message)
                            <a class="item" href="/notification/message-{{ $message->id }}">
                                <i class="fa fa-envelope pull-left fsize30" style="margin-right: 10px; margin-left: 8px;"></i>
                                <div class="inline-block">
                                    <span class="title">{{ $message->subject }}</span>
                                </div>
                            </a>
                            @endforeach

                            <div class="row cart-footer">
                                <div class="col-md-12">
                                    <a href="/notification" class="btn btn-primary btn-xs fullwidth">Alle notificaties</a>
                                </div>
                            </div>

                        </div>
                    </li>
                    @endif

                </ul>
            </nav>
        </div>

    </div>
</header>
