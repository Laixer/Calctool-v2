<div class="col-sm-6 col-md-2">
    <div class="item-box item-box-show fixed-box">
        <figure>
            <a class="item-hover" href="/company/details">
                <span class="overlay color2"></span>
                <span class="inner" style="top:40%;">
                    <span class="block fa fa-home fsize60"></span>
                </span>
            </a>
            <a href="/company/details" class="btn btn-primary add_to_cart"><strong> @lang('core.companyinfo')</strong></a>

        </figure>
    </div>
</div>

@if ($projectCount)
<div class="col-sm-6 col-md-2">
    <div class="item-box item-box-show fixed-box">
        <figure>
            <a class="item-hover" href="/material">
                <span class="overlay color2"></span>
                <span class="inner" style="top:40%;">
                    <span class="block fa fa-wrench fsize60"></span>
                </span>
            </a>
            <a href="/material" class="btn btn-primary add_to_cart"><strong> @lang('core.products')</strong></a>
        </figure>
    </div>
</div>

<div class="col-sm-6 col-md-2">
    <div class="item-box item-box-show fixed-box">
        <figure>
            <a class="item-hover" href="/timesheet">
                <span class="overlay color2"></span>
                <span class="inner" style="top:40%;">
                    <span class="block fa fa-clock-o fsize60"></span>
                </span>
            </a>
            <a href="/timesheet" class="btn btn-primary add_to_cart"><strong> @lang('core.timesheet')</strong></a>
        </figure>
    </div>
</div>

<div class="col-sm-6 col-md-2">
    <div class="item-box item-box-show fixed-box">
        <figure>
            <a class="item-hover" href="/purchase">
                <span class="overlay color2"></span>
                <span class="inner" style="top:40%;">
                    <span class="block fa fa-shopping-cart fsize60"></span>
                </span>
            </a>
            <a href="/purchase" class="btn btn-primary add_to_cart"><strong> @lang('core.purchaseinvoice')</strong></a>
        </figure>
    </div>
</div>

<div class="col-sm-6 col-md-2 hidden-xs">
    <div class="item-box item-box-show fixed-box">
        <figure>
            <a class="item-hover" href="/finance/overview">
                <span class="overlay color2"></span>
                <span class="inner" style="top:40%;">
                    <span class="block fa fa-usd fsize60"></span>
                </span>
            </a>
            <a href="/finance/overview" class="btn btn-primary add_to_cart"><strong> @lang('core.financial')</strong></a>
        </figure>
    </div>
</div>
@else
<div class="col-sm-6 col-md-2">
    <div class="item-box item-box-show fixed-box">
        <figure>
            <a class="item-hover" style="cursor: default;" href="javascript:void(0);">
                <span class="overlay color2" style="background: #9E9E9E !important"></span>
                <span class="inner" style="top:40%;">
                    <span class="block fa fa-wrench fsize60"></span>
                </span>
            </a>
            <a href="javascript:void(0);" style="cursor: default;" class="btn btn-primary add_to_cart"><strong> @lang('core.products')</strong></a>
        </figure>
    </div>
</div>

<div class="col-sm-6 col-md-2">
    <div class="item-box item-box-show fixed-box">
        <figure>
            <a class="item-hover" style="cursor: default;" href="javascript:void(0);">
                <span class="overlay color2" style="background: #9E9E9E !important"></span>
                <span class="inner" style="top:40%;">
                    <span class="block fa fa-clock-o fsize60"></span>
                </span>
            </a>
            <a href="javascript:void(0);" style="cursor: default;" class="btn btn-primary add_to_cart"><strong> @lang('core.timesheet')</strong></a>
        </figure>
    </div>
</div>

<div class="col-sm-6 col-md-2">
    <div class="item-box item-box-show fixed-box">
        <figure>
            <a class="item-hover" style="cursor: default;" href="javascript:void(0);">
                <span class="overlay color2" style="background: #9E9E9E !important"></span>
                <span class="inner" style="top:40%;">
                    <span class="block fa fa-shopping-cart fsize60"></span>
                </span>
            </a>
            <a href="javascript:void(0);" style="cursor: default;" class="btn btn-primary add_to_cart"><strong> @lang('core.purchaseinvoice')</strong></a>
        </figure>
    </div>
</div>

<div class="col-sm-6 col-md-2 hidden-xs">
    <div class="item-box item-box-show fixed-box">
        <figure>
            <a class="item-hover" style="cursor: default;" href="javascript:void(0);">
                <span class="overlay color2" style="background: #9E9E9E !important"></span>
                <span class="inner" style="top:40%;">
                    <span class="block fa fa-usd fsize60"></span>
                </span>
            </a>
            <a href="javascript:void(0);" style="cursor: default;" class="btn btn-primary add_to_cart"><strong> @lang('core.financial')</strong></a>
        </figure>
    </div>
</div>
@endif

<div class="col-sm-6 col-md-2">
    <div class="item-box item-box-show fixed-box">
        <figure>
            <a class="item-hover" href="/relation">
                <span class="overlay color2"></span>
                <span class="inner" style="top:40%;">
                    <span class="block fa fa-users fsize60"></span>
                </span>
            </a>
            <a href="/relation" class="btn btn-primary add_to_cart"><strong> {{ trans_choice('core.relation', 2) }}</strong></a>
        </figure>
    </div>
</div>