<!-- Add Employee Modal -->
<dialog id="addModal" class="modal">
    <div class="modal-box max-w-xl rounded-xl shadow-lg p-6 bg-white">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6 border-b pb-3">
            <h2 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                <i class="bx bx-user-plus text-green-600 text-xl"></i> Add New Employee
            </h2>
            <form method="dialog">
                <button class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full p-1 transition">
                    ✕
                </button>
            </form>
        </div>

        <!-- Form -->
        <form id="addEmployeeForm" method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Upload Picture -->
            <div>
                <label for="emp_pic" class="block text-gray-700 font-medium mb-2">Upload Picture</label>
                <input type="file" id="emp_pic" name="emp_pic" accept="image/*" 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
            </div>

            <!-- Full Name -->
            <div>
                <input type="text" name="name" placeholder="Full Name" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
            </div>

            <!-- Email -->
            <div>
                <input type="email" name="email" placeholder="Email" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
            </div>

            <!-- Password -->
            <div>
                <input type="password" name="password" placeholder="Password" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
            </div>

            <!-- Position -->
            <div>
                <input type="text" name="position" placeholder="Position" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow-md px-4 py-2 transition">
                    <i class="bx bx-save"></i> Add Employee
                </button>
            </div>
        </form>

        <!-- Optional Footer Close -->
        <div class="mt-4 pt-3 border-t">
            <form method="dialog">
                <button class="w-full rounded-md border border-gray-300 px-4 py-2 hover:bg-gray-100 transition">Close</button>
            </form>
        </div>
    </div>
</dialog>
