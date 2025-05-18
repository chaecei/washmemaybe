<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#D4F6FF] min-h-screen flex items-center justify-center font-sans">
    <div class="bg-white p-10 rounded shadow max-w-md w-full">
        <h1 class="text-2xl font-bold mb-6">Forgot Password</h1>

        @if (session('status'))
            <div class="mb-4 text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <input type="email" name="email" placeholder="Enter your email"
                class="w-full mb-4 px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#C6E7FF]" required>

            <button type="submit" class="w-full bg-[#C6E7FF] text-[#1b1b1b] font-semibold py-2 rounded hover:bg-[#FFDDae] transition duration-300">
                Send Password Reset Link
            </button>
        </form>
    </div>
</body>
</html>
