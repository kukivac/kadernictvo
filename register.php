<?php

session_start();
if (count($_POST) > 0) {
    if (array_key_exists("username", $_POST) && array_key_exists("firstname", $_POST) && array_key_exists("lastname", $_POST) && array_key_exists("password", $_POST)) {
        $username = htmlspecialchars($_POST["username"]);
        $firstname = htmlspecialchars($_POST["firstname"]);
        $lastname = htmlspecialchars($_POST["lastname"]);
        $password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT);
        require_once "databaze.php";
        try {
            $pdo_statement = $pdo->prepare("INSERT INTO users(username,password,firstname,lastname,permissions_id) VALUES (:username, :password, :firstname, :lastname, '2');");
            $pdo_statement->bindParam(":username", $username);
            $pdo_statement->bindParam(":firstname", $firstname);
            $pdo_statement->bindParam(":lastname", $lastname);
            $pdo_statement->bindParam(":password", $password);
            $registered_user = $pdo_statement->execute();
            if ($registered_user) {
                $pdo_statement = $pdo->prepare("SELECT users.id,username, level FROM users JOIN permissions ON users.permissions_id = permissions.id WHERE users.username = :username;");
                $pdo_statement->bindParam(":username", $username);
                $pdo_statement->execute();
                $pdo_statement->setFetchMode(PDO::FETCH_ASSOC);
                $user = $pdo_statement->fetch();
                $_SESSION["user"] = ["user_id" => $user["id"], "username" => $user['username'], "level" => $user['level']];
                header("Location: index.php");
            } else {
                echo "CHYBA";
            }
        } catch (PDOException $exception) {
            echo "CHYBA";
        }
    }
}
?>
<?php
require_once "header.php" ?>
<form action="register.php" method="post">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br>

    <label for="firstname">First Name:</label><br>
    <input type="text" id="firstname" name="firstname" required><br>

    <label for="lastname">Last Name:</label><br>
    <input type="text" id="lastname" name="lastname" required><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
</form>
