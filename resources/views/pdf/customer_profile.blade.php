<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ACI Motors - Customer Profile</title>
    <style>
@page {
    margin: 15px 20px 40px 20px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    color: #222;
    padding: 0 10px;
}

/* ================= HEADER ================= */
.header {
    width: 100%;
    padding: 8px 0 10px 0;
    border-bottom: 2px solid #9e9b9b;
    margin-bottom: 0;
}

.header-table {
    width: 100%;
}

.header-logo {
    width: 45px;
    height: 45px;
}

.header-customer-photo {
    width: 80px;
    height: 100px;
    object-fit: cover;
    border: 1px solid #94a3b8;
}

.header-title {
    font-size: 18px;
    font-weight: bold;
    color: #0c0c0c;
    margin: 0;
    letter-spacing: 1px;
}

.header-address {
    font-size: 9px;
    color: #555;
    margin: 2px 0 0 0;
}

/* ================= SECTION TITLE ================= */
.section-title {
    background: #2c3e50;
    color: #ffffff;
    text-align: center;
    padding: 7px 0;
    font-size: 12px;
    font-weight: bold;
    margin: 8px 0 10px 0;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-bottom: 3px solid #1a252f;
}

/* ================= FORM TABLE ================= */
.form-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0 auto 14px auto;
    font-size: 9px;
    table-layout: fixed;
    border: 1px solid #8b8b8b;
    border-radius: 2px;
}

.form-table td {
    padding: 2px 8px;
    vertical-align: middle;
    border: 1px solid #c5c5c5;
    line-height: 1.1;
}

.form-table tr:nth-child(even) td.form-value {
    background: #fafbfc;
}

.form-table .form-label {
    font-weight: 700;
    color: #1a1a2e;
    white-space: nowrap;
    font-size: 9px;
    width: 18%;
    background: #e8eaed;
    border-right: 2px solid #5a6c7e;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.form-table .form-value {
    color: #1a1a1a;
    font-size: 9px;
    height: 16px;
    width: 36%;
    padding-left: 10px;
    background: #ffffff;
    font-weight: 400;
}

/* ================= PERSONS SECTION ================= */
.persons-table {
    width: 100%;
    border-collapse: collapse;
}

.persons-table > tr > td,
.persons-table > tbody > tr > td {
    width: 50%;
    vertical-align: top;
    padding: 0 8px 0 0;
}

.persons-table > tr > td:last-child,
.persons-table > tbody > tr > td:last-child {
    padding: 0 0 0 8px;
}

/* Person Title */
.person-title {
    background: #2c3e50;
    color: #ffffff;
    padding: 5px 0;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: 0.5px;
    margin-top: 10px;
    margin-bottom: 8px;
    text-align: center;
    text-transform: uppercase;
    border-bottom: 2px solid #1a252f;
}

/* ================= PHOTO (Passport Size) ================= */
.photo-box {
    width: 91px;
    height: 117px;
    border: 1px solid #94a3b8;
    overflow: hidden;
    background: #fff;
    text-align: center;
    margin: 8px auto 0 auto;
}

.photo-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-placeholder {
    font-size: 36px;
    color: #b0b0b0;
    padding-top: 18px;
    text-align: center;
}

.photo-label {
    font-size: 8px;
    color: #555;
    text-align: center;
    margin-top: 2px;
    margin-bottom: 6px;
}

/* ================= SIGNATURE ================= */
.signature-box {
    width: 160px;
    height: 50px;
    text-align: center;
    margin: 4px auto 2px auto;
}

.signature-box img {
    max-width: 100%;
    max-height: 45px;
    object-fit: contain;
}

.signature-line {
    width: 120px;
    height: 1px;
    background: #666;
    margin: 2px auto 2px auto;
}

.signature-label {
    font-size: 8px;
    color: #555;
    text-align: center;
    margin-bottom: 8px;
}

/* ================= NID (Large Display) ================= */
.nid-label {
    font-size: 9px;
    font-weight: 600;
    color: #333;
    text-align: center;
    margin-top: 10px;
    margin-bottom: 4px;
    padding: 3px 0;
    background: #f0efef;
}

.nid-box {
    width: 100%;
    height: 420px;
    margin: 0 auto 6px auto;
    border: 1px solid #94a3b8;
    overflow: hidden;
    text-align: center;
    background: #fff;
}

.nid-box img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

/* ================= FOOTER ================= */
.footer {
    position: fixed;
    bottom: -25px;
    right: 0;
    font-size: 9px;
    color: #374151;
}

/* ================= PAGE BREAK ================= */
.page-break {
    page-break-before: always;
}

/* ================= ATTACHMENTS ================= */
.attachment-page {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 40px;
    padding: 0 10px;
}

.attachment-section-title {
    background: #2c3e50;
    color: #ffffff;
    text-align: center;
    padding: 4px 0;
    font-size: 12px;
    font-weight: bold;
    letter-spacing: 1px;
    margin: 0 0 3px 0;
    text-transform: uppercase;
    border-bottom: 3px solid #1a252f;
}

