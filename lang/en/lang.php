<?php

return [
    'plugin' =>  [
        'label' => 'Horeca',
        'description' => 'Horeca\'s aggregator',
        'permissions' => [
            'operator' => 'Operator',
            'manager' => 'Manager'
        ]
    ],

    'firm'      => [
        'label'         => 'Firm',
        'new'           => 'New Firm'
    ],
    'tag'       => [
        'label'         => 'Tag',
        'new'           => 'New Tag'
    ],
    'price'     => [
        'label'         => 'Price',
        'new'           => 'New Price'
    ],
    'node'      => [
        'label'         => 'Node',
        'new'           => 'New Node',
        'return'        => 'Return  to Nodes',
        'reorder'       => 'Reoder',
        'reordernodes'  => 'Reoder Nodes',
        'manageorder'   => 'Manage Nodes Order',
    ],
    'placement' => [
        'label'         => 'Placement',
        'new'           => 'New Placement',
    ],
    'table'     => [
        'label'         => 'Tables'
    ],
    'event'     => [
        'label'         => 'Event',
        'new'           => 'New Event'
    ],
    'comment'   => [
        'label'         => 'Comment',
        'new'           => 'New Comment',
        'create'        => 'Create Comment',
        'edit'          => 'Edit Comment',
        'preview'       => 'Preview Comment',
        'manage'        => 'Manage Comments',
        'return'        => 'Return to Comments',
        'viewed'        => 'Viewed',
        'notviewed'     => 'Not Viewed'
    ],
    'order'     => [
        'label'         => 'Order',
        'notpayed'      => 'Not Payed',
        'payed'         => 'Payed'
    ],
    'payment'     => [
        'label'         => 'Payment'
    ],
    'payment_method'     => [
        'label'         => 'Payment Method'
    ],
    'horeca'    => [
        'label'         => 'Horeca',
        'desc'          => 'Provides view with pagination, filtering and map',
        'pageSize'      => [
                'title'             => 'Page size',
                'description'       => 'Number of elements per page',
                'validationMessage' => 'The Page size value is required and should be integer.'
        ]
    ],
    'field'     => [
        'name'              => 'Name',
        'date'              => 'Date',
        'description'       => 'Description',
        'images'            => 'Images',
        'image'             => 'Image',
        'firm'              => 'Firm',
        'payment_method'    => 'Payment Method',
        'tags'              => 'Tags',
        'active'            => 'Active',
        'address'           => 'Address',
        'phone'             => 'Phone',
        'activity'          => 'Activity Period',
        'break'             => 'Break Period',
        'avgbill'           => 'Average Bill',
        'map'               => 'Map',
        'users'             => 'Users',
        'user'              => 'User',
        'tables'            => 'Tables',
        'ingredients'       => 'Ingredients',
        'portion'           => 'Portion',
        'cost'              => 'Cost',
        'isnew'             => 'Novelty',
        'specialoffer'      => 'Special Offer',
        'type'              => 'Type',
        'price'             => 'Price',
        'order'             => 'Order',
        'total'             => 'Total',
        'prices'            => 'Prices',
        'comments'          => 'Comments',
        'rating'            => [
                'label'         => 'Rating',
                'bad'           => 'Bad',
                'good'          => 'Good',
                'better'        => 'Better',
                'best'          => 'Best'
        ],
        'status'            => 'Status',
        'content'           => 'Content',
        'holydays'          => [
                'label'         => 'Holydays',
                'monday'        => 'Monday',
                'tuesday'       => 'Tuesday',
                'wednesday'     => 'Wednesday',
                'thursday'      => 'Thursday',
                'friday'        => 'Friday',
                'saturday'      => 'Saturday',
                'sunday'        => 'Sunday'
        ]
    ],
    'common'    => [
        'create'        => 'Create',
        'createclose'   => 'Create and Close',
        'cancel'        => 'Cancel',
        'save'          => '<u>S</u>ave'
    ]
];