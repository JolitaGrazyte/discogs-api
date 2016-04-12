<?php

return [
    'artist' => 'artists/{id}',
    'artist-releases' => 'artists/{id}/releases',
    'label' => 'labels/{id}',
    'label-releases' => 'labels/{id}/releases',
    'release' => 'releases/{id}',

    'messages' => 'marketplace/orders/{id}/messages',
    'orders' => 'marketplace/orders',
    'order' => 'marketplace/orders/{id}',
    'search' => 'database/search',
    'inventory' => 'users/{username}/inventory',
];
