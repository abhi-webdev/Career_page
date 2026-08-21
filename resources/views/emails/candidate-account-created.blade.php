<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Careers — Account Created</title>
    <style>
        body { margin: 0; padding: 0; background: #f8fafc; font-family: Arial, sans-serif; color: #334155; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0; }
        .header { color: #f97316; font-size: 24px; font-weight: bold; }
        .title { margin-top: 20px; font-size: 20px; font-weight: bold; color: #0f172a; }
        .details { margin-top: 20px; padding: 20px; background: #fff7ed; border-radius: 12px; border: 1px solid #ffedd5; }
        .detail { margin-bottom: 10px; font-size: 14px; }
        .label { font-weight: bold; color: #9a3412; }
        .password-box { font-family: monospace; font-size: 16px; font-weight: bold; background: white; padding: 8px 14px; border-radius: 8px; border: 1px dashed #f97316; display: inline-block; color: #c2410c; margin-top: 4px; }
        .button { display: inline-block; margin-top: 24px; padding: 14px 28px; background: #f97316; color: white !important; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 14px; }
        .footer { margin-top: 30px; font-size: 13px; color: #64748b; line-height: 1.6; }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        Company Careers
    </div>

    <div class="title">
        🎉 Welcome! Your Candidate Account Has Been Created
    </div>

    <p>
        Hello <strong>{{ $user->name }}</strong>,
    </p>

    <p>
        Thank you for submitting your job application. A secure candidate account has been generated for you so you can track your application status, interview schedules, and upcoming offers.
    </p>

    <div class="details">

        <div class="detail">
            <span class="label">Login Email:</span><br>
            <strong>{{ $user->email }}</strong>
        </div>

        <div class="detail" style="margin-top: 12px;">
            <span class="label">Temporary Access Password:</span><br>
            <div class="password-box">{{ $temporaryPassword }}</div>
        </div>

    </div>

    <div style="text-align: center;">
        <a href="{{ route('login') }}" class="button">
            Log In to Candidate Portal →
        </a>
    </div>

    <p style="margin-top: 24px; font-size: 13px; color: #64748b;">
        🔒 <strong>Security Tip:</strong> We recommend updating your password from your profile settings after your first login.
    </p>

    <div class="footer">
        Regards,<br>
        <strong>Recruitment Operations Team</strong><br>
        Company Careers Portal
    </div>

</div>

</body>
</html>
