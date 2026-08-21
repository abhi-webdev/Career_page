<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revised Employment Offer</title>
</head>

<body style="margin:0; padding:0; background:#f8fafc; font-family:Arial,Helvetica,sans-serif;">

<div style="max-width:600px; margin:40px auto; background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">

    {{-- Header --}}
    <div style="padding:30px; background:#F97316; color:#ffffff;">
        <h1 style="margin:0; font-size:24px;">
            Revised Employment Offer
        </h1>
        <p style="margin:8px 0 0; font-size:14px;">
            Version {{ $offer->version }} • Your offer has been updated with a new joining date.
        </p>
    </div>

    {{-- Content --}}
    <div style="padding:32px;">
        <p style="margin-top:0; font-size:16px; color:#334155;">
            Hello <strong>{{ $offer->application->user->name }}</strong>,
        </p>

        <p style="color:#475569; font-size:14px; line-height:1.7;">
            We have reviewed your request and generated a revised employment offer for the position of 
            <strong>{{ $offer->application->job->title }}</strong> at 
            <strong>{{ $offer->application->job->company }}</strong>.
        </p>

        {{-- Revised Details Box --}}
        <div style="margin-top:24px; padding:22px; background:#fff7ed; border:1px solid #fed7aa; border-radius:12px;">
            <h2 style="margin:0 0 18px; font-size:17px; color:#9a3412;">
                Revised Offer Summary (Version {{ $offer->version }})
            </h2>

            <p style="margin:10px 0; font-size:14px; color:#9a3412;">
                <strong>Position:</strong> {{ $offer->application->job->title }}
            </p>

            <p style="margin:10px 0; font-size:14px; color:#9a3412;">
                <strong>Company:</strong> {{ $offer->application->job->company }}
            </p>

            <p style="margin:10px 0; font-size:14px; color:#9a3412;">
                <strong>Annual Compensation:</strong> ₹{{ number_format($offer->salary, 2) }}
            </p>

            <p style="margin:10px 0; font-size:14px; color:#9a3412;">
                <strong>New Joining Date:</strong> {{ $offer->joining_date->format('d M Y') }}
            </p>

            @if($offer->offer_expiry_date)
                <p style="margin:10px 0; font-size:14px; color:#9a3412;">
                    <strong>Offer Valid Until:</strong> {{ $offer->offer_expiry_date->format('d M Y') }}
                </p>
            @endif
        </div>

        {{-- Action Required Note --}}
        <div style="margin-top:24px; padding:18px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px;">
            <p style="margin:0 0 6px; font-size:13px; font-weight:bold; color:#166534;">
                Next Steps Required:
            </p>
            <p style="margin:0; font-size:13px; line-height:1.6; color:#166534;">
                Please download your revised offer letter, sign it, and upload the signed copy in your candidate portal before accepting.
            </p>
        </div>

        <p style="margin-top:28px; font-size:14px; line-height:1.7; color:#475569;">
            You can respond directly through your candidate portal to accept or submit further questions.
        </p>

        <p style="margin-bottom:0; font-size:14px; color:#475569;">
            Regards,<br>
            <strong>Recruitment Team</strong><br>
            {{ $offer->application->job->company }}
        </p>
    </div>

    {{-- Footer --}}
    <div style="padding:20px; background:#f8fafc; border-top:1px solid #e2e8f0; text-align:center;">
        <p style="margin:0; font-size:12px; color:#94a3b8;">
            This is an automated email from Job Portal.
        </p>
    </div>

</div>

</body>
</html>
