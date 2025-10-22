<!-- Update Employee Modal -->
<dialog id="updateModal" class="modal">
    <div class="modal-box max-w-xl p-6 rounded-xl shadow-lg bg-white">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                <i class="bx bx-user-edit text-xl"></i> Update Employee Record
            </h2>
            <form method="dialog">
                <button
                    class="text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full p-1 transition">✕</button>
            </form>
        </div>

        <form method="POST" action="{{ route('employees.update') }}" id="updateForm" enctype="multipart/form-data"
            class="space-y-4">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="update_id">
            <input type="hidden" name="user_id" id="update_user_id">

            <div class="flex flex-col items-center mb-2">
                <img id="update_emp_pic_preview" src="{{ asset('pics/default.png') }}" alt="Profile Picture"
                    class="w-24 h-24 object-cover rounded-full border border-gray-300 shadow-sm mb-2">
            </div>

            <div>
                <label for="update_emp_pic" class="block text-gray-700 font-medium mb-1">Change Picture</label>
                <input type="file" id="update_emp_pic" name="emp_pic" accept="image/*"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <input type="text" name="name" id="update_name" placeholder="Full Name" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <input type="email" name="email" id="update_email" placeholder="Email" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <input type="password" name="password" id="update_password"
                    placeholder="New Password (leave blank to keep old)"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <input type="text" name="position" id="update_position" placeholder="Position" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <i class="bx bx-save"></i> Update Record
                </button>
            </div>
        </form>

        <div class="mt-4 pt-3 border-t">
            <form method="dialog">
                <button
                    class="w-full rounded-md border border-gray-300 px-4 py-2 hover:bg-gray-100 transition">Close</button>
            </form>
        </div>
    </div>

</dialog>
