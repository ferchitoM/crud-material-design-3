<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$id = "";
$name = "";
$name_err = "";
$email = "";
$email_err = "";
$pass = "";
$pass_err = "";
$pass_old = "";
$pass_old_err = "";
$pass_database = "";


// Processing form data when form is submitted
if (isset($_POST["id"])) {
    if (!empty($_POST["id"])) {
        // Get hidden input value
        $id = $_POST["id"];
        $pass_database = $_POST["pass_database"];

        // Validate name
        $input_name = trim($_POST["name"]);
        if (empty($input_name)) {
            $name_err = "Por favor ingrese un nombre.";
        } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
            $name_err = "Por favor ingrese un nombre válido.";
        } else {
            $name = $input_name;
        }

        // Validate email
        $input_email = trim($_POST["email"]);
        if (empty($input_email)) {
            $email_err = "Por favor ingrese un correo electrónico.";
        } else {
            $email = $input_email;
        }

        // Validate pass
        $input_pass = trim($_POST["pass"]);
        if (empty($input_pass)) {
            $pass_err = "Por favor ingrese una contraseña.";
        } else {
            $pass = $input_pass;
        }

        // Validate pass old
        $input_pass_old = trim($_POST["pass_old"]);
        $input_pass_old = hash('sha512', $input_pass_old);
        if (empty($input_pass_old) || $input_pass_old != $pass_database) {
            $pass_old_err = "La contraseña anterior es incorrecta.";
        } else {
            $pass_old = $input_pass_old;
        }

        // Check input errors before inserting in database
        if (empty($name_err) && empty($email_err) && empty($pass_err) && empty($pass_old_err)) {

            // Encrypt the password
            $pass = hash('sha512', $pass);
            // Save an random image
            $image = "https://xsgames.co/randomusers/assets/avatars/male/";

            // Prepare an update statement
            $sql = "UPDATE user SET name='$name', email='$email', pass='$pass' WHERE id = $id";

            if (mysqli_execute_query($link, $sql)) {
                // Records updated successfully. Redirect to landing page
                header("location: index.php?msg_title=¡Usuario actualizado!&msg_text=Los datos se almacenaron correctamente");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }

    // Close connection
    mysqli_close($link);
} else {

    // Check existence of id parameter before processing further
    if (isset($_GET["id"])) {
        if (!empty(trim($_GET["id"]))) {
            // Get URL parameter
            $id =  trim($_GET["id"]);

            // Prepare a select statement
            $sql = "SELECT * FROM user WHERE id = $id";
            if ($result = mysqli_query($link, $sql)) {

                if (mysqli_num_rows($result) == 1) {
                    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    $id = $user["id"];
                    $name = $user["name"];
                    $email = $user["email"];
                    $pass_database = $user["pass"];
                } else {
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close connection
            mysqli_close($link);
        } else {
            // URL doesn't contain id parameter. Redirect to error page
            header("location: error.php");
            exit();
        }
    }
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
    <link rel="stylesheet" href="css/create.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <main>
        <md-elevation></md-elevation>
        <h1 class="md-typescale-display-small">Actualizar Empleado</h1>
        </div>
        <p class="md-typescale-body-medium">Para actualizar los datos del usuario debes confirmar la contraseña anterior</p>

        <form action="update.php" method="post">

            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="pass_database" value="<?php echo $pass_database; ?>">

            <section>
                <md-icon>person</md-icon>
                <md-outlined-text-field label="Nombre"
                    type="text"
                    name="name"
                    value="<?php echo $name ?>"
                    <?php if ($name_err) echo 'error error-text="' . $name_err . '">' ?>>
                    <?php if ($name_err) echo '<md-icon slot="trailing-icon">error</md-icon>' ?>
                </md-outlined-text-field>
            </section>

            <section>
                <md-icon>email</md-icon>
                <md-outlined-text-field label="E-mail"
                    type="email"
                    name="email"
                    value="<?php echo $email ?>"
                    <?php if ($email_err) echo 'error error-text="' . $email_err . '">' ?>>
                    <?php if ($email_err) echo '<md-icon slot="trailing-icon">error</md-icon>' ?>
                </md-outlined-text-field>
            </section>

            <section>
                <md-icon>vpn_key_alert</md-icon>
                <md-outlined-text-field label="Contraseña anterior"
                    type="password"
                    name="pass_old"
                    value=""
                    <?php if ($pass_old_err) echo 'error error-text="' . $pass_old_err . '">' ?>>
                    <?php if ($pass_old_err) echo '<md-icon slot="trailing-icon">error</md-icon>';
                    else { ?>
                        <md-icon-button toggle slot="trailing-icon" onclick="show_pass('pass_old');">
                            <md-icon>visibility_off</md-icon>
                            <md-icon slot="selected">visibility</md-icon>
                        </md-icon-button>
                    <?php } ?>
                </md-outlined-text-field>
            </section>

            <section>
                <md-icon>key</md-icon>
                <md-outlined-text-field label="Nueva contraseña"
                    type="password"
                    name="pass"
                    value=""
                    <?php if ($pass_err) echo 'error error-text="' . $pass_err . '">' ?>>
                    <?php if ($pass_err) echo '<md-icon slot="trailing-icon">error</md-icon>';
                    else { ?>
                        <md-icon-button toggle slot="trailing-icon" onclick="show_pass('pass');">
                            <md-icon>visibility_off</md-icon>
                            <md-icon slot="selected">visibility</md-icon>
                        </md-icon-button>
                    <?php } ?>
                </md-outlined-text-field>
            </section>

            <script>
                function show_pass(input_name) {
                    event.preventDefault();
                    let input = document.getElementsByName(input_name)[0];
                    if (input.type === "password") input.type = "text";
                    else input.type = "password";
                }
            </script>

            <footer>
                <a href="index.php" class="btn btn-default">
                    <md-elevated-button type="button">Cancelar</md-elevated-button>
                </a>
                <md-filled-button type="submit">
                    <md-elevation></md-elevation>
                    Aceptar
                </md-filled-button>
            </footer>

        </form>
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