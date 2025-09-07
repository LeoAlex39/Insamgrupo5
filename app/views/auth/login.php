<h2>Iniciar sesión</h2>
<form method="POST" action="index.php?controller=auth&action=login">
    <label>Correo:</label>
    <input type="email" name="correo" required>
    <br>
    <label>Contraseña:</label>
    <input type="password" name="contraseña" required>
    <br>
    <button type="submit">Ingresar</button>
</form>
