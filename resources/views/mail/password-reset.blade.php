<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفعيل الحساب</title>
    <style>
        /* إعدادات الخطوط */
        body {
            font-family: Arial, sans-serif;
            width: 100%;

            direction:rtl;

            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        .header {
            background-color: #3498db;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 150px; /* حجم الشعار */
        }

        .content {
            padding: 20px;
            text-align: center;
            font-size: 16px;
            line-height: 1.6;
            color: #333333;
        }

        .content p {
            margin-bottom: 20px;
        }

        .button {
            background-color: #3498db;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            display: inline-block;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="email-container">
    <!-- Header with logo -->
    <div class="header">

        <img src="your-logo-url.png" alt="Logo"> <!-- استبدل `your-logo-url.png` برابط الشعار الخاص بك -->
    </div>

    <!-- Content -->
    <div class="content">
        <p>
            Hi, {{ $passwordReset->user->name }}
        </p>
        <p>
            this Message is to verify Your email to password reset
        </p>

        <p style="text-align: center">
            {{ $passwordReset->code }}
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>إذا كنت تواجه مشكلة أو لديك أي استفسارات، يمكنك الاتصال بنا عبر البريد الإلكتروني.</p>
        <p>&copy; 2025 شركتنا. جميع الحقوق محفوظة.</p>
    </div>
</div>

</body>
</html>
