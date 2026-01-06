<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Childcare Management</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2 style="color: #4f46e5;">Welcome to Our Team, {{ $staff->name }}!</h2>
        
        <p>We are excited to have you on board as a <strong>{{ $staff->specialization ?? 'Caregiver' }}</strong>.</p>
        
        <p>Your account has been created. Please use the following credentials to log in to your staff dashboard:</p>
        
        <div style="background-color: #f9fafb; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Email:</strong> {{ $staff->email }}</p>
            <p style="margin: 5px 0;"><strong>Password:</strong> {{ $password }}</p>
        </div>
        
        <p><em>For security reasons, we recommend that you change your password after your first login.</em></p>
        
        <p>You can login here: <a href="{{ route('login') }}" style="color: #4f46e5; text-decoration: none;">Login to Portal</a></p>
        
        <p>Best regards,<br>The Admin Team</p>
    </div>
</body>
</html>
