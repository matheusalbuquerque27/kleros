<?php

function formatarData($data, $formato = 'd/m/Y') {
    if ($data) {
        return date($formato, strtotime($data));
    }
    return null;
}


?>