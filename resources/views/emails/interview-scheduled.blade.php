<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Interview Scheduled</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f8fafc;
            font-family: Arial, sans-serif;
            color: #334155;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            padding: 32px;
            border: 1px solid #e2e8f0;
        }

        .header {
            color: #4f46e5;
            font-size: 24px;
            font-weight: bold;
        }

        .title {
            margin-top: 25px;
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }

        .details {
            margin-top: 20px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 10px;
        }

        .detail {
            margin-bottom: 12px;
        }

        .label {
            font-weight: bold;
            color: #64748b;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #4f46e5;
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #64748b;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        {{ $interview->application->job->company }}
    </div>

    <div class="title">
        Interview Scheduled
    </div>

    <p>
        Dear
        <strong>
            {{ $interview->application->user->name }}
        </strong>,
    </p>

    <p>
        We are pleased to inform you that your interview has been scheduled
        for the position of
        <strong>
            {{ $interview->application->job->title }}
        </strong>.
    </p>


    <div class="details">

        <div class="detail">
            <span class="label">Position:</span>
            {{ $interview->application->job->title }}
        </div>

        <div class="detail">
            <span class="label">Date:</span>
            {{ $interview->interview_date->format('d M Y') }}
        </div>

        <div class="detail">
            <span class="label">Time:</span>
            {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }}
            -
            {{ \Carbon\Carbon::parse($interview->end_time)->format('h:i A') }}
        </div>

        @if($interview->notes)

            <div class="detail">
                <span class="label">Instructions:</span>
                {{ $interview->notes }}
            </div>

        @endif

    </div>


    @if($interview->meeting_link)

        <a
            href="{{ $interview->meeting_link }}"
            class="button"
        >
            Join Google Meet
        </a>

    @endif


    <p>
        Please join the meeting at least 10 minutes before the scheduled
        interview time.
    </p>

    <div class="footer">

        Regards,<br>

        <strong>
            Recruitment Team
        </strong><br>

        {{ $interview->application->job->company }}

    </div>

</div>

</body>
</html>