<h2>Editar grupo</h2>
<?php if (!empty($error)): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>
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
