<dialog id="adminDialog" class="modal">

    {{-- AuthController::login() --}}
    <form method="POST" action="{{ route('admin.login.submit') }}"
        class="modal-box max-w-sm p-6 rounded-xl shadow-lg bg-white">
        @csrf

        <h3 class="font-bold text-xl text-center text-blue-800 mb-4 flex items-center justify-center gap-2">
            <i class="bx bx-shield-quarter text-2xl"></i> ADMIN LOGIN
        </h3>

        @if ($errors->any())
            <div class="bg-red-500 text-white text-sm rounded p-2 mb-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="email"
                name="email"
                placeholder="Email"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

        <div class="flex gap-2 mb-4">
            <input type="password"
                    name="password"
                    placeholder="Password"
                    required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <button type="button" class="px-3 py-2 bg-gray-200 rounded-md hover:bg-gray-300 transition"
                onclick="togglePassword(this)">
                <i class="bx bx-show"></i>
            </button>
        </div>

        <div class="flex flex-col gap-2">
            <button type="button"
                    class="w-full border border-gray-300 rounded-md px-4 py-2 hover:bg-gray-100 transition"
                    onclick="document.getElementById('adminDialog').close()">
                    Cancel
            </button>
            <button type="submit"
                    class="w-full bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700 transition flex justify-center items-center gap-2">
                <i class="bx bx-log-in"></i> Login
            </button>
        </div>
    </form>
</dialog>

<script>
    function togglePassword(btn) {
        const input = btn.previousElementSibling;
        input.type = input.type === "password" ? "text" : "password";
        btn.innerHTML = input.type === "password" ?
            '<i class="bx bx-show"></i>' :
            '<i class="bx bx-hide"></i>';
    }
</script>
