<?php

session_start();
require_once "databaze.php";
try {
    $pdo_statement = $pdo->prepare("SELECT r.*, rt.* FROM reservations r JOIN reservation_types rt ON r.reservation_types_id = rt.id LEFT JOIN users_has_reservations uhr ON r.id = uhr.reservations_id WHERE uhr.reservations_id IS NULL;");
    $pdo_statement->execute();
    $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
    $reservations = $pdo_statement->fetchAll();

    if (array_key_exists("user", $_SESSION)) {
        $pdo_statement = $pdo->prepare("SELECT * FROM reservations JOIN reservation_types ON reservations.reservation_types_id = reservation_types.id JOIN users_has_reservations ON users_has_reservations.reservations_id = reservations.id WHERE users_has_reservations.users_id = :users_id;");
        $pdo_statement->bindParam(":users_id", $_SESSION["user"]["user_id"]);;
        $pdo_statement->execute();
        $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
        $my_reservations = $pdo_statement->fetchAll();
    } else {
        $my_reservations = [];
    }
} catch (PDOException $exception) {
    echo "CHYBA";
}
?>
<?php
require_once "header.php" ?>
<div>
    <?php
    if (count($my_reservations) > 0): ?>
        <h2>Moje rezervace</h2>
        <?php
        foreach ($my_reservations as $my_reservation): ?>
            <div>
                <h3><?= $my_reservation["name"] ?></h3>
                <p><?= $my_reservation["date"] ?></p>
            </div>
        <?php
        endforeach; ?>
    <?php
    endif; ?>
    <h2>Volné rezervace</h2>
    <?php
    foreach ($reservations as $reservation): ?>
        <div>
            <h3><?= $reservation["name"] ?></h3>
            <p><?= $reservation["date"] ?></p>
            <a href="objednat.php?id=<?= $reservation["id"] ?>">objednat</a>
        </div>
    <?php
    endforeach; ?>
</div>