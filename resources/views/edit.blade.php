<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div>
        <label>Mot de passe actuel</label>
        <input type="password" name="current_password" required>
        @error('current_password') <span>{{ $message }}</span> @enderror
    </div>

    <div>
        <label>Nouveau mot de passe</label>
        <input type="password" name="password" required>
        @error('password') <span>{{ $message }}</span> @enderror
    </div>

    <div>
        <label>Confirmer le nouveau mot de passe</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <button type="submit">Mettre à jour le mot de passe</button>
</form>