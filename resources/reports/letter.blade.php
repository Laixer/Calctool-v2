@extends('report')

@section('footer')
<div>
    KVK: 1293629 <span class="divider">|</span>
    BTW: NL1275430B01 <span class="divider">|</span>
    IBAN: NL15INGB0000106644 
    TNV: Arie kaas B.V. <span class="divider">|</span>
    SWIFT: 42-12-43
</div>
@endsection

@section('topright')
<h1>FACTUUR {{ $invoice }}</h1>
<div class="date">Project: {{ $project->project_name }}</div>
<div class="date">Date of Invoice: 01/06/2014</div>
<div class="date">Due Date: 30/06/2014</div>
@isset($reference)
<div class="date">Reference: {{ $reference }}</div>
@endisset
@endsection

@section('topleft')
<div class="to">FACTUUR AAN:</div>
<h2 class="name">John Doe</h2>
<div class="address">796 Silver Harbour, TX 79273, US</div>
<div class="email"><a href="mailto:john@example.com">john@example.com</a></div>
<div class="address">Customer No: 17320263</div>
<div class="address">VAT Numner: NL1287329</div>
@endsection


{{-- First page --}}

@section('body_main')
<div id="details" class="clearfix">
    <div id="client">@yield('topleft')</div>
    <div id="invoice">@yield('topright')</div>
</div>

@isset($pretext)
<div style="font-size: 16px;padding-bottom: 10px;">Geachte heer Janssen,</div>
<div style="font-size: 16px;padding-bottom: 30px;">{{ $pretext }}</div>
@endisset

@include('partials.total')

@isset($posttext)
<div style="font-size: 16px;padding-bottom: 10px;">{{ $posttext }}</div>
<div style="font-size: 16px;padding-bottom: 30px;">Met vriendelijke groet, <br />Arie kaas</div>
@endisset

@isset($messages)
<h4>Opmerkingen</h4>
<div id="notices">
    @foreach($messages as $message)
    <div class="notice">&#8226; {{ $message }}</div>
    @endforeach
</div>
@endisset

@endsection


{{-- Additional pages --}}

@foreach(array_slice($pages, 1) as $page)
@section("body_$page")
<div id="details" class="clearfix">
    <div id="invoice">@yield('topright')</div>
</div>

@include("partials.$page")

@endsection
@endforeach
