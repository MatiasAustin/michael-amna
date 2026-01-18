<!DOCTYPE html>
<html>
<head>
    <title>Verification Code</title>
</head>
<body style="font-family: sans-serif; color: #333;">
    <div style="max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        <h2 style="color: #3d1516; text-align: center;">Verification Code</h2>
        <p>Hello Admin,</p>
        <p>You requested to change your password. Please use the following One-Time Password (OTP) to verify this request:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 24px; font-weight: bold; background: #f3ecdc; padding: 10px 20px; border-radius: 4px; letter-spacing: 5px; color: #3d1516;">
                {{ $otp }}
            </span>
        </div>

        <p>This code is valid for 10 minutes.</p>
        <p>If you did not request this change, please ignore this email and check your account security.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin-top: 30px;">
        <p style="font-size: 12px; color: #999; text-align: center;">Amma & Michael Wedding Admin Panel</p>
    </div>
</body>
</html>
