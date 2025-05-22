<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body class="bg-[#D4F6FF] min-h-screen flex items-center justify-center font-sans">
    <div class="bg-[#FBFBFB] w-full max-w-5xl mx-4 md:mx-auto flex flex-col md:flex-row items-center justify-between rounded-lg shadow-xl overflow-hidden">
        
        <div class="w-full md:w-1/2 p-10">
            <h1 class="text-3xl font-bold text-[#1b1b1b] mb-6">Laundry Shop Name</h1>
            <p class="text-sm text-gray-600 mb-4 font-medium">Login as Admin</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <input type="email" name="email" placeholder="Username/Email"
                    class="w-full mb-4 px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#C6E7FF]" required
                    value="{{ old('email') }}">

                <div class="relative w-full mb-4">
                    <input type="password" id="passwordInput" name="password" placeholder="********"
                        class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#C6E7FF]" required>
                    <button type="button" onclick="togglePassword(this)" 
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                        <i class="fa-solid fa-eye"></i>
                    </button>
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

        <div class="w-full md:w-1/2 h-64 md:h-full bg-[#C6E7FF] flex items-center justify-center p-6">
        <img src="{{ asset('laundry-image.png') }}" alt="Laundry Logo"> 
        </div>
    </div>

    <script>
        function togglePassword(button) {
            const input = document.getElementById("passwordInput");
            const icon = button.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

    
</body>
</html>
