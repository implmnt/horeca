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
        'returntonodes' => 'Return to Nodes',
        'reordernodes'  => 'Reoder Nodes',
        'manageorder'   => 'Manage Nodes Order',
    ],
    'placement' => [
        'label'         => 'Placement',
        'new'           => 'New Placement',
    ],
    'event'     => [
        'label'         => 'Event',
        'new'           => 'New Event'
    ],
    'comment'   => [
        'label'         => 'Comment',
        'new'           => 'New Comment'
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
        'name'          => 'Name',
        'date'          => 'Date',
        'description'   => 'Description',
        'images'        => 'Images',
        'image'         => 'Image',
        'firm'          => 'Firm',
        'tags'          => 'Tags',
        'active'        => 'Active',
        'address'       => 'Address',
        'phone'         => 'Phone',
        'activity'      => 'Activity Period',
        'avgbill'       => 'Average Bill',
        'map'           => 'Map',
        'users'         => 'Users',
        'tables'        => 'Tables',
        'ingredients'   => 'Ingredients',
        'portion'       => 'Portion',
        'cost'          => 'Cost',
        'isnew'         => 'Novelty',
        'specialoffer'  => 'Special Offer',
        'type'          => 'Type',
        'price'         => 'Price'
    ]
];