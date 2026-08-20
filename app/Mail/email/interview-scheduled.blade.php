<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Interview Scheduled</title>

</head>

<body
    style="
        margin: 0;
        padding: 0;
        background-color: #f8fafc;
        font-family: Arial, Helvetica, sans-serif;
    "
>

<div
    style="
        max-width: 600px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    "
>

    {{-- Header --}}

    <div
        style="
            padding: 28px;
            background: #4f46e5;
            color: #ffffff;
        "
    >

        <h1
            style="
                margin: 0;
                font-size: 24px;
            "
        >
            Interview Scheduled
        </h1>

        <p
            style="
                margin: 8px 0 0;
                font-size: 14px;
                opacity: 0.9;
            "
        >
            Your interview has been scheduled successfully.
        </p>

    </div>


    {{-- Content --}}

    <div style="padding: 32px;">

        <p
            style="
                margin-top: 0;
                font-size: 16px;
                color: #334155;
            "
        >
            Hello
            <strong>
                {{ $interview->application->user->name }}
            </strong>,
        </p>


        <p
            style="
                color: #475569;
                line-height: 1.7;
                font-size: 14px;
            "
        >
            We are pleased to inform you that your interview has
            been scheduled for the following position.
        </p>


        {{-- Job Details --}}

        <div
            style="
                margin-top: 24px;
                padding: 20px;
                background: #f8fafc;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
            "
        >

            <p
                style="
                    margin: 0;
                    font-size: 12px;
                    color: #64748b;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                "
            >
                Position
            </p>

            <p
                style="
                    margin: 6px 0 0;
                    font-size: 18px;
                    font-weight: bold;
                    color: #0f172a;
                "
            >
                {{ $interview->application->job->title }}
            </p>


            <p
                style="
                    margin: 6px 0 0;
                    font-size: 14px;
                    color: #475569;
                "
            >
                {{ $interview->application->job->company }}
            </p>

        </div>


        {{-- Interview Details --}}

        <div
            style="
                margin-top: 24px;
                padding: 20px;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
            "
        >

            <h2
                style="
                    margin-top: 0;
                    font-size: 17px;
                    color: #0f172a;
                "
            >
                Interview Details
            </h2>


            <p
                style="
                    margin: 10px 0;
                    font-size: 14px;
                    color: #475569;
                "
            >
                <strong>Date:</strong>

                {{ $interview->interview_date->format('d M Y') }}
            </p>


            <p
                style="
                    margin: 10px 0;
                    font-size: 14px;
                    color: #475569;
                "
            >
                <strong>Time:</strong>

                {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }}

                -

                {{ \Carbon\Carbon::parse($interview->end_time)->format('h:i A') }}
            </p>


            <p
                style="
                    margin: 10px 0;
                    font-size: 14px;
                    color: #475569;
                "
            >
                <strong>Platform:</strong>
                Google Meet
            </p>

        </div>


        {{-- Meeting Button --}}

        <div
            style="
                margin-top: 28px;
                text-align: center;
            "
        >

            <a
                href="{{ $interview->meeting_link }}"
                style="
                    display: inline-block;
                    padding: 14px 24px;
                    background: #4f46e5;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 10px;
                    font-size: 14px;
                    font-weight: bold;
                "
            >
                Join Google Meet
            </a>

        </div>


        {{-- Notes --}}

        @if($interview->notes)

            <div
                style="
                    margin-top: 28px;
                    padding: 18px;
                    background: #fffbeb;
                    border: 1px solid #fde68a;
                    border-radius: 10px;
                "
            >

                <p
                    style="
                        margin: 0 0 6px;
                        font-size: 13px;
                        font-weight: bold;
                        color: #92400e;
                    "
                >
                    Interview Notes
                </p>

                <p
                    style="
                        margin: 0;
                        font-size: 13px;
                        line-height: 1.6;
                        color: #78350f;
                    "
                >
                    {{ $interview->notes }}
                </p>

            </div>

        @endif


        <p
            style="
                margin-top: 30px;
                font-size: 14px;
                line-height: 1.7;
                color: #475569;
            "
        >
            Please join the meeting a few minutes before the
            scheduled time.
        </p>


        <p
            style="
                margin-bottom: 0;
                font-size: 14px;
                color: #475569;
            "
        >
            Regards,<br>
            <strong>Recruitment Team</strong>
        </p>

    </div>


    {{-- Footer --}}

    <div
        style="
            padding: 20px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        "
    >

        <p
            style="
                margin: 0;
                font-size: 12px;
                color: #94a3b8;
            "
        >
            This is an automated email from Job Portal.
        </p>

    </div>

</div>

</body>

</html>