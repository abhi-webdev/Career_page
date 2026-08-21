<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Scheduled</title>
    <style>
        body { margin: 0; padding: 0; background: #f8fafc; font-family: Arial, sans-serif; color: #334155; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0; }
        .header { color: #f97316; font-size: 24px; font-weight: bold; }
        .title { margin-top: 20px; font-size: 20px; font-weight: bold; color: #0f172a; }
        .details { margin-top: 20px; padding: 20px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; }
        .detail { margin-bottom: 10px; font-size: 14px; }
        .label { font-weight: bold; color: #64748b; }
        .button { display: inline-block; margin-top: 24px; padding: 14px 28px; background: #f97316; color: white !important; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 14px; }
        .footer { margin-top: 30px; font-size: 13px; color: #64748b; line-height: 1.6; }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        {{ $interview->application->job->company }}
    </div>

    <div class="title">
        {{ $interview->type === 'technical' ? '⚡ Technical Interview Scheduled' : '📋 HR Interview Scheduled' }}
    </div>

    <p>
        Hello <strong>{{ $interview->application->user->name }}</strong>,
    </p>

    <p>
        We are pleased to inform you that your <strong>{{ $interview->type === 'technical' ? 'Technical Assessment' : 'HR Screening' }}</strong> interview for the position of <strong>{{ $interview->application->job->title }}</strong> has been scheduled.
    </p>

    <div class="details">

        <div class="detail">
            <span class="label">Position:</span>
            <strong>{{ $interview->application->job->title }}</strong>
        </div>

        <div class="detail">
            <span class="label">Interview Round:</span>
            {{ $interview->type === 'technical' ? 'Technical Interview (TR)' : 'HR Interview (HR)' }}
        </div>

        <div class="detail">
            <span class="label">Date:</span>
            {{ $interview->interview_date->format('l, d F Y') }}
        </div>

        <div class="detail">
            <span class="label">Time:</span>
            {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($interview->end_time)->format('h:i A') }}
        </div>

        <div class="detail">
            <span class="label">Interviewer:</span>
            {{ $interview->interviewer ? $interview->interviewer->name : 'Recruitment Team' }}
        </div>

        @if($interview->notes)
            <div class="detail" style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #e2e8f0;">
                <span class="label">Notes / Instructions:</span><br>
                <span style="color: #334155;">{{ $interview->notes }}</span>
            </div>
        @endif

    </div>

    @if($interview->meeting_link)
        <div style="text-align: center;">
            <a href="{{ $interview->meeting_link }}" class="button" target="_blank">
                📹 Join Google Meet
            </a>
        </div>
    @endif

    <p style="margin-top: 24px; font-size: 13px; color: #64748b;">
        Please ensure you join the video meeting 5 to 10 minutes early with a stable internet connection.
    </p>

    <div class="footer">
        Regards,<br>
        <strong>{{ $interview->interviewer ? $interview->interviewer->name : 'Recruitment Team' }}</strong><br>
        {{ $interview->application->job->company }}
    </div>

</div>

</body>
</html>