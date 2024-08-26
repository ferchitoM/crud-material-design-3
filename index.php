<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de usuarios</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="css/dark.css">
    <link rel="stylesheet" href="css/light.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <md-filled-button variant="primary" onclick="toggleTheme()">
        Cambiar tema
        <md-icon slot="icon">dark_mode</md-icon>
    </md-filled-button>

    <main>
        <md-elevation></md-elevation>

        <h1 class="md-typescale-display-small">Lista de usuarios</h1>

        <section>
            <a class="button-add" href="create.php">
                <md-fab lowered variant="primary" size="small" aria-label="Edit">
                    <md-icon slot="icon"> person_add </md-icon>
                </md-fab>
            </a>
        </section>

        <md-list>
            <?php
            require_once "config.php";
            $sql = "SELECT * FROM user";
            if ($result = mysqli_query($link, $sql)) {
                $i = 1;
                $all_rows = mysqli_num_rows($result);
                if ($all_rows > 0) {
                    while ($item = mysqli_fetch_array($result)) { ?>

                        <md-list-item>
                            <img slot="start" style="width: 56px" src="<?php echo $item['image'] . $item['id'] . ".jpg" ?>">
                            <div slot="headline">
                                <?php echo $item['name'] ?>
                            </div>
                            <div slot="supporting-text"><?php echo $item['email'] ?></div>

                            <!-- Actions -->
                            <md-icon slot="end">
                                <a class="icon-view" href="read.php?id=<?php echo $item['id'] ?>">visibility</a>
                            </md-icon>
                            <md-icon slot="end">
                                <a class="icon-update" href="update.php?id=<?php echo $item['id'] ?>">edit</a>
                            </md-icon>
                            <md-icon slot="end">
                                <a class="icon-delete" href="index.php?id=<?php echo $item['id'] ?>&name=<?php echo $item['name'] ?>">delete</a>
                            </md-icon>
                            <!-- Actions -->

                        </md-list-item>
                        <?php if ($i < $all_rows) { ?>
                            <md-divider inset></md-divider>
            <?php
                        }
                        $i++;
                    }
                    mysqli_free_result($result);
                } else {
                    echo "<p class='lead'><em>No records were found.</em></p>";
                }
            } else {
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
            }

            // Close connection
            mysqli_close($link);
            ?>
        </md-list>

        <!-- Message dialog -->
        <?php if (isset($_GET['msg_title'])) { ?>
            <md-dialog open id="dialog">
                <div slot="headline"><?php echo $_GET['msg_title'] ?></div>
                <div class="content" slot="content"><?php echo $_GET['msg_text'] ?>
                    <form>
                        <md-filled-button variant="primary" class="button" onclick="close_dialog('dialog')">
                            <md-icon slot="icon">task_alt</md-icon>
                            Aceptar
                        </md-filled-button>
                    </form>
                </div>
            </md-dialog>
        <?php
        } ?>

        <!-- Confirm deletion dialog -->
        <?php if (isset($_GET['id'])) { ?>
            <md-dialog open id="confirm_dialog">
                <div slot="headline">Confirmar eliminación</div>
                <div class="content" slot="content">¿Realmente desea eliminar de forma permanente a "<?php echo $_GET['name'] ?>"?</div>

                <div class="actions" slot="actions">
                    <md-text-button onclick="close_dialog('confirm_dialog')">
                        Cancelar
                    </md-text-button>
                    <a class="icon-delete" href="delete.php?id=<?php echo $_GET['id'] ?>">
                        <md-filled-button variant="primary">
                            Eliminar
                        </md-filled-button></a>

                </div>


            </md-dialog>
        <?php
        } ?>

        <script>
            function close_dialog(dialog_name) {
                event.preventDefault();
                let dialog = document.getElementById(dialog_name);
                dialog.close();
            }
        </script>

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