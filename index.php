<?php

spl_autoload_register(function ($class_name) {
    include './inc/' . $class_name . '.php';
});

$kernel = new Kernel();
$kernel->Run();
