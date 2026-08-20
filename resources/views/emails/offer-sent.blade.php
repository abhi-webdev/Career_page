<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Employment Offer</title>

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
            padding:30px;
            background:#059669;
            color:#ffffff;
        "
    >

        <h1
            style="
                margin:0;
                font-size:24px;
            "
        >
            Congratulations!
        </h1>

        <p
            style="
                margin:8px 0 0;
                font-size:14px;
            "
        >
            You have received an employment offer.
        </p>

    </div>


    {{-- Content --}}

    <div style="padding:32px;">

        <p
            style="
                margin-top:0;
                font-size:16px;
                color:#334155;
            "
        >
            Hello
            <strong>
                {{ $offer->application->user->name }}
            </strong>,
        </p>


        <p
            style="
                color:#475569;
                font-size:14px;
                line-height:1.7;
            "
        >

            We are pleased to offer you the position of

            <strong>
                {{ $offer->application->job->title }}
            </strong>

            at

            <strong>
                {{ $offer->application->job->company }}
            </strong>.

        </p>


        {{-- Offer Details --}}

        <div
            style="
                margin-top:24px;
                padding:22px;
                background:#f0fdf4;
                border:1px solid #bbf7d0;
                border-radius:12px;
            "
        >

            <h2
                style="
                    margin:0 0 18px;
                    font-size:17px;
                    color:#166534;
                "
            >
                Offer Details
            </h2>


            <p
                style="
                    margin:10px 0;
                    font-size:14px;
                    color:#166534;
                "
            >

                <strong>Position:</strong>

                {{ $offer->application->job->title }}

            </p>


            <p
                style="
                    margin:10px 0;
                    font-size:14px;
                    color:#166534;
                "
            >

                <strong>Company:</strong>

                {{ $offer->application->job->company }}

            </p>


            <p
                style="
                    margin:10px 0;
                    font-size:14px;
                    color:#166534;
                "
            >

                <strong>Annual Salary:</strong>

                ₹{{ number_format($offer->salary, 2) }}

            </p>


            <p
                style="
                    margin:10px 0;
                    font-size:14px;
                    color:#166534;
                "
            >

                <strong>Joining Date:</strong>

                {{ $offer->joining_date->format('d M Y') }}

            </p>


            @if($offer->offer_expiry_date)

                <p
                    style="
                        margin:10px 0;
                        font-size:14px;
                        color:#166534;
                    "
                >

                    <strong>Offer Valid Until:</strong>

                    {{ $offer->offer_expiry_date->format('d M Y') }}

                </p>

            @endif

        </div>


        {{-- Notes --}}

        @if($offer->notes)

            <div
                style="
                    margin-top:24px;
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
                    Additional Information
                </p>

                <p
                    style="
                        margin:0;
                        font-size:13px;
                        line-height:1.6;
                        color:#78350f;
                    "
                >
                    {{ $offer->notes }}
                </p>

            </div>

        @endif


        <p
            style="
                margin-top:28px;
                font-size:14px;
                line-height:1.7;
                color:#475569;
            "
        >

            Please review the offer details carefully. You will be
            able to accept or reject the offer from your candidate
            dashboard.

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