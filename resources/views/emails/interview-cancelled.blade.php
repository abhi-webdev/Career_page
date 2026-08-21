<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Cancelled</title>
    <style>
        body { margin: 0; padding: 0; background: #f8fafc; font-family: Arial, sans-serif; color: #334155; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0; }
        .header { color: #dc2626; font-size: 24px; font-weight: bold; }
        .title { margin-top: 20px; font-size: 20px; font-weight: bold; color: #0f172a; }
        .details { margin-top: 20px; padding: 20px; background: #fef2f2; border-radius: 12px; border: 1px solid #fecaca; }
        .detail { margin-bottom: 10px; font-size: 14px; }
        .label { font-weight: bold; color: #991b1b; }
        .footer { margin-top: 30px; font-size: 13px; color: #64748b; line-height: 1.6; }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        {{ $interview->application->job->company }}
    </div>

    <div class="title">
        {{ $interview->type === 'technical' ? '⚡ Technical Interview Cancelled' : '📋 HR Interview Cancelled' }}
    </div>

    <p>
        Hello <strong>{{ $interview->application->user->name }}</strong>,
    </p>

    <p>
        We would like to inform you that your <strong>{{ $interview->type === 'technical' ? 'Technical Assessment' : 'HR Screening' }}</strong> interview for the position of <strong>{{ $interview->application->job->title }}</strong> has been cancelled.
    </p>

    <div class="details">

        <div class="detail">
            <span class="label">Position:</span>
            <strong>{{ $interview->application->job->title }}</strong>
        </div>

        <div class="detail">
            <span class="label">Previous Date:</span>
            {{ $interview->interview_date->format('l, d F Y') }}
        </div>

        <div class="detail">
            <span class="label">Previous Time:</span>
            {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }}
        </div>

    </div>

    <p style="margin-top: 24px; font-size: 14px; color: #475569; line-height: 1.7;">
        If this interview is rescheduled, you will receive an updated email confirmation with the new schedule and meeting link.
    </p>

    <div class="footer">
        Regards,<br>
        <strong>Recruitment Team</strong><br>
        {{ $interview->application->job->company }}
    </div>

</div>

</body>
</html>