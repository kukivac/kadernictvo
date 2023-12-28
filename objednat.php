<?php

session_start();
if (array_key_exists("id", $_GET)) {
    require_once "databaze.php";
    try {
        $pdo_statement = $pdo->prepare("SELECT * FROM reservations WHERE id = :id;");
        $pdo_statement->bindParam(":id", $_GET["id"]);
        $pdo_statement->execute();
        $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
        $reservation = $pdo_statement->fetch();
        if ($reservation) {
            $pdo_statement = $pdo->prepare("INSERT INTO users_has_reservations(users_id,reservations_id) VALUES (:users_id, :reservations_id);");
            $pdo_statement->bindParam(":users_id", $_SESSION["user"]["user_id"]);
            $pdo_statement->bindParam(":reservations_id", $reservation["id"]);
            $result = $pdo_statement->execute();
            if ($result) {
                header("Location: seznam_rezervaci.php");
            } else {
                echo "CHYBA";
            }
        }
    } catch (PDOException $exception) {
        echo "CHYBA";
    }
} else {
    header("Location: seznam_rezervaci.php");
}