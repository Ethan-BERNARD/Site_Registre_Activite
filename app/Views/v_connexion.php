<?= $this->extend('l_connexion') ?>

<?= $this->section('body') ?>
<div id="contenu">
    <h2>Identification utilisateur</h2>

    <?php if (isset($erreur)): ?>
        <div class="erreur">
            <ul>
                <li><?= esc($erreur) ?></li>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('anonyme/seConnecter') ?>">
        <p>
            <label for="LOGIN">Login*</label>
            <input id="LOGIN" type="text" name="LOGIN"  size="30" maxlength="45">
        </p>
        <p>
            <label for="MDP">Mot de passe*</label>
            <input id="MDP"  type="password"  name="MDP" size="30" maxlength="45">
        </p>
        <p>
            <input type="submit" value="Valider" name="valider">
            <input type="reset" value="Annuler" name="annuler">
        </p>
    </form>

</div>
<?= $this->endSection() ?>
