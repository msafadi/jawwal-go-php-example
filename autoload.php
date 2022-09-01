<?php

spl_autoload_register(function($classname) {
    include __DIR__ . "/includes/{$classname}.php";
});