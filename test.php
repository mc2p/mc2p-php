#!/usr/bin/php
<?php

require_once(join(DIRECTORY_SEPARATOR, array('mc2p', 'MC2P.php')));

use MyChoice2Pay as MC2P;


// Upload a publicly accessible file. The file size and type are determined by the SDK.
try {
    $key = 'fd1e7e20a676';
    $secret = 'a88402f080b54547ad07114a13c1a375';

    $client = new MC2P\APIClient($key, $secret);

    # Create transaction
    $transaction = $client->Transaction(
        array(
            "currency" => "EUR",
            "products" => array(
                array(
                    "amount" => 1,
                    "product" => array(
                        "name" => "Product",
                        "price" => 5
                    )
                )
            )
        )
    );

    $transaction->save();

    print_r('SAVED!');

    print_r($transaction->pay_url); # Send user to this url to pay

} catch (Exception $e) {
    var_dump($e);
    echo "There was an error testing the SDK.\n";
}

exit (0);