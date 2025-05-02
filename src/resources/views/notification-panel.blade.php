<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications – Laundry Shop Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-[#D4F6FF] min-h-screen font-sans">

  {{-- Sidebar --}}
  <aside class="w-64 bg-[#FBFBFB] flex flex-col p-6 space-y-4">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 mb-6">
    <a href="#" class="px-4 py-2 rounded bg-[#C6E7FF] font-semibold text-black">Dashboard</a>
    <a href="#" class="px-4 py-2 rounded bg-[#FFDDAE] font-semibold text-black">NOTIFICATION</a>
    <a href="#" class="px-4 py-2 rounded hover:bg-[#C6E7FF] transition">ADD ORDER</a>
    <a href="#" class="px-4 py-2 rounded hover:bg-[#C6E7FF] transition">EXPENSES</a>
    <a href="#" class="px-4 py-2 rounded hover:bg-[#C6E7FF] transition">SERVICES</a>
    <a href="#" class="px-4 py-2 rounded hover:bg-[#C6E7FF] transition">LAUNDRY STATUS</a>
    <a href="#" class="px-4 py-2 rounded hover:bg-[#C6E7FF] transition">HISTORY</a>
    <a href="#" class="px-4 py-2 rounded hover:bg-[#C6E7FF] transition">ACCOUNT INFORMATION</a>
    <div class="mt-auto space-y-1">
      <a href="#" class="text-sm text-gray-600 hover:underline">Settings</a><br>
      <a href="#" class="text-sm text-gray-600 hover:underline">Logout</a>
    </div>
  </aside>

  {{-- Main Content --}}
  <div class="flex-1 p-8">
    {{-- Header --}}
    <header class="flex justify-between items-center mb-8">
      <h1 class="text-2xl font-semibold text-[#1b1b1b]">Laundry Shop Name</h1>
      <div class="flex items-center space-x-3">
        <span class="uppercase text-sm">Admin</span>
        <img src="{{ asset('images/avatar.png') }}" alt="Admin" class="w-10 h-10 rounded-full">
      </div>
    </header>

    {{-- Filter --}}
    <div class="flex justify-end mb-4">
      <select
        class="px-3 py-2 border border-gray-300 rounded bg-[#FBFBFB] focus:outline-none focus:ring-2 focus:ring-[#C6E7FF]"
      >
        <option>Filter with</option>
        <option value="all">All</option>
        <option value="orders">Orders</option>
        <option value="users">Users</option>
      </select>
    </div>

    {{-- Notifications Table --}}
    <div class="overflow-auto">
      <table class="min-w-full border border-gray-300">
        <thead class="bg-[#C6E7FF]">
          <tr>
            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium">Date and Time</th>
            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium">Notification</th>
          </tr>
        </thead>
        <tbody>
          {{-- Example static rows; replace with @foreach($notifications as $n) ... @endforeach --}}
          <tr class="bg-white even:bg-[#FBFBFB]">
            <td class="border border-gray-300 px-4 py-2 text-sm">04/01 10:09</td>
            <td class="border border-gray-300 px-4 py-2 text-sm">New user registered into your system.</td>
          </tr>
          <tr class="bg-white even:bg-[#FBFBFB]">
            <td class="border border-gray-300 px-4 py-2 text-sm">04/01 10:30</td>
            <td class="border border-gray-300 px-4 py-2 text-sm">WIN023 laundry completed.</td>
          </tr>
          <tr class="bg-white even:bg-[#FBFBFB]">
            <td class="border border-gray-300 px-4 py-2 text-sm">04/01 13:03</td>
            <td class="border border-gray-300 px-4 py-2 text-sm">John Doe sent a laundry order.</td>
          </tr>
          <tr class="bg-white even:bg-[#FBFBFB]">
            <td class="border border-gray-300 px-4 py-2 text-sm">04/02 09:34</td>
            <td class="border border-gray-300 px-4 py-2 text-sm">Jennifer Huh sent a laundry order.</td>
          </tr>
          <tr class="bg-white even:bg-[#FBFBFB]">
            <td class="border border-gray-300 px-4 py-2 text-sm">04/02 10:56</td>
            <td class="border border-gray-300 px-4 py-2 text-sm">WIN011 laundry completed.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>

