<?php
ob_start();
if(phpversion() < "5.5.9") {
    die("php version must be <= 5.5.9");
}
require 'boot.php';