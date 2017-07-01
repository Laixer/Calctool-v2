{{--
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
--}}

@extends('layout.master')

@section('content')
<div id="wrapper">

    <section class="container">

        <div class="row">

            <div class="col-md-12 text-center">
                <div class="e404">404</div>
                <h2>
                    <strong>Oeps</strong>, Deze pagina is niet beschikbaar!
                    <span class="subtitle">Ons excuus, de pagina {{ Request::path() }} kon niet worden gevonden.</span>
                </h2>
            </div>

        </div>

    </section>

</div>
@stop
