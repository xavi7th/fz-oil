<?php

return [
  // 'whitelist' => ['home', 'api.*'],
  /**
   * !Blacklist hidden routes from the general @routes call
   * ? Access the blacklisted routes using group routes
   * * @routes('admin'), @routes(['admin', 'superadmin]), @routes('*')
   */
  'blacklist' => ['debugbar.*', 'horizon.*', 'ignition.*', 'admin.*', 'superadmin.*'],
  'groups' => [
    'superadmin' => [
      'superadmin.*'
    ],
    'salesrep' => [
      'salesrep.*'
    ],
    'supervisor' => [
      'supervisor.*'
    ],
    'generic' => [
      'fzcustomer.*',
      'purchaseorders.*',
    ],
    'public' => [
      'app.*',
    ],
    'auth' => [
      'auth.*'
    ],
  ],
];
