<?php
// Check existence of id parameter before processing further
if (isset($_GET["id"])) {
    if (!empty(trim($_GET["id"]))) {
        // Include config file
        require_once "config.php";

        $id =  $_GET["id"];

        // Prepare a select statement
        $sql = "SELECT * FROM user WHERE id = $id";

        if ($result = mysqli_execute_query($link, $sql)) {
            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $name = $user["name"];
                $email = $user["email"];
                $image = $user["image"];
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
            mysqli_close($link);
        }
    }
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar usuario</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="css/dark.css">
    <link rel="stylesheet" href="css/light.css">
    <link rel="stylesheet" href="css/read.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <main>
        <img src="<?php echo $image . "/$id.jpg" ?>">

        <article>
            <md-elevation></md-elevation>
            <h1 class="md-typescale-headline-large">Información del usuario</h1>
            <section>
                <md-icon>person</md-icon>
                <h2 class="md-typescale-body-large"><?php echo $name ?></h2>
            </section>
            <section>
                <md-icon>email</md-icon>
                <h2 class="md-typescale-body-large"><?php echo $email ?></h2>
            </section>
            <footer>
                <a href="index.php">
                    <md-filled-button type="button">
                        <md-elevation></md-elevation>
                        Regresar
                    </md-filled-button>
                </a>
            </footer>
        </article>

    </main>

    <script type="importmap">
        {
        "imports": {
          "@material/web/": "https://esm.run/@material/web/"
        }
      }
    </script>
    <script type="module">
        import '@material/web/all.js';
        import {
            styles as typescaleStyles
        } from '@material/web/typography/md-typescale-styles.js';

        document.adoptedStyleSheets.push(typescaleStyles.styleSheet);
    </script>

    <script>
        // function to set a given theme/color-scheme
        function setTheme(themeName) {
            localStorage.setItem('theme', themeName);
            document.documentElement.className = themeName;
        }
        // function to toggle between light and dark theme
        function toggleTheme() {
            if (localStorage.getItem('theme') === 'dark') {
                setTheme('light');
            } else {
                setTheme('dark');
            }
        }
        // Immediately invoked function to set the theme on initial load
        (function() {
            if (localStorage.getItem('theme') === 'dark') {
                setTheme('dark');
            } else {
                setTheme('light');
            }
        })();
    </script>

</body>

</html>