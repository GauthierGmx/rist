<?php

    $s = 'mdpJPDuibuis0&';
    $salt = 'salt5678901234567890123456789012';
    $salt_prefix = 'salt567890123456789010';
    $hashMotDePasse = password_hash($s, PASSWORD_BCRYPT, array('salt' => $salt_prefix));
    echo $hashMotDePasse;



    
    

?>