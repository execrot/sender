<?php

/**
 * If will be available class with name \App\Bootstrap and it will be extends from \Light\BootstrapAbstract
 * it will be initialized and called each function
 */

return [

  'light' => [
    'exception' => true,
    'phpIni' => [
      'display_errors' => '1',
    ],
    'startup' => [
      'error_reporting' => E_ALL,
      'set_time_limit' => 0
    ],
    'loader' => [
      'path' => realpath(dirname(__FILE__)) . '/app',
      'namespace' => 'App',
    ],
    'modules' => '\\App\\Module'
  ],

  'db' => [
    'servers' => [[
      'host' => 'localhost',
      'port' => 27017
    ]],
    'driver' => 'mongodb',
    'db' => 'sender'
  ],

  'router' => [

    'cli' => [
      'strict' => true,
      'module' => 'cli',
      'routes' => [
        'add-user' => [
          'controller' => 'user',
          'action' => 'add'
        ],
        'message' => [
          'controller' => 'message',
          'action' => 'queue'
        ]
      ],
    ],

    '*' => [
      'strict' => true,
      'module' => 'api',
      'routes' => [
        '/settings/add' => [
          'method' => 'put',
          'controller' => 'settings',
          'action' => 'add',
        ],
        '/settings' => [
          'method' => 'get',
          'controller' => 'settings',
          'action' => 'index',
        ],
        '/settings/item' => [
          'method' => 'get',
          'controller' => 'settings',
          'action' => 'item',
        ],
        '/settings/edit' => [
          'method' => 'post',
          'controller' => 'settings',
          'action' => 'edit',
        ],
        '/settings/delete' => [
          'method' => 'delete',
          'controller' => 'settings',
          'action' => 'delete',
        ],

        '/message/add' => [
          'method' => 'put',
          'controller' => 'message',
          'action' => 'add',
        ],
        '/message' => [
          'method' => 'get',
          'controller' => 'message',
          'action' => 'index',
        ],
        '/message/item' => [
          'method' => 'get',
          'controller' => 'message',
          'action' => 'item',
        ],
        '/message/edit' => [
          'method' => 'post',
          'controller' => 'message',
          'action' => 'edit',
        ],
        '/message/delete' => [
          'method' => 'delete',
          'controller' => 'message',
          'action' => 'delete',
        ],

        '/template/add' => [
          'method' => 'put',
          'controller' => 'template',
          'action' => 'add',
        ],
        '/template' => [
          'method' => 'get',
          'controller' => 'template',
          'action' => 'index',
        ],
        '/template/item' => [
          'method' => 'get',
          'controller' => 'template',
          'action' => 'item',
        ],
        '/template/edit' => [
          'method' => 'post',
          'controller' => 'template',
          'action' => 'edit',
        ],
        '/template/delete' => [
          'method' => 'delete',
          'controller' => 'template',
          'action' => 'delete',
        ],
      ]
    ]
  ],
];