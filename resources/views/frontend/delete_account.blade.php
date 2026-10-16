<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Your Account - {{ config('app.name', 'Vlocus') }}</title>
    <meta name="description" content="How to delete your {{ config('app.name', 'Vlocus') }} driver account and what data is removed.">
    <style>
        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; line-height: 1.6; color: #1f2937; background: #f9fafb; margin: 0; }
        main { max-width: 760px; margin: 0 auto; padding: 32px 16px 64px; }
        h1 { font-size: 1.8rem; margin-bottom: .25rem; }
        h2 { font-size: 1.2rem; margin-top: 2rem; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 20px; }
        a { color: #2563eb; }
    </style>
</head>
<body>
<main>
    <h1>Delete your {{ config('app.name', 'Vlocus') }} account</h1>
    <p>This page explains how drivers using the {{ config('app.name', 'Vlocus') }} app can permanently delete their account and data.</p>

    <h2>Option 1: Delete from the app (fastest)</h2>
    <div class="card">
        <ol>
            <li>Open the {{ config('app.name', 'Vlocus') }} driver app and log in.</li>
            <li>Go to <strong>Profile</strong> and tap <strong>Delete Account</strong>.</li>
            <li>Confirm the deletion. Your account is removed immediately and you are logged out.</li>
        </ol>
    </div>

    <h2>Option 2: Request deletion by email</h2>
    <div class="card">
        <p>If you can no longer access the app, email us from the contact details below with the subject <strong>"Delete my account"</strong>, and include your registered phone number. We verify the request and delete the account within 7 days.</p>
        @if (config('services.support_email'))
            <p>Email: <a href="mailto:{{ config('services.support_email') }}?subject=Delete%20my%20account">{{ config('services.support_email') }}</a></p>
        @endif
    </div>

    <h2>What is deleted</h2>
    <ul>
        <li>Your account login and active sessions</li>
        <li>Your driver profile, documents and uploaded media</li>
        <li>Bank account details saved on your profile</li>
        <li>Location history linked to your account</li>
    </ul>

    <h2>What is kept</h2>
    <ul>
        <li>Past delivery records are retained for audit and accounting purposes. They are no longer linked to you (the driver reference is removed).</li>
    </ul>

    <p>Deletion is permanent and cannot be undone. See our <a href="{{ route('home.privacy') }}">Privacy Policy</a> for more.</p>
</main>
</body>
</html>
