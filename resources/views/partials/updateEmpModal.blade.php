<!-- Update Employee Modal -->
<dialog id="updateModal" class="modal">
    <div class="modal-box max-w-4xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                <i class="fas fa-user-edit"></i> Update Employee Record
            </h2>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-gray-200">✕</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left: Form -->
            <form method="POST" action="{{ route('employees.update') }}" id="updateForm" enctype="multipart/form-data" class="space-y-3">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" id="update_id">
                <input type="hidden" name="user_id" id="update_user_id">

                <!-- Picture Preview -->
                <div class="flex flex-col items-center mb-2">
                    <img id="update_emp_pic_preview" src="{{ asset('pics/default.png') }}"
                        alt="Profile Picture"
                        class="w-24 h-24 object-cover rounded-full border border-gray-300 shadow-sm" />
                </div>

                <!-- Change Picture -->
                <div>
                    <label for="update_emp_pic" class="block text-gray-700 font-medium mb-1">Change Picture</label>
                    <input type="file" id="update_emp_pic" name="emp_pic" accept="image/*" class="file-input file-input-bordered w-full" />
                </div>

                <!-- Full Name -->
                <div>
                    <input type="text" name="full_name" id="update_full_name" placeholder="Full Name" required
                        class="input input-bordered w-full" />
                </div>

                <!-- Email -->
                <div>
                    <input type="email" name="email" id="update_email" placeholder="Email" required
                        class="input input-bordered w-full" />
                </div>

                <!-- Password -->
                <div>
                    <input type="password" name="password" id="update_password" placeholder="New Password (leave blank to keep old)"
                        class="input input-bordered w-full" />
                </div>

                <!-- Position -->
                <div>
                    <input type="text" name="position" id="update_position" placeholder="Position" required
                        class="input input-bordered w-full" />
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <button type="submit" class="btn btn-primary w-full gap-2">
                        <i class="fas fa-save"></i> Update Record
                    </button>
                </div>
            </form>

            <!-- Right: Attendance Preview -->
            <div class="border rounded-xl p-4 bg-gray-50 h-fit">
                <div class="flex justify-between items-center mb-2">
                    <h6 class="text-gray-700 font-semibold flex items-center gap-2">
                        <i class="fas fa-calendar-check text-blue-600"></i> Recent Attendance
                    </h6>
                </div>

                <div id="attendancePreview" class="text-center text-gray-500">
                    <span class="loading loading-spinner loading-sm text-blue-600"></span>
                    <p class="mt-2 mb-0">Loading attendance...</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="modal-action mt-6">
            <form method="dialog">
                <button class="btn btn-neutral">Close</button>
            </form>
        </div>
    </div>

    <!-- Modal Backdrop -->
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
