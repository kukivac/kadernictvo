<?php

session_start();
if (count($_POST) > 0) {
    if (array_key_exists("username", $_POST) && array_key_exists("password", $_POST)) {
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);
        require_once "databaze.php";
        try {
            $pdo_statement = $pdo->prepare("SELECT users.id,password, username, level FROM users JOIN permissions ON users.permissions_id = permissions.id WHERE users.username = :username;");
            $pdo_statement->bindParam(":username", $username);
            $pdo_statement->execute();
            $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
            $user = $pdo_statement->fetch();
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION["user"] = ["user_id" => $user["id"], "username" => $user['username'], "level" => $user['level']];
                    header("Location: index.php");
                } else {
                    echo "heslo špatně";
                }
            } else {
                echo "uživatel neexistuje";
            }
        } catch (PDOException $exception) {
            echo "CHYBA";
        }
    }
}
?>

<?php
require_once "header.php" ?>
<form action="login.php" method="post">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Login">
</form>
