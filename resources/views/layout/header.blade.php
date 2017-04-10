<?php
use \CalculatieTool\Models\User;
use \CalculatieTool\Models\MessageBox;
use \CalculatieTool\Models\UserType;
?>

<header id="topNav" class="topHead">
	<div class="container">

		<button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
			<i class="fa fa-bars"></i>
		</button>

		<a class="logo" href="/">
			<img src="/images/logo2.png" width="229px" alt="CalculatieTool.com" />
		</a>

		<div class="navbar-collapse nav-main-collapse collapse pull-right">
			<nav class="nav-main mega-menu">
				<ul class="nav nav-pills nav-main scroll-menu" id="topMain">
					@if (Auth::check() && !Auth::user()->isSystem())
					<li>
						<a href="/">Dashboard</a>
					</li>
					<li>
						<a href="/myaccount">Mijn account</a>
					</li>
					@endif
					@if (0)
					<li>
						<a href="/about">Over ons</a>
					</li>
					@endif
					@if (Auth::check() && Auth::user()->isAdmin() && !session()->has('swap_session'))
					<li>
						<a href="/admin">Admin CP</a>
					</li>
					@else
					<li>
						<a href="/get-help">Support</a>
					</li>
					@endif
					<li class="active">
					<?php
						if (Auth::check()) {
							if (UserType::find(Auth::user()->user_type)->user_type != 'demo') {
								if (session()->has('swap_session')) {
									echo '<a href="/admin/switch/back">Terugkeren</a>';
								} else {
									echo '<a href="/logout">Uitloggen</a>';
								}
							} else {
								echo '<a href="javascript:void(0);"></a>';
							}
						} else {
							echo '<a href="/login">Login</a>';
						}
					?>
					</li>
					@if (!Auth::check())
					<li class="visible-xs visible-sm">
						<a href="/register">Account aanmaken</a>
					</li>
					@endif
					@if (0)
					<li class="search">
						<form method="get" action="#" class="input-group pull-right">
							<input type="text" class="form-control" name="k" id="k" value="" placeholder="Zoeken">
							<span class="input-group-btn">
								<button class="btn btn-primary notransition"><i class="fa fa-search"></i></button>
							</span>
						</form>
					</li>
					@endif
					@if (Auth::check())
					<li class="quick-cart">
						<?php
						$msg_cnt = MessageBox::where('user_id','=', Auth::id())->where('active', true)->whereNull('read')->count();
						?>
						@if ($msg_cnt > 0)
						<span class="badge pull-right">{{ $msg_cnt }}</span>
						@endif
						<div class="quick-cart-content">

							@if ($msg_cnt == 0)
							<p> Geen nieuwe meldingen</p>
							@elseif ($msg_cnt == 1)
							<p> 1 ongelezen notificatie</p>
							@else
							<p> {{ $msg_cnt }} ongelezen notificaties</p>
							@endif

							@foreach(MessageBox::where('user_id','=', Auth::id())->where('active', true)->whereNull('read')->get() as $message)
							<a class="item" href="/messagebox/message-{{ $message->id }}">
								<i class="fa fa-envelope pull-left fsize30" style="margin-right: 10px; margin-left: 8px;"></i>
								<div class="inline-block">
									<span class="price">{{ ucfirst(User::find($message->from_user)->username) }}</span>
									<span class="title">{{ $message->subject }}</span>
								</div>
							</a>
							@endforeach

							<div class="row cart-footer">
								<div class="col-md-12">
									<a href="/messagebox" class="btn btn-primary btn-xs fullwidth">Alle notificaties</a>
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
