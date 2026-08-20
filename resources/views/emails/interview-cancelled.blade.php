<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Interview Cancelled</title>

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

    <div
        style="
            padding:28px;
            background:#dc2626;
            color:white;
        "
    >

        <h1 style="margin:0;font-size:24px;">
            Interview Cancelled
        </h1>

        <p style="margin:8px 0 0;font-size:14px;">
            Your previously scheduled interview has been cancelled.
        </p>

    </div>


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

            We would like to inform you that your interview for

            <strong>
                {{ $interview->application->job->title }}
            </strong>

            at

            <strong>
                {{ $interview->application->job->company }}
            </strong>

            has been cancelled.

        </p>


        <div
            style="
                margin-top:24px;
                padding:20px;
                background:#fef2f2;
                border:1px solid #fecaca;
                border-radius:12px;
            "
        >

            <p
                style="
                    margin:0;
                    font-size:13px;
                    color:#991b1b;
                "
            >

                Previous Interview Date

            </p>


            <p
                style="
                    margin:6px 0 0;
                    font-weight:bold;
                    color:#7f1d1d;
                "
            >

                {{ $interview->interview_date->format('d M Y') }}

                at

                {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }}

            </p>

        </div>


        <p
            style="
                margin-top:28px;
                color:#475569;
                font-size:14px;
                line-height:1.7;
            "
        >

            If the interview is rescheduled, you will receive
            another email with the new interview details.

        </p>


        <p
            style="
                margin-bottom:0;
                color:#475569;
                font-size:14px;
            "
        >

            Regards,<br>

            <strong>
                Recruitment Team
            </strong>

        </p>

    </div>


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