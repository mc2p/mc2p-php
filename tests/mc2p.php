#!/usr/bin/php
<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use MC2P\MC2PClient;

// Upload a publicly accessible file. The file size and type are determined by the SDK.
try {
    $key = 'fd1e7e20a676';
    $secret = 'a88402f080b54547ad07114a13c1a375';

    $mc2p = new MC2PClient($key, $secret);

    # Create transaction
    $transaction = $mc2p->Transaction(
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
    $pURL = $transaction->getPayUrl();
    $iURL = $transaction->getIframeUrl();

    var_dump(
        array('------------TRANSACTION---------------'),
        array('------------PAY URL---------------', $pURL),
        array('------------IFRAME URL------------', $iURL)
    );


    # Get plans
    $plans_paginator = $mc2p->plan->itemList(null);
    $count = $plans_paginator->count;
    $results = $plans_paginator->results; # Application's plans
    $nextList = $plans_paginator->getNextList();

    var_dump(
        array('------------PLAN----------------'),
        array('------------COUNT---------------', $count),
        array('------------RESULTS-------------', $results),
        array('------------NEXT LIST-----------', $nextList)
    );


    # Get product, change and save
    $product = $mc2p->Product(
        array(
            "id" => "59ba4752-1679-43b5-b0c7-2c48fdb77e4e"
        )
    );
    $product->retrieve();
    $product->price = 10;
    $product->save();

    var_dump(
        array('------------PRODUCT----------------'),
        array('------------ID---------------------', $product->id)
    );


    # Create and delete tax
    $tax = $mc2p->Tax(
        array(
            "name" => "Tax",
            "percent" => 5
        )
    );
    $tax->save();
    $tax->delete();

    var_dump(
        array('------------TAX----------------'),
        array('------------ID---------------------', $tax->id)
    );


} catch (Exception $e) {
    var_dump($e);
    echo "There was an error testing the SDK.\n";
}

exit (0);
