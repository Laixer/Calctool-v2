@extends('mail.layout.master')

@section('content')
<!--[if gte mso 9]>
<table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
    <tr>
        <td align="center" valign="top" width="600" style="width:600px;">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;border: 0;max-width: 600px !important;">

                {{-- Page Logo --}}
                <tr>
                    <td valign="top" id="templateHeader" style="background:#FFFFFF none no-repeat center/cover;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;background-image: none;background-repeat: no-repeat;background-position: center;background-size: cover;border-top: 0;border-bottom: 0;padding-top: 9px;padding-bottom: 0;">

                        @include('mail.layout.header')

                    </td>
                </tr>
                {{-- /Page Logo --}}

                {{-- Page Content --}}
                <tr>
                    <td valign="top" id="templateBody" style="background:#FFFFFF none no-repeat center/cover;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;background-image: none;background-repeat: no-repeat;background-position: center;background-size: cover;border-top: 0;border-bottom: 2px solid #EAEAEA;padding-top: 0;padding-bottom: 9px;">

                        @yield('page')

                    </td>
                </tr>
                {{-- /Page Content --}}

                {{-- Footer --}}
                <tr>
                    <td valign="top" id="templateFooter" style="background:#FAFAFA none no-repeat center/cover;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FAFAFA;background-image: none;background-repeat: no-repeat;background-position: center;background-size: cover;border-top: 0;border-bottom: 0;padding-top: 9px;padding-bottom: 9px;">

                        @include('mail.layout.footer')

                    </td>
                </tr>
                {{-- /Footer --}}

            </table>
            <!--[if gte mso 9]>
        </td>
    </tr>
</table>
<![endif]-->
@stop
