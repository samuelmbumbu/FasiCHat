<?php
require_once __DIR__ . '/app/classes/BaseDeDonnees.php';
require_once __DIR__ . '/app/classes/Session.php';
require_once __DIR__ . '/app/classes/UtilisateurFactory.php';
require_once __DIR__ . '/app/helpers.php';

$u = current_user();
$objet = UtilisateurFactory::creer($u);
$db = db();

/* Récupérer la promotion de l'utilisateur */
$stmtPromo = $db->prepare("SELECT promotion_id FROM users WHERE id = ?");
$stmtPromo->execute([$u['id']]);
$promotionId = (int)($stmtPromo->fetchColumn() ?: 0);

/* Cours accessibles */
$stmtCours = $db->prepare("
    SELECT c.*
    FROM cours c
    LEFT JOIN inscriptions i ON i.cours_id = c.id AND i.user_id = ?
    LEFT JOIN enseignement e ON e.cours_id = c.id AND e.user_id = ?
    WHERE i.user_id IS NOT NULL
       OR e.user_id IS NOT NULL
       OR ? IN ('doyen','vice_doyen','apparitaire')
    GROUP BY c.id
    ORDER BY c.nom
");
$stmtCours->execute([$u['id'], $u['id'], $u['role']]);
$cours = $stmtCours->fetchAll();

/* Contacts privés selon les règles du PDF */
if ($u['role'] === 'etudiant') {
    $stmtContacts = $db->prepare("
        SELECT id, nom, identifiant, role
        FROM users
        WHERE id <> ?
        AND role = 'etudiant'
        AND promotion_id = ?
        ORDER BY nom
    ");
    $stmtContacts->execute([$u['id'], $promotionId]);

} elseif (in_array($u['role'], ['enseignant', 'assistant'], true)) {
    $stmtContacts = $db->prepare("
        SELECT id, nom, identifiant, role
        FROM users
        WHERE id <> ?
        AND role IN ('enseignant','assistant')
        ORDER BY FIELD(role,'enseignant','assistant'), nom
    ");
    $stmtContacts->execute([$u['id']]);

} elseif (in_array($u['role'], ['doyen', 'vice_doyen'], true)) {
    $stmtContacts = $db->prepare("
        SELECT id, nom, identifiant, role
        FROM users
        WHERE id <> ?
        AND role IN ('doyen','vice_doyen')
        ORDER BY FIELD(role,'doyen','vice_doyen'), nom
    ");
    $stmtContacts->execute([$u['id']]);

} else {
    $stmtContacts = $db->prepare("
        SELECT id, nom, identifiant, role
        FROM users
        WHERE 1 = 0
    ");
    $stmtContacts->execute();
}

$contacts = $stmtContacts->fetchAll();

/* Convocations reçues */
$stmtConv = $db->prepare("
    SELECT co.*
    FROM convocations co
    JOIN convocation_destinataires cd ON cd.convocation_id = co.id
    WHERE cd.user_id = ?
    ORDER BY co.date_heure DESC
");
$stmtConv->execute([$u['id']]);
$conv = $stmtConv->fetchAll();
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>FasiChat</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<aside class="sidebar">
    <div class="brand small">
        <div class="logo">💬</div>
        <div>
            <h2>FasiChat</h2>
            <p><?= h(role_label($u['role'])) ?></p>
        </div>
    </div>

    <nav>
        <a class="active" href="dashboard.php">💬 Messages</a>
        <a href="cours.php">📚 Cours</a>
        <a href="valve.php">📣 Valve</a>

        <?php if (in_array($u['role'], ['doyen','vice_doyen'], true)): ?>
            <a href="convocation.php">🏛️ Convocations</a>
        <?php endif; ?>

        <?php if ($u['role'] === 'apparitaire'): ?>
            <a href="valve_admin.php">🛠️ Gestion Valve</a>
        <?php endif; ?>

        <a href="logout.php">🚪 Déconnexion</a>
    </nav>

    <div class="me">
        <b><?= h($u['nom']) ?></b>
        <span><?= h($u['identifiant']) ?></span>
    </div>
</aside>

<main class="layout">

    <section class="list-panel">
        <h3>📚 Cours publics</h3>

        <?php foreach ($cours as $c): ?>
            <a class="item" href="chat.php?type=public&cours_id=<?= (int)$c['id'] ?>">
                <b><?= h($c['nom']) ?></b>
                <span><?= h($c['code']) ?></span>
            </a>
        <?php endforeach; ?>

        <h3>👥 Messages privés</h3>

        <?php if (!$contacts): ?>
            <p class="empty">Aucun contact privé disponible.</p>
        <?php endif; ?>

        <?php foreach ($contacts as $ct): ?>
            <a class="item" href="chat.php?type=prive&user_id=<?= (int)$ct['id'] ?>">
                <b><?= h($ct['nom']) ?></b>
                <span><?= h(role_label($ct['role'])) ?></span>
            </a>
        <?php endforeach; ?>
    </section>

    <section class="content">
        <div class="hero">
            <h1><?= h($objet->tableauDeBord()) ?></h1>
            <p>Bienvenue <?= h($u['nom']) ?>. Choisis un cours ou une conversation à gauche.</p>
        </div>

        <?php if (in_array($u['role'], ['enseignant','assistant'], true)): ?>
            <div class="card">
                <h2>👨‍🏫 Tableau enseignant</h2>
                <p>
                    Consulte tes étudiants, publie sur le mur pédagogique
                    et lis tes convocations.
                </p>
                <a class="btn" href="cours.php">Voir mes cours</a>
            </div>
        <?php endif; ?>

        <?php if ($conv): ?>
            <div class="card admin-msg">
                <h2>🏛️ Convocations reçues</h2>

                <?php foreach ($conv as $co): ?>
                    <p>
                        <b><?= h($co['objet']) ?></b>
                        — <?= h($co['date_heure']) ?>
                        — <?= h($co['lieu']) ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

</main>

</body>
</html>
