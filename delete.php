<?php
// Process delete operation after confirmation
if (isset($_GET["id"])) {
    // Include config file
    require_once "config.php";
    $id = $_GET["id"];

    // Prepare a delete statement
    $sql = "DELETE FROM user WHERE id = $id";
    if (mysqli_execute_query($link, $sql)) {
        // Records deleted successfully. Redirect to landing page
        header("location: index.php?msg_title=¡Usuario eliminado!&msg_text=El usuario se eliminó correctamente");
        exit();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
}

// Close connection
mysqli_close($link);