.attachment-container {
    width: 100%;
    margin: 0 auto;
    text-align: center;
    background: #fff;
    padding: 0;
}

.attachment-img {
    width: 100%;
    height: 950px;
    display: block;
    margin: 0 auto;
}
</style>

</head>
<body>

@php
    function getAttachmentImage($attachments, $type) {
        if (isset($attachments[$type]) && !empty($attachments[$type][0]->AttachmentRaw)) {
            $raw = $attachments[$type][0]->AttachmentRaw;
            if (strpos($raw, 'data:image') === 0) {
                return $raw;
            }
            return 'https://webapps.acibd.com/api/KYCMotors/storage/' . ltrim($raw, '/');
        }
        return null;
    }

    function getAllAttachmentImages($attachments, $type) {
        $images = [];
        if (isset($attachments[$type])) {
            foreach ($attachments[$type] as $att) {
                if (!empty($att->AttachmentRaw)) {
                    $raw = $att->AttachmentRaw;
                    if (strpos($raw, 'data:image') === 0) {
                        $images[] = $raw;
                    } else {
                        $images[] = 'https://webapps.acibd.com/api/KYCMotors/storage/' . ltrim($raw, '/');
                    }
                }
            }
        }
        return $images;
    }

    $customerPhoto = getAttachmentImage($attachments, 'Customer Photo');
    $guarantorPhoto = getAttachmentImage($attachments, 'Guarantor Photo');
    $customerSignature = getAttachmentImage($attachments, 'Customer Signature');
    $guarantorSignature = getAttachmentImage($attachments, 'Guarantor Signature');
    $customerNIDs = getAllAttachmentImages($attachments, 'Customer NID');
    $guarantorNIDs = getAllAttachmentImages($attachments, 'Guarantor NID');

    $otherTypes = ['Bio Data', 'Trade License', 'Agreement', 'Signature Verification', 'Cheque'];
@endphp

<!-- Page Number Footer -->
<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script('
            $text = "Page-" . str_pad($PAGE_NUM, 2, "0", STR_PAD_LEFT);
            $font = $fontMetrics->getFont("Arial", "normal");
            $size = 9;
            $x = $pdf->get_width() - 70;
            $y = $pdf->get_height() - 25;
            $pdf->text($x, $y, $text, $font, $size, array(0.2, 0.2, 0.2));
        ');
    }
</script>

<!-- ========== PAGE 1: CUSTOMER PROFILE ========== -->

<!-- Header -->
<div class="header">
    <table class="header-table">
        <tr>
            <td style="width: 100px; vertical-align: middle;">
                <img src="https://webapps.acibd.com/api/KYCMotors/logo/logo.png" class="header-logo" alt="Logo">
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <p class="header-title">ACI Motors</p>
                <p class="header-address">245 Tejgaon I/A. Dhaka-1208, Bangladesh</p>
            </td>
            <td style="width: 100px;"><img src="{{ $customerPhoto }}" class="header-customer-photo" alt="Customer Photo"></td>
        </tr>
    </table>
</div>

<!-- Customer Profile Title -->
<div class="section-title">Customer Profile</div>

<!-- Form Fields -->
<table class="form-table">
    <tr>
        <td class="form-label">Customer Code</td>
        <td class="form-value">{{ $customer->CustomerCode ?? '' }}</td>
        <td class="form-label">Business</td>
        <td class="form-value">
            @if ($customer->Business == 'F')
                Foton
            @elseif ($customer->Business == 'Q')
                Tractor
            @endif
        </td>
    </tr>
    <tr>
        <td class="form-label">Customer Name</td>
        <td class="form-value">{{ $customer->CustomerName ?? '' }}</td>
        <td class="form-label">Down Payment</td>
        <td class="form-value" style="text-align: right; padding-right: 8px;">{{ $customer->downPayment ? number_format((float)$customer->downPayment, 2) : '' }}</td>
    </tr>
    <tr>
        <td class="form-label">Father's Name</td>
        <td class="form-value">{{ $customer->FatherName ?? '' }}</td>
        <td class="form-label">Finance Amount</td>
        <td class="form-value" style="text-align: right; padding-right: 8px;">{{ $customer->FinanceAmount ? number_format((float)$customer->FinanceAmount, 2) : '' }}</td>
    </tr>
    <tr>
        <td class="form-label">Address</td>
        <td class="form-value">{{ $customer->Address ?? '' }}</td>
        <td class="form-label">Tenure</td>
        <td class="form-value" style="text-align: right; padding-right: 8px;">{{ $customer->NoOfInstallment ?? '' }}</td>
    </tr>
    <tr>
        <td class="form-label">NID Number</td>
        <td class="form-value">{{ $customer->NID ?? '' }}</td>
        <td class="form-label">Matured Amount</td>
        <td class="form-value" style="text-align: right; padding-right: 8px;">{{ $customer->MaturedAmount ? number_format((float)$customer->MaturedAmount, 2) : '' }}</td>
    </tr>
    <tr>
        <td class="form-label">Contact</td>
        <td class="form-value">{{ $customer->Contact ?? '' }}</td>
        <td class="form-label">Overdue Amount</td>
        <td class="form-value" style="text-align: right; padding-right: 8px;">{{ $customer->OverDueTaka ? number_format((float)$customer->OverDueTaka, 2) : '' }}</td>
    </tr>
    <tr>
        <td class="form-label">Invoice Date</td>
        <td class="form-value">{{ $customer->InvoiceDate ? \Carbon\Carbon::parse($customer->InvoiceDate)->format('d M Y') : '' }}</td>
        <td class="form-label">Non Matured Amount</td>
        <td class="form-value" style="text-align: right; padding-right: 8px;">{{ $customer->NonMaturedAmount ? number_format((float)$customer->NonMaturedAmount, 2) : '' }}</td>
    </tr>
    <tr>
        <td class="form-label">Territory</td>
        <td class="form-value">{{ $customer->TTYName ?? '' }}</td>
        <td class="form-label">Outstanding Amount</td>
        <td class="form-value" style="text-align: right; padding-right: 8px;">{{ $customer->OutstandingAmount ? number_format((float)$customer->OutstandingAmount, 2) : '' }}</td>
    </tr>
    <tr>
        <td class="form-label">Box No.</td>
        <td class="form-value">{{ $customer->BoxNo ?? '' }}</td>
        <td class="form-label"></td>
        <td class="form-value" style="text-align: right; padding-right: 8px;"></td>
    </tr>
