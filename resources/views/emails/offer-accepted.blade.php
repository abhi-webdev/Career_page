<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Accepted Successfully</title>
</head>
<body style="margin:0; padding:0; background:#f8fafc; font-family:Arial,Helvetica,sans-serif;">

<div style="max-width:600px; margin:40px auto; background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">

    {{-- Header --}}
    <div style="padding:30px; background:#F97316; color:#ffffff;">
        <h1 style="margin:0; font-size:24px;">
            Offer Accepted Successfully! 🎉
        </h1>
        <p style="margin:8px 0 0; font-size:14px;">
            Welcome to the team!
        </p>
    </div>

    {{-- Content --}}
    <div style="padding:32px;">
        <p style="margin-top:0; font-size:16px; color:#334155;">
            Hello <strong>{{ $offer->application->user->name }}</strong>,
        </p>

        <p style="color:#475569; font-size:14px; line-height:1.7;">
            Congratulations! Your employment offer for the position of <strong>{{ $offer->application->job->title }}</strong> at <strong>{{ $offer->application->job->company }}</strong> has been officially accepted.
        </p>

        {{-- Details Box --}}
        <div style="margin-top:24px; padding:22px; background:#fff7ed; border:1px solid #fdba74; border-radius:12px;">
            <h2 style="margin:0 0 18px; font-size:17px; color:#9a3412;">
                Hiring & Onboarding Summary
            </h2>

            <p style="margin:10px 0; font-size:14px; color:#9a3412;">
                <strong>Employee ID:</strong>
                {{ $employee->employee_code }}
            </p>

            <p style="margin:10px 0; font-size:14px; color:#9a3412;">
                <strong>Position:</strong>
                {{ $offer->application->job->title }}
            </p>

            <p style="margin:10px 0; font-size:14px; color:#9a3412;">
                <strong>Company:</strong>
                {{ $offer->application->job->company }}
            </p>

            <p style="margin:10px 0; font-size:14px; color:#9a3412;">
                <strong>Official Joining Date:</strong>
                {{ $employee->joining_date->format('d M Y') }}
            </p>

            <p style="margin:10px 0; font-size:14px; color:#9a3412;">
                <strong>Signed Offer:</strong>
                ✓ Verified & Received
            </p>
        </div>

        <p style="margin-top:28px; font-size:14px; line-height:1.7; color:#475569;">
            We look forward to welcoming you to the team. Our onboarding department will reach out with your first-day onboarding details prior to your joining date.
        </p>

        <p style="margin-bottom:0; font-size:14px; color:#475569;">
            Warm regards,<br>
            <strong>Recruitment & Talent Team</strong>
        </p>
    </div>

    {{-- Footer --}}
    <div style="padding:20px; background:#f8fafc; border-top:1px solid #e2e8f0; text-align:center;">
        <p style="margin:0; font-size:12px; color:#94a3b8;">
            This is an automated confirmation from Job Portal ATS.
        </p>
    </div>

</div>

</body>
</html>
