<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Interview Rescheduled</title>

</head>

<body
    style="
        margin:0;
        padding:0;
        background:#f8fafc;
        font-family:Arial,Helvetica,sans-serif;
    "
>

<div
    style="
        max-width:600px;
        margin:40px auto;
        background:#ffffff;
        border:1px solid #e2e8f0;
        border-radius:16px;
        overflow:hidden;
    "
>

    {{-- Header --}}

    <div
        style="
            padding:28px;
            background:#4f46e5;
            color:#ffffff;
        "
    >

        <h1 style="margin:0;font-size:24px;">
            Interview Rescheduled
        </h1>

        <p style="margin:8px 0 0;font-size:14px;">
            Your interview details have been updated.
        </p>

    </div>


    {{-- Content --}}

    <div style="padding:32px;">

        <p style="font-size:16px;color:#334155;">

            Hello
            <strong>
                {{ $interview->application->user->name }}
            </strong>,

        </p>


        <p
            style="
                color:#475569;
                font-size:14px;
                line-height:1.7;
            "
        >

            Your interview for

            <strong>
                {{ $interview->application->job->title }}
            </strong>

            at

            <strong>
                {{ $interview->application->job->company }}
            </strong>

            has been rescheduled.

        </p>


        {{-- New Details --}}

        <div
            style="
                margin-top:24px;
                padding:22px;
                background:#eef2ff;
                border:1px solid #c7d2fe;
                border-radius:12px;
            "
        >

            <h2
                style="
                    margin:0 0 18px;
                    font-size:17px;
                    color:#312e81;
                "
            >
                New Interview Details
            </h2>


            <p
                style="
                    margin:10px 0;
                    font-size:14px;
                    color:#3730a3;
                "
            >

                <strong>Date:</strong>

                {{ $interview->interview_date->format('d M Y') }}

            </p>


            <p
                style="
                    margin:10px 0;
                    font-size:14px;
                    color:#3730a3;
                "
            >

                <strong>Time:</strong>

                {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }}

                -

                {{ \Carbon\Carbon::parse($interview->end_time)->format('h:i A') }}

            </p>


            <p
                style="
                    margin:10px 0;
                    font-size:14px;
                    color:#3730a3;
                "
            >

                <strong>Platform:</strong>
                Google Meet

            </p>

        </div>


        {{-- Meeting Button --}}

        <div
            style="
                margin-top:28px;
                text-align:center;
            "
        >

            <a
                href="{{ $interview->meeting_link }}"
                style="
                    display:inline-block;
                    padding:14px 24px;
                    background:#4f46e5;
                    color:#ffffff;
                    text-decoration:none;
                    border-radius:10px;
                    font-size:14px;
                    font-weight:bold;
                "
            >
                Join Google Meet
            </a>

        </div>


        {{-- Notes --}}

        @if($interview->notes)

            <div
                style="
                    margin-top:28px;
                    padding:18px;
                    background:#fffbeb;
                    border:1px solid #fde68a;
                    border-radius:10px;
                "
            >

                <p
                    style="
                        margin:0 0 6px;
                        font-size:13px;
                        font-weight:bold;
                        color:#92400e;
                    "
                >
                    Interview Notes
                </p>

                <p
                    style="
                        margin:0;
                        font-size:13px;
                        line-height:1.6;
                        color:#78350f;
                    "
                >
                    {{ $interview->notes }}
                </p>

            </div>

        @endif


        <p
            style="
                margin-top:30px;
                font-size:14px;
                line-height:1.7;
                color:#475569;
            "
        >

            Please make a note of the new date and time and join
            the meeting a few minutes before the scheduled time.

        </p>


        <p
            style="
                margin-bottom:0;
                font-size:14px;
                color:#475569;
            "
        >

            Regards,<br>

            <strong>
                Recruitment Team
            </strong>

        </p>

    </div>


    {{-- Footer --}}

    <div
        style="
            padding:20px;
            background:#f8fafc;
            border-top:1px solid #e2e8f0;
            text-align:center;
        "
    >

        <p
            style="
                margin:0;
                font-size:12px;
                color:#94a3b8;
            "
        >
            This is an automated email from Job Portal.
        </p>

    </div>

</div>

</body>

</html>