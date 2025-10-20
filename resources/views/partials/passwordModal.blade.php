<!-- Password Dialog -->
<dialog id="passwordDialog" class="modal">
  <form method="dialog" class="modal-box max-w-md">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
      <i class="fas fa-lock text-blue-600"></i> Enter Password
    </h3>

    <!-- Employee info -->
    <div class="flex flex-col items-center mb-4">
      <img src="https://via.placeholder.com/100"
           alt="Employee"
           class="w-24 h-24 rounded-full object-cover border border-gray-300 mb-2">
      <span class="font-semibold text-gray-700">Employee Name</span>
    </div>

    <!-- Password input -->
    <input type="password"
           name="password"
           placeholder="Enter your password"
           class="input input-bordered w-full mb-4"
           required>

    <!-- Modal actions -->
    <div class="modal-action">
      <button class="btn btn-outline">Cancel</button>
      <button type="submit" class="btn btn-primary">Confirm</button>
    </div>
  </form>

  <!-- Backdrop -->
  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>
