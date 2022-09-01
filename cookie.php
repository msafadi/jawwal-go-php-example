<?php

setcookie('count', "", time() - 60 * 60 * 24);
setcookie('count', "", time() - 60 * 60 * 24, '/');

var_dump($_COOKIE);

?>