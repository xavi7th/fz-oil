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
    'admin' => [
      'admin.*'
    ],
    'superadmin' => [
      'superadmin.*'
    ],
    'fzstaff' => [
      'fzstaff.*'
    ],
    'salesrep' => [
      'fzstaff.*'
    ],
    'public' => [
      'app.*',
    ],
    'auth' => [
      'auth.*'
    ],
  ],
];
