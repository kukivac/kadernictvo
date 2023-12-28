<?php

?>
<header>
    <a href="/index.php"><img src="logo.png" height="100" width="100"></a>
    <nav>
        <a href="/seznam_rezervaci.php">seznam rezervaci</a>
        <?php if (array_key_exists("user", $_SESSION) && $_SESSION["user"]["level"] == 1): ?>
            <a href="/sprava_rezervaci.php">sprava rezervaci</a>
        <?php endif; ?>

        <?php if (array_key_exists("user", $_SESSION)): ?>
            <a href="/logout.php">logout</a>
        <?php else: ?>
            <a href="/login.php">login</a>
            <a href="/register.php">register</a>
        <?php endif; ?>
    </nav>
</header>
