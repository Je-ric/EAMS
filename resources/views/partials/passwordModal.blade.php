<dialog id="passwordDialog" class="modal">
    <form id="attendanceForm" method="POST" action="">
        @csrf
        <div class="modal-box max-w-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-lock text-blue-600"></i> Confirm Attendance
            </h3>

            <div class="flex flex-col items-center mb-4">
                <img id="empPicPreview" src="https://via.placeholder.com/100" alt="Employee"
                    class="w-24 h-24 rounded-full object-cover border border-gray-300 mb-2">
                <span id="empNameDisplay" class="font-semibold text-gray-700">Employee Name</span>
            </div>

            <input type="hidden" name="email" id="empEmailInput">

            <input type="password" name="password" placeholder="Enter your password"
                class="input input-bordered w-full mb-4" required>

            <div class="modal-action">
                <button type="button" class="btn btn-outline" onclick="passwordDialog.close()">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </form>

    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
