<?php
session_start();

if (isset($_SESSION["create"])) {
?>
    <div class="alert alert-success shadow-sm">
        <?php
        echo $_SESSION["create"];
        ?>
    </div>
<?php
    unset($_SESSION["create"]);
}
?>

<?php
if (isset($_SESSION["update"])) {
?>
    <div class="alert alert-success shadow-sm">
        <?php
        echo $_SESSION["update"];
        ?>
    </div>
<?php
    unset($_SESSION["update"]);
}
?>

<?php
if (isset($_SESSION["delete"])) {
?>
    <div class="alert alert-success shadow-sm">
        <?php
        echo $_SESSION["delete"];
        ?>
    </div>
<?php
    unset($_SESSION["delete"]);
}
?>