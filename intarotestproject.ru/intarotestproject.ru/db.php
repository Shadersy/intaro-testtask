<?php

require "libs/rb-mysql.php";
R::setup( 'mysql:host=localhost;dbname=test_db',
        'root', '' );
		
session_start();