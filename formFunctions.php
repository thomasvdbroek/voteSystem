<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$validationErrors = array();

function hasValidationErrors() {
    global $validationErrors;
    return count($validationErrors) > 0;
}

function showValidationSummary() {
    global $validationErrors;

    if (hasValidationErrors()) {
        echo "<div class='alert-error'>$validationErrors[0]</div>";
    }
}

function addValidationError($message) {
    global $validationErrors;
    array_push($validationErrors, $message);
}

function isRequired($parameterName, $propertyName) {
    if (empty($_POST[$parameterName])) {
        addValidationError("Je moet $propertyName opgeven.");
    }
}

function validateEmail($postEmail) {
    if (!filter_var($_POST[$postEmail], FILTER_VALIDATE_EMAIL)) {
        addValidationError('Geen geldig e-mailadres ingevuld!');
    }
}

function onlyAlpha($parameter, $propertyName) {
    if (!preg_match("/^[a-zA-Z ]*$/", $_POST[$parameter])) {
        addValidationError("$propertyName is niet geldig");
    }
}

function onlyDigit($parameter, $propertyName) {
    if (preg_match("#[a-zA-Z]+#", $_POST[$parameter])) {
        addValidationError("$propertyName is niet geldig");
    }
}

function setMinCharacters($parameter, $propertyName, $minLength) {
    if (strlen($_POST[$parameter]) < $minLength) {
        addValidationError("$propertyName moet minimaal $minLength karakters bevatten");
    }
}

function setMaxCharacters($parameter, $propertyName, $maxLength) {
    if (strlen($_POST[$parameter]) > $maxLength) {
        addValidationError("$propertyName mag maximaal $maxLength karakters bevatten");
    }
}

function doesEmailExist($tableName, $postName) {
    global $link;
    DB::query("SELECT * FROM account WHERE email = %s", $_POST[$postName]);

    if (DB::count() == 0) {
	validateEmail($postName);
    } else {
        addValidationError('E-mailadres is al geregistreerd');
    }
}

function checkPassword($postPassword, $postPasswordRepeat, $postEmail) {
    if (!hasValidationErrors()) {
        if ($_POST[$postPassword] == $_POST[$postEmail]) {
            addValidationError('Wachtwoord en e-mailadres mogen niet gelijk zijn!');
        } else if (strlen($_POST[$postPassword]) < 8) {
            addValidationError('Wachtwoord moet minimaal 8 karakters lang zijn!');
        } else if (!preg_match("#[0-9]+#", $_POST["$postPassword"])) {
            addValidationError('Wachtwoord moet minimaal 1 getal bevatten!');
        } else if (!preg_match("#[a-zA-Z]+#", $_POST["$postPassword"])) {
            addValidationError('Wachtwoord moet minimaal 1 letter bevatten!');
        } else {
            if ($_POST[$postPassword] != $_POST[$postPasswordRepeat]) {
                addValidationError('Wachtwoorden komen niet overeen!');
            }
        }
    }
}

function checkDates($beginDate, $endDate, $startedDate = null, $willEndDate = null) {
    if (empty($startedDate) || empty($willEndDate)) {
        if ($beginDate < $startedDate || $beginDate > $willEndDate) {
            addValidationError('De datum van het starten moet tussen het begin en het einde zitten');
        } elseif ($endDate > $willEndDate || $endDate < $startedDate) {
            addValidationError('De datum van het eindigen moet tussen het begin en het einde zitten');
        } elseif (empty($beginDate)) {
            addValidationError('Je moet de startdatum nog invullen');
        } elseif (empty($endDate)) {
            addValidationError('Je moet de einddatum nog invullen');
        } elseif ($endDate <= $beginDate) {
            addValidationError('De einddatum kan niet eerder zijn dan de startdatum of gelijk zijn aan de startdatum');
        }
    } else {
        if (empty($beginDate)) {
            addValidationError('Je moet de startdatum nog invullen');
        } elseif (empty($endDate)) {
            addValidationError('Je moet de einddatum nog invullen');
        } elseif ($endDate <= $beginDate) {
            addValidationError('De einddatum kan niet eerder zijn dan de startdatum of gelijk zijn aan de startdatum');
        }
    }
}

function checkYear($beginYear, $endYear) {
    if (empty($_POST[$beginYear])) {
        addValidationError('Je moet het startjaar nog invullen');
    } elseif (empty($_POST[$endYear])) {
        addValidationError('Je moet de eindjaar nog invullen');
    } elseif ($_POST[$endYear] <= $_POST[$beginYear]) {
        addValidationError('Het eindjaar kan niet eerder zijn dan het startjaar of gelijk zijn aan het startjaar');
    }
}
?>

