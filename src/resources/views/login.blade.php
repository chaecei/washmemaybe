<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Laundry Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#D4F6FF] min-h-screen flex items-center justify-center font-sans">
    <div class="bg-[#FBFBFB] w-full max-w-5xl mx-4 md:mx-auto flex flex-col md:flex-row items-center justify-between rounded-lg shadow-xl overflow-hidden">
        
        <!-- Left: Login Form -->
        <div class="w-full md:w-1/2 p-10">
            <h1 class="text-3xl font-bold text-[#1b1b1b] mb-6">Laundry Shop Name</h1>
            <p class="text-sm text-gray-600 mb-4 font-medium">Login as Admin</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <input type="email" name="email" placeholder="Username/Email"
                    class="w-full mb-4 px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#C6E7FF]" required
                    value="{{ old('email') }}">

                <input type="password" name="password" placeholder="********"
                    class="w-full mb-4 px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#C6E7FF]" required>

                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center text-sm text-gray-700">
                        <input type="checkbox" class="mr-2" name="remember"> Keep me Signed in
                    </label>
                    <span class="text-sm text-gray-400 italic">Forgotten your password?</span>
                </div>

                <button type="submit"
                    class="w-full bg-[#C6E7FF] text-[#1b1b1b] font-semibold py-2 px-4 rounded hover:bg-[#FFDDae] transition duration-300">
                    LOGIN
                </button>

                @if ($errors->any())
                    <div class="mt-4 text-sm text-red-600">{{ $errors->first() }}</div>
                @endif

                @if (session('success'))
                    <div class="mt-4 text-sm text-green-600">{{ session('success') }}</div>
                @endif
            </form>
        </div>

        <!-- Right: Image -->
        <div class="w-full md:w-1/2 h-64 md:h-full bg-[#C6E7FF] flex items-center justify-center p-6">
        <img src="{{ asset('laundry-image.png') }}" alt="Laundry Logo"> 
        </div>
    </div>
</body>
</html>
