<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #0097a7); /* degradado fondo */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            gap: 20px; /* separación entre las dos cajas */
        }

        /* Caja superior del título */
        .login-header {
            background: #0097a7;
            padding: 15px 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
            color: white;
            font-size: 22px;
            font-weight: bold;
            text-transform: lowercase;
        }

        /* Caja principal del login */
        .login-container {
            background: #0097a7; /* azul */
            padding: 40px 30px;
            border-radius: 20px;
            width: 300px;
            text-align: center;
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.25);
        }

        .login-container label {
            display: block;
            text-align: left;
            font-size: 12px;
            color: white;
            margin: 10px 0 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
            box-shadow: inset 0px 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .login-container input:focus {
            border: 2px solid #00e5ff;
            box-shadow: 0px 0px 8px #00e5ff;
        }

        .login-container button {
            background: white;
            color: #0097a7;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
        }

        .login-container button:hover {
            background: #007c91;
            color:white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Caja separada del título -->
    <div class="login-header">iniciar sesion</div>

    <!-- Caja principal -->
    <div class="login-container">
            <form method="POST" action="/public/index.php?controller=auth&action=login">
            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" required>

            <label for="contraseña">Contraseña</label>
            <input type="password" id="contraseña" name="contraseña" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
