<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <!-- [Keep all meta tags and font imports] -->
    <style type="text/css" rel="stylesheet" media="all">
        /* [Previous styles remain] */
        .login-path {
            background: #f0f4f8;
            padding: 12px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <table class="email-wrapper" width="100%" role="presentation">
        <tr>
            <td align="center">
                <!-- Header Section -->
                <div class="gpon-header">
                    <img src="{{ asset('images/gpon-logo.png') }}" class="gpon-logo" alt="GPON Office">
                    <h2 style="color: white; margin-top: 15px;">All-in-One Humanitarian Project Suite</h2>
                </div>

                <!-- Main Content -->
                <table class="email-content" width="100%" role="presentation">
                    <tr>
                        <td class="content-cell">
                            <h1>Welcome to GPON Staff Portal</h1>
                            <p>Access your account at:</p>
                            
                            <div class="login-path">
                                {{ url('/staff/login') }}
                            </div>

                            <h3>Your Login Credentials:</h3>
                            <div class="credentials-box">
                                <p><strong>Email:</strong> {{ $data['email'] }}</p>
                                <p><strong>Temporary Password:</strong> {{ $data['password'] }}</p>
                            </div>

                            <a href="{{ url('/staff/login') }}" class="button" style="margin: 25px 0;">
                                ➡️ Access Staff Portal
                            </a>

                            <div class="security-note">
                                <p>🔒 For security reasons:</p>
                                <ul>
                                    <li>Change password after first login</li>
                                    <li>Enable 2FA in account settings</li>
                                    <li>Never share your credentials</li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Footer Section -->
                <div class="gpon-footer">
                    <div class="footer-links">
                        <a href="{{ url('/staff/docs') }}">📚 Staff Documentation</a>
                        <a href="{{ url('/support') }}">🛠️ Technical Support</a>
                    </div>
                    <p>GPON Office Project Management Suite<br>
                    Humanitarian & Development Solutions Platform</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>