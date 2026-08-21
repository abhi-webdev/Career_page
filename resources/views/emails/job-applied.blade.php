<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Received</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0A0A0A;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #FFFFFF;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #0A0A0A;
            padding-bottom: 40px;
        }
        .main {
            background-color: #141414;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            color: #FFFFFF;
            border-radius: 16px;
            border: 1px solid #262626;
            overflow: hidden;
        }
        .header {
            background-color: #1A1A1A;
            padding: 28px 32px;
            border-bottom: 1px solid #262626;
        }
        .brand {
            font-size: 20px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.5px;
        }
        .brand-accent {
            color: #F97316;
        }
        .body-content {
            padding: 32px;
        }
        .title {
            font-size: 22px;
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 16px 0;
            letter-spacing: -0.5px;
        }
        .subtitle {
            font-size: 14px;
            line-height: 22px;
            color: #A1A1A1;
            margin: 0 0 24px 0;
        }
        .card {
            background-color: #1A1A1A;
            border-radius: 12px;
            border: 1px solid #262626;
            padding: 20px;
            margin-bottom: 24px;
        }
        .card-row {
            margin-bottom: 12px;
        }
        .card-row:last-child {
            margin-bottom: 0;
        }
        .label {
            font-size: 11px;
            font-weight: 700;
            color: #6B6B6B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 4px;
        }
        .value {
            font-size: 14px;
            font-weight: 600;
            color: #FFFFFF;
        }
        .highlight {
            color: #F97316;
        }
        .button {
            display: inline-block;
            background-color: #F97316;
            color: #FFFFFF !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
        }
        .footer {
            padding: 24px 32px;
            background-color: #0A0A0A;
            text-align: center;
            font-size: 12px;
            color: #6B6B6B;
            border-top: 1px solid #262626;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main" align="center" cellpadding="0" cellspacing="0">
            {{-- Header --}}
            <tr>
                <td class="header">
                    <span class="brand">Job<span class="brand-accent">Portal</span></span>
                    <span style="float: right; font-size: 12px; font-weight: 700; color: #F97316; text-transform: uppercase; letter-spacing: 1px;">
                        Application Confirmation
                    </span>
                </td>
            </tr>

            {{-- Body --}}
            <tr>
                <td class="body-content">
                    <h1 class="title">Application Received!</h1>
                    <p class="subtitle">
                        Hi <strong>{{ $application->user->name }}</strong>,<br><br>
                        Thank you for applying for the <strong style="color: #FFFFFF;">{{ $application->job->title }}</strong> position at <strong style="color: #F97316;">{{ $application->job->company }}</strong>. We have successfully received your application and attached documents.
                    </p>

                    <div class="card">
                        <div class="card-row">
                            <span class="label">Position Applied</span>
                            <span class="value">{{ $application->job->title }}</span>
                        </div>
                        <div class="card-row" style="margin-top: 12px;">
                            <span class="label">Company</span>
                            <span class="value highlight">{{ $application->job->company }}</span>
                        </div>
                        <div class="card-row" style="margin-top: 12px;">
                            <span class="label">Location & Type</span>
                            <span class="value">{{ $application->job->location ?? 'Remote' }} • {{ $application->job->job_type ?? 'Full Time' }}</span>
                        </div>
                        <div class="card-row" style="margin-top: 12px;">
                            <span class="label">Submitted Resume</span>
                            <span class="value">📄 {{ $application->resume ? $application->resume->file_name : 'Uploaded Document' }}</span>
                        </div>
                        <div class="card-row" style="margin-top: 12px;">
                            <span class="label">Submission Date</span>
                            <span class="value">{{ $application->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                    </div>

                    <p class="subtitle">
                        Our recruitment team is reviewing your profile against the role criteria. You can monitor your application stage, interview appointments, and offers directly in your candidate dashboard.
                    </p>

                    <table cellpadding="0" cellspacing="0" style="margin-top: 24px;">
                        <tr>
                            <td>
                                <a href="{{ route('applications.index') }}" class="button">
                                    Track My Application →
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            {{-- Footer --}}
            <tr>
                <td class="footer">
                    Sent with care from <strong>{{ $application->job->company }}</strong> via JobPortal.<br>
                    © {{ date('Y') }} All rights reserved.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
