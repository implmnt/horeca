<?php

return [
    'plugin'    =>  [
        'name' => 'Food',
        'description' => 'Food Catalog',
        'controller' => [
            'tags'              => 'Tags',
            'firms'             => 'Firms',
            'prices'            =>  'Prices',
            'nodes'             =>  'Nodes',
            'placements' =>  'Placements'
        ]
    ],
    'misc' => [
        'nodes' => 'Nodes',
        'newnode' => 'New Node',
        'manageorder' => 'Manage Nodes Order',
        'returntonodes' => 'Return to Nodes'
    ],
    'nodes' => [
        'reordernodes' => 'Reoder Nodes',
        'delete_selected_success' => 'Successfully deleted the selected nodes.',
        'delete_selected_confirm' => 'Delete the selected nodes?',
        'delete_selected_empty' => 'There are no selected nodes to delete.'
    ]
];