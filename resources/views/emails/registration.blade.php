@extends('emails.template')
@section('content')
    <tr>
        <td>
            <p style="color: #333333; font-size: 16px; margin: 0;">Dear {{$user->name}},</p>
            <p style="color: #333333; font-size: 16px; margin: 20px 0 0;">Thank you for registering at MobileKiShop. Your registration is now complete.</p>
            <p style="color: #333333; font-size: 16px; margin: 20px 0 0;">Here are some of our featured products:</p>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px 0;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="text-align: center;">
                        <a href="https://mobilekishop.net" style="text-decoration: none;">
                            <img src="https://mobilekishop.net/images/mobile_phones.png" alt="Mobile Phones" style="display: block; max-width: 100px; height: auto; margin: 0 auto;">
                            <h3 style="color: #333333; font-size: 18px; margin: 10px 0 0;">Mobile Phones</h3>
                       
						</a>
                    </td>
                    <td style="text-align: center;">
                        <a href="https://mobilekishop.net/packages" style="text-decoration: none;">
                            <img src="https://mobilekishop.net/images/mobile_packages.png" alt="Mobile Packages" style="display: block; max-width: 100px; height: auto; margin: 0 auto;">
                            <h3 style="color: #333333; font-size: 18px; margin: 10px 0 0;">Mobile Packages</h3>
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@stop