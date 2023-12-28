<?php

session_start();
require_once "databaze.php";
if (count($_POST) > 0) {
    if (array_key_exists("reservation_type", $_POST) && array_key_exists("date", $_POST)) {
        try {
            $pdo_statement = $pdo->prepare("INSERT INTO reservations(date,reservation_types_id) VALUES (:date, :reservation_types_id);");
            $pdo_statement->bindParam(":date", $_POST["date"]);
            $pdo_statement->bindParam(":reservation_types_id", $_POST["reservation_type"]);
            $result = $pdo_statement->execute();
            if ($result) {
                header("Location: seznam_rezervaci.php");
            } else {
                echo "CHYBA";
            }
        } catch (PDOException $exception) {
            echo "CHYBA";
        }
    }
}
try {
    $pdo_statement = $pdo->prepare("SELECT * FROM reservation_types;");
    $pdo_statement->execute();
    $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
    $reservation_types = $pdo_statement->fetchAll();
} catch (PDOException $exception) {
    echo "CHYBA";
}
?>
<?php
require_once "header.php" ?>
<form method="post" action="sprava_rezervaci.php">
    <label for="reservation_type">Typ rezervacie:</label>
    <select name="reservation_type" id="reservation_type">
        <?php
        foreach ($reservation_types as $reservation_type): ?>
            <option value="<?= htmlspecialchars($reservation_type['id']) ?>">
                <?= htmlspecialchars($reservation_type['name']) ?>
            </option>
        <?php
        endforeach; ?>
    </select>
    <label for="datum">Datum</label>
    <input name="date" type="date" id="datum">
    <button type="submit">Vytvorit</button>
</form>
