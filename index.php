<?php
define('PETICION_INVALIDA', 'La petición no es válida.');
if (!empty($_POST)) {
    $numberString = trim(filter_input(INPUT_POST, 'number_string', FILTER_UNSAFE_RAW));
    $numberStringValid = filter_var($numberString, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => "/^(([1-9],)|([1-9]-[1-9],))*(([1-9])|([1-9]-[1-9]))$/"]]);
    if ($numberStringValid !== false) {
        $numsOrRanges = explode(",", $numberString);
        $nums = [];
        foreach ($numsOrRanges as $numOrRange) {
            if (strpos($numOrRange, "-") !== false) {
                $limsRange = explode("-", $numOrRange);
                $range = range($limsRange[0], $limsRange[1]);
                $nums = array_merge($nums, $range);
            } else {
                $nums[] = (int) ($numOrRange);
            }
        }

        $nums = array_unique($nums);
        sort($nums);
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
                            <input id = "number_string" type="text" name="number_string" placeholder="ie, 1,3,5,7-9" value="<?= ($numberString ?? '') ?>"/>
                            <span class="error <?= (isset($numberStringValid) && !$numberStringValid) ? 'error-visible' : '' ?>">
                                <?= constant("PETICION_INVALIDA") ?>
                            </span> 
                            <!--    <?php if (isset($numberStringValid) && !$numberStringValid): ?>
                                        <p class="error">The </p>
                            <?php endif ?> -->
                        </div>
                        <div class="submit-section">
                            <input class="submit" type="submit" 
                                   value="Send" name="button" /> 
                        </div>
                    </div>
                </form>   
                <?php if (isset($nums)): ?>
                    <h1>Las tablas de multiplicar solicitadas son:</h1>
                    <div class="info">
                        <?php foreach ($nums as $num): ?>

                            <table class="mult">
                                <th>Table of <?= $num ?></th>
                                <?php for ($n = 1; $n <= 10; $n++): ?>
                                    <tr>
                                        <td><?= "$num x $n = " . ($num * $n) ?></td>
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
