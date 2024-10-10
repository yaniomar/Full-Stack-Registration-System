<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Email Verification</h2>

        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <p>Please check your email for a verification link before proceeding.</p>

        <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Resend Verification Email</button>
        </form>
    </div>
</body>
</html>
