<h2>Editar grupo</h2>
<?php if (!empty($error)): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <style>
  h2 {
    font-size: 1.6rem;
    margin-bottom: 15px;
    color: #333;
  }

  form {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    max-width: 500px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  }

  label {
    display: block;
    margin-top: 12px;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
  }

  select, input[type="text"] {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.95rem;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  select:focus, input[type="text"]:focus {
    border-color: #5a8;
    box-shadow: 0 0 4px rgba(90, 168, 120, 0.3);
    outline: none;
  }

  button {
    background: #5a8;
    color: white;
    font-weight: 600;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    margin-top: 16px;
    cursor: pointer;
    transition: background 0.2s;
  }

  button:hover {
    background: #48976b;
  }

  a {
    margin-left: 12px;
    font-weight: 600;
    color: #555;
    text-decoration: none;
    padding: 9px 14px;
    border-radius: 6px;
    border: 1px solid #ccc;
    transition: background 0.2s, color 0.2s;
  }

  a:hover {
    background: #f5f5f5;
    color: #222;
  }

  /* Mensajes de error */
  .error {
    color: #b00;
    margin-bottom: 12px;
    font-weight: 600;
  }
</style>

<form method="POST">
  <label>Grado:</label>
  <select name="idGrado">
    <?php foreach($grados as $g): ?>
      <option value="<?= $g['idGrado'] ?>" <?= ($g['idGrado']==$row['idGrado']?'selected':'') ?>>
        <?= htmlspecialchars($g['nombreGrado']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <label>Modalidad:</label>
  <select name="idModalidad">
    <?php foreach($modalidades as $m): ?>
      <option value="<?= $m['idModalidad'] ?>" <?= ($m['idModalidad']==$row['idModalidad']?'selected':'') ?>>
        <?= htmlspecialchars($m['nombreModalidad']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <label>Sección:</label>
  <select name="idSeccion">
    <?php foreach($secciones as $s): ?>
      <option value="<?= $s['idSeccion'] ?>" <?= ($s['idSeccion']==$row['idSeccion']?'selected':'') ?>>
        <?= htmlspecialchars($s['nombreSeccion']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <br>
  <label>Alias (opcional):</label>
  <input type="text" name="alias" value="<?= htmlspecialchars($row['alias'] ?? '') ?>">
  <br><br>
  <button type="submit">Guardar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=index">Cancelar</a>
</form>
