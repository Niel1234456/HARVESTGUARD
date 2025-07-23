<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for dynamic effects */
        body {
            background: linear-gradient(120deg, #e0f7fa, #ffffff);
            font-family: 'Inter', sans-serif;
        }

        .container {
            background: #ffffff;
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            padding: 2rem;
            animation: fadeIn 0.8s ease-in-out;
        }

        .button-green {
            background: linear-gradient(135deg, #10b981, #059669);
            transition: all 0.3s ease-in-out;
        }

        .button-green:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #059669, #047857);
            box-shadow: 0px 6px 15px rgba(16, 185, 129, 0.3);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container mx-auto mt-20 max-w-md p-8">
        <h1 class="text-4xl font-bold text-gray-800 text-center">
            Verify Your Email
        </h1>
        <p class="mt-6 text-center text-gray-600 leading-relaxed">
            A verification link has been sent to your email address. Please check your inbox to verify your email.
        </p>
        <p class="mt-4 text-center text-gray-600 leading-relaxed">
            Didn't receive the email? Request a new verification link below.
        </p>
        <div class="mt-8 text-center">
            <form method="POST" action="{{ route('admin.verification.resend') }}">
                @csrf
                <button
                    type="submit"
                    class="button-green text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl">
                    Resend Verification Link
                </button>
            </form>
        </div>
    </div>
</body>
</html>
