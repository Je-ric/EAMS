<!-- Admin Login Dialog -->
<dialog id="adminDialog" class="modal">
  <form method="dialog" class="modal-box max-w-sm">
    <h3 class="font-bold text-xl text-center text-blue-800 mb-4">
      <i class="fas fa-user-shield"></i> ADMIN
    </h3>
    <input type="email" placeholder="Email" class="input input-bordered w-full mb-3">
    <div class="flex gap-2 mb-4">
      <input type="password" placeholder="Password" class="input input-bordered w-full">
      <button class="btn btn-primary"><i class="fas fa-eye"></i></button>
    </div>
    <div class="modal-action">
      <button class="btn btn-outline">Cancel</button>
      <button class="btn btn-primary w-full">Login</button>
    </div>
  </form>
</dialog>
