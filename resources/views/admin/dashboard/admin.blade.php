@extends('layout.master')

@section('title', 'Admin Dashboard')

@section('content')
        <div id="wrapper">

            <section class="container text-center">
                <h1 class="text-center">
                    <strong>Admin</strong> Dashboard
                </h1>
            </section>

            <div id="shop">

                <section class="container">

                    <div class="row">

                        <div class="col-sm-12 col-md-2">
                            <div class="item-box item-box-show fixed-box">
                                <figure>
                                    <a class="item-hover" href="/admin/user">
                                        <span class="overlay color2"></span>
                                        <span class="inner">
                                            <span class="block fa fa-user fsize60"></span>
                                        </span>
                                    </a>
                                    <a href="/admin/user" class="btn btn-primary add_to_cart"><i class="fa fa-user"></i> Gebruikers</a>
                                </figure>
                                <div class="item-box-desc">
                                    <a href="/admin/user" ><h4>Gebruikers</h4></a>
                                    <small class="styleColor">{{ \BynqIO\Dynq\Models\User::where('active','=','true')->count() }} actieve gebruikers</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                            <div class="item-box item-box-show fixed-box">
                                <figure>
                                    <a class="item-hover" href="/admin/group">
                                        <span class="overlay color2"></span>
                                        <span class="inner">
                                            <span class="block fa fa-users fsize60"></span>
                                        </span>
                                    </a>
                                    <a href="/admin/group" class="btn btn-primary add_to_cart"><i class="fa fa-users"></i> Groepen</a>
                                </figure>
                                <div class="item-box-desc">
                                    <a href="/admin/group" ><h4>Groepen</h4></a>
                                    <small class="styleColor">{{ \BynqIO\Dynq\Models\UserGroup::count() }} groepen</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                            <div class="item-box item-box-show fixed-box">
                                <figure>
                                    <a class="item-hover" href="/admin/payment">
                                        <span class="overlay color2"></span>
                                        <span class="inner">
                                            <span class="block fa fa-usd fsize60"></span>
                                        </span>
                                    </a>
                                    <a href="/admin/payment" class="btn btn-primary add_to_cart"><i class="fa fa-usd"></i> Transacties</a>
                                </figure>
                                <div class="item-box-desc">
                                    <a href="/admin/payment"><h4>Transacties</h4></a>
                                    <small class="styleColor">{{ \BynqIO\Dynq\Models\Payment::count() }} transactie(s)</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                            <div class="item-box item-box-show fixed-box">
                                <figure>
                                    <a class="item-hover" href="/admin/project">
                                        <span class="overlay color2"></span>
                                        <span class="inner">
                                            <span class="block fa fa-pencil fsize60"></span>
                                        </span>
                                    </a>
                                    <a href="/admin/project" class="btn btn-primary add_to_cart"><i class="fa fa-pencil"></i> Projecten</a>
                                </figure>
                                <div class="item-box-desc">
                                    <a href="/admin/project"><h4>Projecten</h4></a>
                                    <small class="styleColor">{{ \BynqIO\Dynq\Models\Project::count() }} Projecten</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                            <div class="item-box item-box-show fixed-box">
                                <figure>
                                    <a class="item-hover" href="/admin/alert">
                                        <span class="overlay color2"></span>
                                        <span class="inner">
                                            <span class="block fa fa-warning fsize60"></span>
                                        </span>
                                    </a>
                                    <a href="/admin/alert" class="btn btn-primary add_to_cart"><i class="fa fa-warning"></i> Alerts</a>

                                </figure>
                                <div class="item-box-desc">
                                    <a href="/admin/alert" ><h4>Alerts</h4></a>
                                    <small class="styleColor">{{ \BynqIO\Dynq\Models\SysMessage::where('active',true)->count() }} alert(s)</small>
                                </div>
                            </div>
                        </div>

                        <!--<div class="col-sm-12 col-md-2">
                            <div class="item-box item-box-show fixed-box">
                                <figure>
                                    <a class="item-hover" href="/admin/snailmail">
                                        <span class="overlay color2"></span>
                                        <span class="inner">
                                            <span class="block fa fa-print fsize60"></span>
                                        </span>
                                    </a>
                                    <a href="/admin/snailmail" class="btn btn-primary add_to_cart"><i class="fa fa-print"></i>Printservice</a>
                                </figure>
                                <div class="item-box-desc">
                                    <a href="/admin/snailmail"><h4>Printservice</h4></a>
                                    <small class="styleColor">{{ \BynqIO\Dynq\Models\OfferPost::whereNull('sent_date')->count() }} offertes</small>
                                    <small class="styleColor">{{ \BynqIO\Dynq\Models\InvoicePost::whereNull('sent_date')->count() }} facturen</small>
                                </div>
                            </div>
                        </div>-->

                        <div class="col-sm-12 col-md-2">
                            <div class="item-box item-box-show fixed-box">
                                <figure>
                                    <a class="item-hover" href="/admin/message">
                                        <span class="overlay color2"></span>
                                        <span class="inner">
                                            <span class="block fa fa-bell fsize60"></span>
                                        </span>
                                    </a>
                                    <a href="/admin/message" class="btn btn-primary add_to_cart"><i class="fa fa-bell"></i> Notificaties</a>
                                </figure>
                                <div class="item-box-desc">
                                    <a href="/admin/message"><h4>Notificaties</h4></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                            <div class="item-box item-box-show fixed-box">
                                <figure>
                                    <a class="item-hover" href="/admin/promo">
                                        <span class="overlay color2"></span>
                                        <span class="inner">
                                            <span class="block fa fa-percent fsize60"></span>
                                        </span>
                                    </a>
                                    <a href="/admin/promo" class="btn btn-primary add_to_cart"><i class="fa fa-percent"></i> Acties</a>
                                </figure>
                                <div class="item-box-desc">
                                    <a href="/admin/promo"><h4>Acties</h4></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                            <div class="item-box item-box-show fixed-box">
                                <figure>
                                    <a class="item-hover" href="/admin/product">
                                        <span class="overlay color2"></span>
                                        <span class="inner">
                                            <span class="block fa fa-shopping-cart fsize60"></span>
                                        </span>
                                    </a>
                                    <a href="/admin/product" class="btn btn-primary add_to_cart"><i class="fa fa-user-plus"></i> Artikelen</a>
                                </figure>
                                <div class="item-box-desc">
                                    <a href="/admin/product"><h4>Artikelen</h4></a>
                                </div>
                            </div>
                        </div>

                    </div>

                </section>

            </div>

        </div>

@stop
