<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Employment Offer Letter</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e293b;
            line-height: 1.6;
            font-size: 13px;
            margin: 40px;
        }

        .header {
            border-bottom: 2px solid #F97316;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .company {
            font-size: 24px;
            font-weight: bold;
            color: #111111;
        }

        .company-accent {
            color: #F97316;
        }

        .subtitle {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        .section {
            margin-top: 22px;
        }

        .label {
            font-weight: bold;
            color: #334155;
            width: 170px;
            display: inline-block;
        }

        .details {
            margin-top: 12px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 6px;
        }

        .details p {
            margin: 6px 0;
        }

        .version-badge {
            display: inline-block;
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fed7aa;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            margin-left: 10px;
        }

        .signature-table {
            width: 100%;
            margin-top: 45px;
            border-collapse: collapse;
        }

        .signature-box {
            width: 48%;
            vertical-align: top;
            padding: 12px;
            border: 1px dashed #cbd5e1;
            background: #ffffff;
            height: 90px;
        }

        .footer {
            margin-top: 50px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            font-size: 11px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="company">
                        {{ $offer->application->job->company }}
                    </div>
                    <div class="subtitle">
                        Official Employment Offer
                    </div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <span class="version-badge">
                        Offer Version {{ $offer->version ?? 1 }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    {{-- Date & Candidate --}}
    <p>
        <strong>Date of Issue:</strong> {{ now()->format('d F Y') }}
    </p>

    <p>
        Dear <strong>{{ $offer->application->user->name }}</strong>,
    </p>

    {{-- Introduction --}}
    <p>
        We are delighted to extend an offer of employment for the position of 
        <strong>{{ $offer->application->job->title }}</strong> with 
        <strong>{{ $offer->application->job->company }}</strong>.
    </p>

    {{-- Offer Details --}}
    <div class="section">
        <h3 style="color: #111111; margin-bottom: 8px;">Key Employment Terms</h3>
        <div class="details">
            <p>
                <span class="label">Position Title:</span>
                <strong>{{ $offer->application->job->title }}</strong>
            </p>
            <p>
                <span class="label">Employing Entity:</span>
                {{ $offer->application->job->company }}
            </p>
            <p>
                <span class="label">Annual CTC:</span>
                <strong>₹{{ number_format($offer->salary, 2) }}</strong>
            </p>
            <p>
                <span class="label">Scheduled Joining Date:</span>
                <strong>{{ $offer->joining_date->format('d F Y') }}</strong>
            </p>
            @if($offer->offer_expiry_date)
                <p>
                    <span class="label">Offer Expiry Date:</span>
                    {{ $offer->offer_expiry_date->format('d F Y') }}
                </p>
            @endif
        </div>
    </div>

    {{-- Terms --}}
    <div class="section">
        <h3 style="color: #111111; margin-bottom: 8px;">Acceptance & Signing Procedure</h3>
        <p>
            This offer is contingent upon satisfactory completion of background verification. 
            To confirm your acceptance, please sign below, scan or save this document as a PDF, 
            and upload the signed copy through your Candidate Portal.
        </p>
    </div>

    @if($offer->notes)
        <div class="section">
            <h3 style="color: #111111; margin-bottom: 8px;">Additional Remarks</h3>
            <p style="background: #fffbeb; border: 1px solid #fde68a; padding: 10px; border-radius: 4px; color: #92400e;">
                {{ $offer->notes }}
            </p>
        </div>
    @endif

    {{-- Dual Signature Area --}}
    <table class="signature-table">
        <tr>
            <td class="signature-box" style="margin-right: 4%;">
                <p style="margin: 0; font-size: 11px; font-weight: bold; color: #64748b;">ISSUED ON BEHALF OF COMPANY</p>
                <p style="margin-top: 35px; margin-bottom: 0; font-weight: bold; color: #111111;">Authorized Signatory</p>
                <p style="margin: 0; font-size: 11px; color: #64748b;">{{ $offer->application->job->company }}</p>
            </td>
            <td style="width: 4%;"></td>
            <td class="signature-box">
                <p style="margin: 0; font-size: 11px; font-weight: bold; color: #64748b;">CANDIDATE ACCEPTANCE SIGNATURE</p>
                <p style="margin-top: 35px; margin-bottom: 0; font-weight: bold; color: #111111;">{{ $offer->application->user->name }}</p>
                <p style="margin: 0; font-size: 11px; color: #64748b;">Date: ____________________</p>
            </td>
        </tr>
    </table>

    {{-- Footer --}}
    <div class="footer">
        {{ $offer->application->job->company }} • Confidential Employment Offer Letter (Version {{ $offer->version ?? 1 }})
    </div>

</body>
</html>