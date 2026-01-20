<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Michael & Amna RSVP Details</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F3ECDC; font-family: 'Times New Roman', Times, serif; color: #3d1516;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #F3ECDC;">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                <!-- Main Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #e0d8c8; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 40px 0 20px 0; border-bottom: 1px solid #F3ECDC;">
                            <h1 style="margin: 0; font-family: 'Playfair Display', Georgia, serif; font-size: 32px; font-weight: 400; letter-spacing: 1px; color: #3d1516; text-transform: uppercase;">
                                Michael & Amna
                            </h1>
                            <p style="margin: 5px 0 0 0; font-family: Arial, sans-serif; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; color: #8a6d6d;">
                                Wedding Celebration
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px; text-align: center;">
                            <p style="margin: 0 0 20px 0; font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6; color: #3d1516;">
                                Dear <strong>{{ $rsvp->full_name }}</strong>,
                            </p>
                            <p style="margin: 0 0 30px 0; font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6; color: #5c4a4a;">
                                We are so excited to celebrate with you. Use the unique code below to access your invitation details and seating information.
                            </p>

                            <!-- Code Box -->
                            <div style="margin: 0 auto 30px auto; display: inline-block; padding: 15px 30px; background-color: #F9F6F0; border: 1px dashed #3d1516; letter-spacing: 4px;">
                                <span style="font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; color: #3d1516;">
                                    {{ $rsvp->unique_code }}
                                </span>
                            </div>

                            <p style="margin: 0 0 30px 0; font-family: Arial, sans-serif; font-size: 14px; color: #5c4a4a;">
                                Please click the button below to view the floor map and other details.
                            </p>

                            <!-- Button -->
                            <a href="{{ url('/floor-map') }}?code={{ urlencode($rsvp->unique_code) }}#find" 
                               style="display: inline-block; padding: 14px 28px; background-color: #3d1516; color: #F3ECDC; text-decoration: none; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; border-radius: 2px;">
                                View Invitation
                            </a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #3d1516; padding: 20px; text-align: center;">
                            <p style="margin: 0; font-family: Arial, sans-serif; font-size: 12px; color: #F3ECDC; opacity: 0.8;">
                                Can't wait to see you there! 🤍
                            </p>
                            <p style="margin: 5px 0 0 0; font-family: Arial, sans-serif; font-size: 10px; color: #F3ECDC; opacity: 0.5;">
                                &copy; {{ date('Y') }} Michael & Amna. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
