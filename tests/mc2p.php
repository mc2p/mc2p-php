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
        array('--------TRANSACTION TEST---------'),
        array('------------PAY URL--------------', $pURL),
        array('-----------IFRAME URL------------', $iURL)
    );


    # Get plans
    $plansPaginator = $mc2p->plan->itemList(null);
    $count = $plansPaginator->count;
    $results = $plansPaginator->results; # Application's plans
    $nextList = $plansPaginator->getNextList();

    var_dump(
        array('----------PLAN TEST--------------'),
        array('------------COUNT----------------', $count),
        array('-----------RESULTS---------------', $results),
        array('----------NEXT LIST--------------', $nextList)
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
        array('-------PRODUCT TEST--------------'),
        array('------------ID-------------------', $product->id)
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
        array('---------TAX TEST----------------'),
        array('------------ID-------------------', $tax->id)
    );


    # Check if transaction was paid
    $transaction = $mc2p->Transaction(
        array(
            "id" => "c8325bb3-c24e-4c0c-b0ff-14fe89bf9f1f"
        )
    );
    $transaction->retrieve();

    var_dump(
        array('---TRANSACTION PAID TEST---------'),
        array('------------PAID-----------------', $transaction->status == 'D')
    );


    # Create subscription
    $subscription = $mc2p->Subscription(
        array(
            "currency" => "EUR",
            "plan" => array(
                "name" => "Plan",
                "price" => 5,
                "duration" => 1,
                "unit" => "M",
                "recurring" => True
            ),
            "note" => "Note example"
        )
    );
    $subscription->save();
    $pURL = $subscription->getPayUrl();
    $iURL = $subscription->getIframeUrl();

    var_dump(
        array('--------SUBSCRIPTION TEST--------'),
        array('------------PAY URL--------------', $pURL),
        array('-----------IFRAME URL------------', $iURL)
    );


    # Receive a notification
    $notificationData = $mc2p->NotificationData(array(
        "status" => "D",
        "type" => "P",
        "id" => "c8325bb3-c24e-4c0c-b0ff-14fe89bf9f1f",
        "sale_id" => "d1bb7082-7a97-48c6-893d-4d5febcd463b"
    ), $mc2p);
    $notificationStatus = $notificationData->getStatus(); # Paid
    $notificationTransaction = $notificationData->getTransaction(); # Transaction Paid
    $notificationSale = $notificationData->getSale(); # Sale generated

    var_dump(
        array('-------NOTIFICATION TEST---------'),
        array('-----------STATUS----------------', $notificationStatus),
        array('---------TRANSACTION-------------', $notificationTransaction->id),
        array('------------SALE-----------------', $notificationSale->id)
    );


    # Incorrect data
    $shipping = $mc2p->Shipping(
        array(
            "name" => "Normal shipping",
            "price" => "text" # Price must be number
        )
    );

    try {
        $shipping->save();
    } catch (MC2P\InvalidRequestMC2PError $e) {
        var_dump(
            array('----------ERROR TEST---------'),
            array('---------STATUS CODE---------', $e->getMessage())
        );
    }

} catch (Exception $e) {
    var_dump($e);
    echo "There was an error testing the SDK.\n";
}

exit (0);
