<?php

return [
    // Web pages
    ['method' => 'GET',    'uri' => '/',                   'action' => 'PageController@main'],
    ['method' => 'GET',    'uri' => '/login',              'action' => 'AuthController@loginForm'],
    ['method' => 'POST',   'uri' => '/login',              'action' => 'AuthController@login'],
    ['method' => 'GET',    'uri' => '/register',           'action' => 'AuthController@registerForm'],
    ['method' => 'POST',   'uri' => '/register',           'action' => 'AuthController@register'],
    ['method' => 'GET',    'uri' => '/logout',             'action' => 'AuthController@logout'],

    ['method' => 'GET',    'uri' => '/auctions',           'action' => 'AuctionController@index'],
    ['method' => 'GET',    'uri' => '/auctions/{id}',      'action' => 'AuctionController@show'],
    ['method' => 'GET',    'uri' => '/history/auctions',   'action' => 'PageController@auctionHistory'],
    ['method' => 'GET',    'uri' => '/history/events',     'action' => 'PageController@eventHistory'],
    ['method' => 'GET',    'uri' => '/profile',            'action' => 'PageController@profile'],
    ['method' => 'POST',   'uri' => '/profile',            'action' => 'PageController@updateProfile'],

    // Management (middleware: Role=Leader|Advisor|Admin)
    ['method' => 'GET',    'uri' => '/mgmt',               'action' => 'ManagementController@dashboard'],
    ['method' => 'GET',    'uri' => '/mgmt/auctions',      'action' => 'ManagementController@auctions'],
    ['method' => 'POST',   'uri' => '/mgmt/auctions',      'action' => 'ManagementController@createAuction'],
    ['method' => 'PATCH',  'uri' => '/mgmt/auctions/{id}', 'action' => 'ManagementController@updateAuction'],
    ['method' => 'DELETE', 'uri' => '/mgmt/auctions/{id}', 'action' => 'ManagementController@deleteAuction'],
    ['method' => 'GET',    'uri' => '/mgmt/events',        'action' => 'ManagementController@events'],
    ['method' => 'POST',   'uri' => '/mgmt/events',        'action' => 'EventController@create'],
    ['method' => 'DELETE', 'uri' => '/mgmt/events/{id}',   'action' => 'EventController@delete'],
    ['method' => 'GET',    'uri' => '/mgmt/register',      'action' => 'ManagementController@registerEventForm'],
    ['method' => 'POST',   'uri' => '/mgmt/register',      'action' => 'EventController@registerEvent'],
    ['method' => 'GET',    'uri' => '/mgmt/users',         'action' => 'ManagementController@users'],
    ['method' => 'POST',   'uri' => '/mgmt/users/{id}/role','action' => 'ManagementController@setRole'],
    ['method' => 'GET',    'uri' => '/mgmt/settings',      'action' => 'ManagementController@settings'],
    ['method' => 'POST',   'uri' => '/mgmt/settings',      'action' => 'ManagementController@saveSettings'],
    ['method' => 'GET',    'uri' => '/mgmt/analytics',     'action' => 'AnalyticsController@overview'],

    // API (JSON)
    ['method' => 'GET',    'uri' => '/api/auctions',            'action' => 'Api\\AuctionApi@index'],
    ['method' => 'GET',    'uri' => '/api/auctions/{id}',       'action' => 'Api\\AuctionApi@show'],
    ['method' => 'POST',   'uri' => '/api/auctions/{id}/bids',  'action' => 'Api\\BidApi@place'],
    ['method' => 'GET',    'uri' => '/api/notifications',       'action' => 'Api\\NotificationApi@index'],
    ['method' => 'PATCH',  'uri' => '/api/notifications/{id}',  'action' => 'Api\\NotificationApi@markRead'],
];