</table>

<!-- Customer & Guarantor Section -->
<table class="persons-table">
    <tr>
        <!-- CUSTOMER -->
        <td>
            <div class="person-title">Customer</div>

            <div class="photo-box">
                @if($customerPhoto)
                    <img src="{{ $customerPhoto }}" alt="Customer Photo">
                @else
                    <div class="photo-placeholder">&#128100;</div>
                @endif
            </div>
            <div class="photo-label">Photo</div>

            <div class="signature-box">
                @if($customerSignature)
                    <img src="{{ $customerSignature }}" alt="Signature">
                @endif
            </div>
            <div class="signature-line"></div>
            <div class="signature-label">Signature</div>

            @if(count($customerNIDs) > 0)
                <div class="nid-label">National ID (NID)</div>
                @foreach($customerNIDs as $nid)
                    <div class="nid-box">
                        <img src="{{ $nid }}" alt="Customer NID">
                    </div>
                @endforeach
            @endif
        </td>

        <!-- GUARANTOR -->
        <td>
            <div class="person-title">Guarantor</div>

            <div class="photo-box">
                @if($guarantorPhoto)
                    <img src="{{ $guarantorPhoto }}" alt="Guarantor Photo">
                @else
                    <div class="photo-placeholder">&#128100;</div>
                @endif
            </div>
            <div class="photo-label">Photo</div>

            <div class="signature-box">
                @if($guarantorSignature)
                    <img src="{{ $guarantorSignature }}" alt="Signature">
                @endif
            </div>
            <div class="signature-line"></div>
            <div class="signature-label">Signature</div>

            @if(count($guarantorNIDs) > 0)
                <div class="nid-label">National ID (NID)</div>
                @foreach($guarantorNIDs as $nid)
                    <div class="nid-box">
                        <img src="{{ $nid }}" alt="Guarantor NID">
                    </div>
                @endforeach
            @endif
        </td>
    </tr>
</table>

<!-- ========== OTHER ATTACHMENT PAGES ========== -->
@foreach($otherTypes as $type)
    @if(isset($attachments[$type]) && count($attachments[$type]) > 0)
        @foreach($attachments[$type] as $attachment)
            @if(!empty($attachment->AttachmentRaw))
                <div class="page-break"></div>

                <!-- Header on each page -->
                <div class="header" style="padding: 5px 0 5px 0; margin-bottom: 0;">
                    <table class="header-table">
                        <tr>
                            <td style="width: 50px; vertical-align: middle;">
                                <img src="https://webapps.acibd.com/api/KYCMotors/logo/logo.png" class="header-logo" alt="Logo">
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <p class="header-title">ACI Motors</p>
                                <p class="header-address">245 Tejgaon I/A. Dhaka-1208, Bangladesh</p>
                            </td>
                            <td style="width: 50px;"></td>
                        </tr>
                    </table>
                </div>

                <!-- Attachment Title -->
                <div class="attachment-section-title">{{ $type }}</div>

                <!-- Attachment Image -->
                @php
                    $imgPath = $attachment->AttachmentRaw;
                    $imgUrl = (strpos($imgPath, 'data:image') === 0)
                        ? $imgPath
                        : 'https://webapps.acibd.com/api/KYCMotors/storage/' . ltrim($imgPath, '/');
                @endphp
                <div class="attachment-container">
                    <img src="{{ $imgUrl }}" class="attachment-img" alt="{{ $type }}">
                </div>
            @endif
        @endforeach
    @endif
@endforeach

</body>
</html>
