<dialog id="registerEmployeeModal" class="modal">
    <form {{-- method="POST"
  action="{{ route('employee.register') }}" --}} class="modal-box max-w-md bg-white p-6 rounded-xl shadow-lg">
        @csrf

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                <i class='bx bx-user-plus text-xl'></i> Register as Employee
            </h3>
            <button type="button" class="text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full p-1 transition"
                onclick="registerEmployeeModal.close()">✕</button>
        </div>

        <button type="submit"
            class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md px-4 py-2 transition">
            Register
        </button>

        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-2 text-gray-500 text-sm">or</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <div class="flex flex-col gap-2">
            <a href="{{ route('auth.google') }}"
                class="flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white rounded-md py-2 font-medium transition">
                <i class='bx bxl-google text-lg'></i> Continue with Google
            </a>
            <a {{-- href="{{ route('auth.facebook.redirect') }}" --}}
                class="flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white rounded-md py-2 font-medium transition">
                <i class='bx bxl-facebook-circle text-lg'></i> Continue with Facebook
            </a>
        </div>
    </form>
</dialog>
