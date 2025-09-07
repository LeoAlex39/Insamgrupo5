<h2>Nuevo grupo</h2>
<?php if (!empty($error)): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="POST">
  <label>Grado:</label>
  <select name="idGrado" required>
    <?php foreach($grados as $g): ?><option value="<?= $g['idGrado'] ?>"><?= htmlspecialchars($g['nombreGrado']) ?></option><?php endforeach; ?>
  </select>
  <label>Modalidad:</label>
  <select name="idModalidad" required>
    <?php foreach($modalidades as $m): ?><option value="<?= $m['idModalidad'] ?>"><?= htmlspecialchars($m['nombreModalidad']) ?></option><?php endforeach; ?>
  </select>
  <label>Sección:</label>
  <select name="idSeccion" required>
    <?php foreach($secciones as $s): ?><option value="<?= $s['idSeccion'] ?>"><?= htmlspecialchars($s['nombreSeccion']) ?></option><?php endforeach; ?>
  </select>
  <br>
  <label>Alias (opcional):</label>
  <input type="text" name="alias" placeholder="p.ej. 1° DS B">
  <br><br>
  <button type="submit">Guardar</button>
  <a href="<?= BASE_URL ?>/index.php?controller=grupo&action=index">Cancelar</a>
</form>
