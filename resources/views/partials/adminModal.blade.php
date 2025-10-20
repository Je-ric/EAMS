<!-- Admin Login Modal -->
<dialog id="adminDialog" class="modal">
  <form method="POST" action="{{ route('admin.login.submit') }}" class="modal-box max-w-sm">
    @csrf

    <h3 class="font-bold text-xl text-center text-blue-800 mb-4">
      <i class="fas fa-user-shield"></i> ADMIN LOGIN
    </h3>

    @if ($errors->any())
      <div class="alert alert-error mb-3">
        <ul class="list-disc list-inside text-sm text-white">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <input type="email" name="email" placeholder="Email"
           class="input input-bordered w-full mb-3" required>

    <div class="flex gap-2 mb-4">
      <input type="password" name="password" placeholder="Password"
             class="input input-bordered w-full" required>
      <button type="button" class="btn btn-primary" onclick="togglePassword(this)">
        <i class="fas fa-eye"></i>
      </button>
    </div>

    <div class="modal-action flex-col">
      <button type="button" class="btn btn-outline w-full" onclick="document.getElementById('adminDialog').close()">
        Cancel
      </button>
      <button type="submit" class="btn btn-primary w-full">Login</button>
    </div>
  </form>
</dialog>

<script>
function togglePassword(btn) {
  const input = btn.previousElementSibling;
  input.type = input.type === "password" ? "text" : "password";
  btn.innerHTML = input.type === "password"
    ? '<i class="fas fa-eye"></i>'
    : '<i class="fas fa-eye-slash"></i>';
}
</script>
