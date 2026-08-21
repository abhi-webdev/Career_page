<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Rescheduled</title>
    <style>
        body { margin: 0; padding: 0; background: #f8fafc; font-family: Arial, sans-serif; color: #334155; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0; }
        .header { color: #f97316; font-size: 24px; font-weight: bold; }
        .title { margin-top: 20px; font-size: 20px; font-weight: bold; color: #0f172a; }
        .details { margin-top: 20px; padding: 20px; background: #fff7ed; border-radius: 12px; border: 1px solid #ffedd5; }
        .detail { margin-bottom: 10px; font-size: 14px; }
        .label { font-weight: bold; color: #9a3412; }
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
        {{ $interview->type === 'technical' ? '⚡ Technical Interview Rescheduled' : '📋 HR Interview Rescheduled' }}
    </div>

    <p>
        Hello <strong>{{ $interview->application->user->name }}</strong>,
    </p>

    <p>
        Your <strong>{{ $interview->type === 'technical' ? 'Technical Assessment' : 'HR Screening' }}</strong> interview for the position of <strong>{{ $interview->application->job->title }}</strong> has been rescheduled to a new date and time.
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
            <span class="label">New Date:</span>
            {{ $interview->interview_date->format('l, d F Y') }}
        </div>

        <div class="detail">
            <span class="label">New Time:</span>
            {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($interview->end_time)->format('h:i A') }}
        </div>

        <div class="detail">
            <span class="label">Interviewer:</span>
            {{ $interview->interviewer ? $interview->interviewer->name : 'Recruitment Team' }}
        </div>

        @if($interview->notes)
            <div class="detail" style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #ffedd5;">
                <span class="label">Updated Notes:</span><br>
                <span style="color: #431407;">{{ $interview->notes }}</span>
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
        Please make a note of the new schedule and join the meeting a few minutes prior to the start time.
    </p>

    <div class="footer">
        Regards,<br>
        <strong>{{ $interview->interviewer ? $interview->interviewer->name : 'Recruitment Team' }}</strong><br>
        {{ $interview->application->job->company }}
    </div>

</div>

</body>
</html>