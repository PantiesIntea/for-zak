<?php

require "libs/rb.php";
R::setup( 'mysql:host=localhost;dbname=someshop',
        'root', 'root' );
    
session_start();

