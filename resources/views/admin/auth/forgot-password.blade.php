<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/forgot-password.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>

</style>
<body>
<div class="container">
    <center><h2>Forgot Password</h2></center>
    @if (session('status'))
        <p style="color: green;">{{ session('status') }}</p>
    @endif
    <form action="{{ route('admin.admin.forgot-password') }}" method="POST">
        @csrf
        <label>Email:</label>
        <input class="name" type="email" name="email" required> <br> 
       <center><button type="submit" class="button">Send Reset Link</button></center> 
    </form>
</div>
</body>
</html>
