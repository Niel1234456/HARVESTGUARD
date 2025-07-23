<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/forgot-password.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>

<body>

<div class="container">
    <center>
        <h2>Forgot Password</h2>
        <form action="{{ route('farmer.farmer.verify.fullname') }}" method="POST">
            @csrf
            <label for="first_name">Unang Pangalan:</label>
            <input class="first_name" type="text" name="first_name" required>
            <br>
            <label for="birth_date">Petsa ng Kapanganakan:</label>
            <input class="birth_date" type="date" name="birth_date" required>
            <br>
            <button type="submit" class="button">Patunayan</button>
        </form>

        @if($errors->any())
            <div style="color: red;">{{ $errors->first() }}</div>
        @endif
    </center>
</div>

</body>
