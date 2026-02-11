<?= $this->extend('layouts/l_user') ?>

<?= $this->section('title') ?>Consultation du traitement<?= $this->endSection() ?>

<?= $this->section('body') ?>

<div id="contenu">

    <a href="<?= site_url('user/tableau') ?>" class="btn-retour">
        Retour au tableau
    </a>

    <div class="header-titre">
        <h2>Consultation du traitement</h2>
        <div class="badge-readonly">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"> <!--c'est un dessin, comme dans un canvas-->
                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
            </svg>
            Lecture seule
        </div>
    </div>

    <div class="formInfos readonly-form">

        <div class="bloc-section">
            <h2 class="barre">Description du traitement</h2>
            <div class="static-inputs">
                <div class="ligne">
                    <label>Nom du traitement</label>
                    <input type="text" value="<?= esc($traitement['NOM']) ?>" disabled>
                </div>
                <div class="ligne">
                    <label>Date de création</label>
                    <input type="date" value="<?= esc($traitement['DATECREATION']) ?>" disabled>
                </div>
                <div class="ligne">
                    <label>Date de mise à jour</label>
                    <input type="date" value="<?= esc($traitement['DATEMAJ']) ?>" disabled>
                </div>
                <div class="ligne checkbox-ligne">
                    <label>Transfert hors UE</label>
                    <input type="checkbox" <?= ($traitement['TRANSFERT_HORS_UE'] === 'Oui') ? 'checked' : '' ?> disabled>
                </div>
            </div>
        </div>

        <div class="sections-grid-wrapper">

            <?php if (!empty($acteurs)): ?>
            <div class="bloc-section">
                <h2 class="barre">Acteurs</h2>
                <div class="grid-2-col">
                    <?php foreach ($acteurs as $acteur): ?>
                        <div class="card-item">
                            <div class="ligne"><label>Nom</label><input type="text" value="<?= esc($acteur['NOM']) ?>" disabled></div>
                            <div class="ligne"><label>Adresse</label><input type="text" value="<?= esc($acteur['ADRESSE']) ?>" disabled></div>
                            <div class="ligne"><label>Code Postal</label><input type="text" value="<?= esc($acteur['CP']) ?>" disabled></div>
                            <div class="ligne"><label>Ville</label><input type="text" value="<?= esc($acteur['VILLE']) ?>" disabled></div>
                            <div class="ligne"><label>Pays</label><input type="text" value="<?= esc($acteur['PAYS']) ?>" disabled></div>
                            <div class="ligne"><label>Téléphone</label><input type="text" value="<?= esc($acteur['TEL']) ?>" disabled></div>
                            <div class="ligne"><label>Mail</label><input type="text" value="<?= esc($acteur['MAIL']) ?>" disabled></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($finalites)): ?>
            <div class="bloc-section">
                <h2 class="barre">Finalités</h2>
                <div class="grid-2-col">
                    <?php foreach ($finalites as $f): ?>
                        <div class="card-item">
                            <div class="ligne"><label>Finalité</label><input type="text" value="<?= esc($f['LIBELLE']) ?>" disabled></div>
                            <div class="ligne checkbox-ligne">
                                <label>Est principal</label>
                                <input type="checkbox" <?= $f['ESTPRINCIPAL'] ? 'checked' : '' ?> disabled>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($categories)): ?>
            <div class="bloc-section">
                <h2 class="barre">Catégories de données personnelles</h2>
                <div class="grid-2-col">
                    <?php foreach ($categories as $cat): ?>
                        <div class="card-item">
                            <div class="ligne"><label>Description</label><input type="text" value="<?= esc($cat['DESCRIPTION']) ?>" disabled></div>
                            <div class="ligne"><label>Durée conservation (mois)</label><input type="text" value="<?= esc($cat['DUREECONSERVATION']) ?>" disabled></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($sensibles)): ?>
            <div class="bloc-section">
                <h2 class="barre">Données sensibles</h2>
                <div class="grid-2-col">
                    <?php foreach ($sensibles as $s): ?>
                        <div class="card-item">
                            <div class="ligne"><label>Description</label><input type="text" value="<?= esc($s['DESCRIPTION']) ?>" disabled></div>
                            <div class="ligne"><label>Durée conservation (mois)</label><input type="text" value="<?= esc($s['DUREECONSERVATION']) ?>" disabled></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($personnes)): ?>
            <div class="bloc-section">
                <h2 class="barre">Personnes concernées</h2>
                <div class="grid-2-col">
                    <?php foreach ($personnes as $p): ?>
                        <div class="card-item">
                            <div class="ligne"><label>Précision</label><input type="text" value="<?= esc($p['PRECIS']) ?>" disabled></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($destinataires)): ?>
            <div class="bloc-section">
                <h2 class="barre">Destinataires</h2>
                <div class="grid-2-col">
                    <?php foreach ($destinataires as $d): ?>
                        <div class="card-item">
                            <div class="ligne"><label>Précision</label><input type="text" value="<?= esc($d['PRECIS']) ?>" disabled></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($securites)): ?>
            <div class="bloc-section">
                <h2 class="barre">Mesures de sécurité</h2>
                <div class="grid-2-col">
                    <?php foreach ($securites as $sec): ?>
                        <div class="card-item">
                            <div class="ligne"><label>Précision</label><input type="text" value="<?= esc($sec['PRECIS']) ?>" disabled></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($transferts)): ?>
            <div class="bloc-section">
                <h2 class="barre">Transferts hors UE</h2>
                <div class="grid-2-col">
                    <?php foreach ($transferts as $tr): ?>
                        <div class="card-item">
                            <div class="ligne"><label>Destinataire</label><input type="text" value="<?= esc($tr['DESTINATAIRE']) ?>" disabled></div>
                            <div class="ligne"><label>Lien doc.</label><input type="text" value="<?= esc($tr['LIENDOC']) ?>" disabled></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>

    </div>

</div>

<?= $this->endSection() ?>
