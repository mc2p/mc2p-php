# MyChoice2Pay PHP


# Overview

MyChoice2Pay PHP provides integration access to the MyChoice2Pay API.


# Installation

You can install using `composer`:

    composer require mc2p/mc2p-php

# Quick Start Example

    require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

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
    plans_paginator = mc2p.plan.list()
    plans_paginator.count
    plans_paginator.results # Application's plans
    plans_paginator.get_next_list()

    # Get product, change and save
    product = mc2p.Product.get("PRODUCT-ID")
    product.price = 10
    product.save()

    # Create and delete tax
    tax = mc2p.Tax({
        "name": "Tax",
        "percent": 5
    })
    tax.save()
    tax.delete()

    # Check if transaction was paid
    transaction = mc2p.Transaction.get("TRANSACTION-ID")
    transaction.status == 'D' # Paid

    # Create subscription
    subscription = mc2p.Subscription({
        "currency": "EUR",
        "plan_id": "PLAN-ID",
        "note": "Note example"
    })
    # or
    subscription = mc2p.Subscription({
        "currency": "EUR",
        "plan": {
            "name": "Plan",
            "price": 5,
            "duration": 1,
            "unit": "M",
            "recurring": True
        },
        "note": "Note example"
    })
    subscription.save()
    subscription.pay_url # Send user to this url to pay
    subscription.iframe_url # Use this url to show an iframe in your site

    # Receive a notification
    notification_data = mc2p.NotificationData(JSON_DICT_RECEIVED_FROM_MYCHOICE2PAY)
    notification_data.status == 'D' # Paid
    notification_data.transaction # Transaction Paid
    notification_data.sale # Sale generated

# Exceptions

    from mc2p.errors import InvalidRequestError

    # Incorrect data
    shipping = mc2p.Shipping({
        "name": "Normal shipping",
        "price": "text" # Price must be number
    })
    try:
        shipping.save()
    except InvalidRequestError as e:
        e._message # Status code of error
        e.json_body # Info from server
        e.resource # Resource used to make the server request
        e.resource_id # Resource id requested
