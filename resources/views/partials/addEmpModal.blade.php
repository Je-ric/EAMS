<!-- Add Employee Modal -->
<dialog id="addModal" class="modal">
    <div class="modal-box max-w-md">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-green-700">
                <i class="fas fa-user-plus mr-2"></i> Add New Employee
            </h2>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-gray-200">✕</button>
            </form>
        </div>

        <!-- Form -->
        <form id="addEmployeeForm" method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf

            <!-- Upload Picture -->
            <div>
                <label for="emp_pic" class="block text-gray-700 font-medium mb-1">Upload Picture</label>
                <input type="file" id="emp_pic" name="emp_pic" accept="image/*" class="file-input file-input-bordered w-full" />
            </div>

            <!-- Full Name (changed name attribute to match controller) -->
            <div>
                <input type="text" name="name" placeholder="Full Name" required
                    class="input input-bordered w-full" />
            </div>

            <!-- Email -->
            <div>
                <input type="email" name="email" placeholder="Email" required
                    class="input input-bordered w-full" />
            </div>

            <!-- Password (make required to satisfy controller validation) -->
            <div>
                <input type="password" name="password" placeholder="Password" required
                    class="input input-bordered w-full" />
            </div>

            <!-- Position -->
            <div>
                <input type="text" name="position" placeholder="Position" required
                    class="input input-bordered w-full" />
            </div>

            <!-- Submit -->
            <div class="pt-3">
                <button type="submit" class="btn btn-success w-full gap-2">
                    <i class="fas fa-save"></i> Add Employee
                </button>
            </div>
        </form>

        <!-- Optional footer close -->
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-neutral">Close</button>
            </form>
        </div>
    </div>

    <!-- Modal backdrop -->
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
