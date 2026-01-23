<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MobileKiShop!</title>
    <style>
        body, div, p, a, img { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        #outlook a { padding: 0; }
        .ReadMsgBody { width: 100%; }
        .ExternalClass { width: 100%; }
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
        body { margin: 0; padding: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; background-color: #f4f4f4; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        body { font-family: 'Arial', sans-serif; }
        .container { width: 100%; max-width: 600px; margin: 20px auto; background-color: #ffffff; border: 1px solid #dddddd; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        .header { background-color: #ffffff; padding: 20px; text-align: center; border-bottom: 2px solid #0c75be; }
        .content { padding: 20px; color: #333333; font-size: 16px; line-height: 1.6; }
        .footer { background-color: #e9e9e9; color: #777777; font-size: 14px; padding: 20px; text-align: center; }
        .button { background-color: #0c75be; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; }
        .social-icons {
            margin-top: 10px;
        }

        .social-icon {
            width: 24px;
            height: 24px;
            margin: 0 5px;
            display: inline-block;
        }

        .social-icon img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container" style="text-align: center;">
         <div class="header">
            <a href="{{url('/')}}">
                <img src="{{ asset('images/logo.png') }}" alt="MobileKiShop Logo" style="max-width: 150px;">
            </a>
            <!-- Social Media Icons -->
            <div class="social-icons">
                <a href="https://www.facebook.com/mobilekishop/" class="social-icon">
                    <img src="{{URL::to('/images/icons/facebook.png')}}" alt="Facebook">
                </a>
                <a href="https://twitter.com/mobilekishop" class="social-icon">
                    <img src="{{URL::to('/images/icons/twitter.png')}}" alt="Twitter">
                </a>
                <a href="https://www.instagram.com/mobilekishop/" class="social-icon">
                    <img src="{{URL::to('/images/icons/instagram.png')}}" alt="Instagram">
                </a>
                <a href="https://www.youtube.com/@MobileKiShop" class="social-icon">
                    <img src="{{URL::to('/images/icons/youtube.png')}}" alt="Instagram">
                </a>
                <!-- Add more icons as needed -->
            </div>
        </div>
        <div class="content">
            @yield('content')

            <p>If you have any questions or need assistance, our support team is always ready to help at <a href="mailto:info@MobileKiShop.net">info@MobileKiShop.net</a>.</p>

            <p>Warm regards,<br>The MobileKiShop Team</p>
        </div>
        <div class="footer">
            You received this email because you registered at MobileKiShop.net. If you no longer wish to receive emails from us, you can <a href="{{ url('/unsubscribe') }}">unsubscribe</a>.
        </div>
    </div>
</body>
</html>
