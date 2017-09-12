# MyChoice2Pay PHP


# Overview

MyChoice2Pay PHP provides integration access to the MyChoice2Pay API.

[![Build Status](https://travis-ci.org/mc2p/mc2p-php.svg?branch=master)](https://travis-ci.org/mc2p/mc2p-php)

# Installation

You can install using `composer`:

    composer require mc2p/mc2p-php

# Quick Start Example

    require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

    use MC2P\MC2PClient;

    $mc2p = MC2PClient('KEY', 'SECRET_KEY')

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
    $transaction->getPayUrl() # Send user to this url to pay
    $transaction->getIframeUrl() # Use this url to show an iframe in your site

    # Get plans
    $plansPaginator = $mc2p->plan->itemList(null);
    $count = $plansPaginator->count;
    $results = $plansPaginator->results; # Application's plans
    $nextList = $plansPaginator->getNextList();

    # Get product, change and save
    $product = $mc2p->Product(
        array(
            "id" => "PRODUCT-ID"
        )
    );
    $product->retrieve();
    $product->price = 10;
    $product->save();

    # Create and delete tax
    $tax = $mc2p->Tax(
        array(
            "name" => "Tax",
            "percent" => 5
        )
    );
    $tax->save();
    $tax->delete();

    # Check if transaction was paid
    $transaction = $mc2p->Transaction(
        array(
            "id" => "c8325bb3-c24e-4c0c-b0ff-14fe89bf9f1f"
        )
    );
    $transaction->retrieve();
    $transaction->status == 'D' # Paid

    # Create subscription
    $subscription = $mc2p->Subscription(
        array(
            "currency" => "EUR",
            "plan_id" => "PLAN-ID",
            "note" => "Note example"
        )
    )
    # or
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
    $subscription->save()
    $subscription->getPayUrl() # Send user to this url to pay
    $subscription->getIframeUrl() # Use this url to show an iframe in your site

    # Receive a notification
    $notificationData = $mc2p->NotificationData(JSON_DICT_RECEIVED_FROM_MYCHOICE2PAY, $mc2p);
    $notificationData->getStatus() == 'D'; # Paid
    $notificationData->getTransaction(); # Transaction Paid
    $notificationData->getSale(); # Sale generated

# Exceptions

    require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

    use MC2P\MC2PClient;
    
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
        $e->getMessage(); # Status code
    }
