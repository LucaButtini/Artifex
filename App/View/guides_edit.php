<?php
// App/View/guides_edit.php
$appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl   = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
$title     = 'Modifica Guida';
require 'header.php';
?>

    <h2><?= $title ?></h2>

    <form action="<?= $baseUrl ?>/guides_update/<?= /**@var $guida*/htmlspecialchars($guida['id_guida']) ?>" method="post">
        <input type="hidden" name="id_guida" value="<?= htmlspecialchars($guida['id_guida']) ?>">

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome"
                   value="<?= htmlspecialchars($guida['nome'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="cognome" class="form-label">Cognome</label>
            <input type="text" class="form-control" id="cognome" name="cognome"
                   value="<?= htmlspecialchars($guida['cognome'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="data_nascita" class="form-label">Data di Nascita</label>
            <input type="date" class="form-control" id="data_nascita" name="data_nascita"
                   value="<?= htmlspecialchars($guida['data_nascita'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="luogo_nascita" class="form-label">Luogo di Nascita</label>
            <input type="text" class="form-control" id="luogo_nascita" name="luogo_nascita"
                   value="<?= htmlspecialchars($guida['luogo_nascita'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="titolo_studio" class="form-label">Titolo di Studio</label>
            <input type="text" class="form-control" id="titolo_studio" name="titolo_studio"
                   value="<?= htmlspecialchars($guida['titolo_studio'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="id_lingua" class="form-label">Lingua</label>
            <select name="id_lingua" id="id_lingua" class="form-control" required>
                <?php
                /**@var $lingue*/
                foreach($lingue as $l): ?>
                    <option value="<?= $l['id_lingua'] ?>"
                        <?= ($guida['id_lingua'] ?? null) == $l['id_lingua'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($l['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_conoscenza" class="form-label">Livello</label>
            <select name="id_conoscenza" id="id_conoscenza" class="form-control" required>
                <?php
                /**@var $conoscenze*/
                foreach($conoscenze as $c): ?>
                    <option value="<?= $c['id_conoscenza'] ?>"
                        <?= ($guida['id_conoscenza'] ?? null) == $c['id_conoscenza'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['livello']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
        <a href="<?= $baseUrl ?>/admin/dashboard" class="btn btn-secondary">Annulla</a>
    </form>

<?php require 'footer.php'; ?>