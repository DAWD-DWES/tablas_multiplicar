<?php
define('PETICION_INVALIDA', 'La petición no es válida.');
if (filter_has_var(INPUT_POST, 'boton_envio')) {
    $cadenaNumeros = trim(filter_input(INPUT_POST, 'cadena_rango', FILTER_UNSAFE_RAW));
    $cadenaNumerosValid = filter_var($cadenaNumeros, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => "/^(([1-9],)|([1-9]-[1-9],))*(([1-9])|([1-9]-[1-9]))$/"]]);
    if ($cadenaNumerosValid !== false) {
        $numerosORangos = explode(",", $cadenaNumeros);
        $numeros = [];
        foreach ($numerosORangos as $numeroORango) {
            if (strpos($numeroORango, "-") !== false) {
                $limiteRango = explode("-", $numeroORango);
                $rango = range($limiteRango[0], $limiteRango[1]);
                $numeros = array_merge($numeros, $rango);
            } else {
                $numeros[] = (int) ($numeroORango);
            }
        }
        $numeros = array_unique($numeros);
        sort($numeros);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Tablas de multiplicar</title>
        <meta name="viewport" content="width=device-width">
        <meta charset = "UTF-8">
        <link rel = "stylesheet" href = "stylesheet.css">
    </head>
    <body>
        <div class="page">
            <h1>Tablas de multiplicar</h1>
            <div class = "capaform">
                <form class = "form-font" name = "form_request_numbers"
                      action = "index.php" method = "POST" novalidate>
                    <div class = "form-section">
                        <div class="input-section">
                            <label for = "numbers">Introduce los números:</Label>
                            <input id = "cadena_rango" type="text" name="cadena_rango" placeholder="ie, 1,3,5,7-9" value="<?= ($cadenaNumeros ?? '') ?>"/>
                            <span class="error <?= (isset($cadenaNumerosValid) && !$cadenaNumerosValid) ? 'error-visible' : '' ?>">
                                <?= constant("PETICION_INVALIDA") ?>
                            </span> 
                        </div>
                        <div class="submit-section">
                            <input class="submit" type="submit" 
                                   value="Envía" name="boton_envio" /> 
                        </div>
                    </div>
                </form>   
                <?php if (isset($numeros)): ?>
                    <h1>Las tablas de multiplicar solicitadas son:</h1>
                    <div class="info">
                        <?php foreach ($numeros as $numero): ?>

                            <table class="mult">
                                <th>Tabla del <?= $numero ?></th>
                                <?php for ($n = 1; $n <= 10; $n++): ?>
                                    <tr>
                                        <td><?= "$numero x $n = " . ($numero * $n) ?></td>
                                    </tr>
                                <?php endfor ?>
                            </table>

                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </body>
</html>
