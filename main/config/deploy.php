<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default deployment strategy
    |--------------------------------------------------------------------------
    |
    | This option defines which deployment strategy to use by default on all
    | of your hosts. Laravel Deployer provides some strategies out-of-box
    | for you to choose from explained in detail in the documentation.
    |
    | Supported: 'basic', 'firstdeploy', 'local', 'pull'.
    |
    */

    'default' => 'routine',

    /*
    |--------------------------------------------------------------------------
    | Custom deployment strategies
    |--------------------------------------------------------------------------
    |
    | Here, you can easily set up new custom strategies as a list of tasks.
    | Any key of this array are supported in the `default` option above.
    | Any key matching Laravel Deployer's strategies overrides them.
    |
    */

    'strategies' => [
      'first_install' => [
        'hook:start',
        'deploy:prepare',
        'deploy:unlock',
        'deploy:lock',
        'deploy:release',
        'deploy:update_code',
        'firstdeploy:shared',
        'deploy:shared',
        'app:custom_deploy_vendor',
        'hook:build',
        'deploy:writable',
        'hook:ready',
        'deploy:symlink',
        'deploy:unlock',
        'cleanup',
        'firstdeploy:cleanup',
        'hook:done',
      ],
      'routine' => [
         'hook:start',
        'deploy:prepare',
        'deploy:lock',
        'deploy:release',
        'deploy:update_code',
        'deploy:shared',
        'app:custom_deploy_vendor',
        'hook:build',
        'deploy:writable',
        'hook:ready',
        'deploy:symlink',
        'deploy:unlock',
        'cleanup',
        'hook:done',
      ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Hooks
    |--------------------------------------------------------------------------
    |
    | Hooks let you customize your deployments conveniently by pushing tasks
    | into strategic places of your deployment flow. Each of the official
    | strategies invoke hooks in different ways to implement their logic.
    |
    */

    'hooks' => [
        // Right before we start deploying.
        'start' => [
            //
        ],
        // Right before we start deploying.
        'cd' => [
        ],

        // Code and composer vendors are ready but nothing is built.
        'build' => [
            // 'npm:install',
            // 'npm:production',
        ],

        // Deployment is done but not live yet (before symlink)
        'ready' => [
            // 'app:change_release_path',

            'app:shared:copy',
            'app:artisan:storage:link',
            'app:artisan:view:clear',
            'app:artisan:config:cache',
            'app:artisan:migrate',
            // 'app:revert_release_path'
        ],

        // Deployment is done and live
        'done' => [
            //  'app:symlink_public_html'
        ],

        // Deployment succeeded.
        'success' => [
            //
        ],

        // Deployment failed.
        'fail' => [
            //
        ],

        // After a deployment has been rolled back.
        'rollback' => [
            //
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Deployment options
    |--------------------------------------------------------------------------
    |
    | Options follow a simple key/value structure and are used within tasks
    | to make them more configurable and reusable. You can use options to
    | configure existing tasks or to use within your own custom tasks.
    |
    */

    'options' => [
        'application' => env('APP_NAME', 'The Elects'),
        'repository' => 'https://xavi7th:Exaviero7th@github.com/xavi7th/theelects.git',
        'bin/npm' => 'npm',
        'bin/composer' => 'composer',
        'composer_options' => 'install --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader',
        'http_user' => 'kortjwxc',
        'writable_mode' => 'chmod',
        'shared_files' => ['main/.env', 'main/.env.example', 'public_html/mix-manifest.json'],
        'shared_dirs' => [
          'main/storage',
          'public_html/css',
          'public_html/js',
          'public_html/fonts',
          'public_html/img',
          ],

        // 'branch' => 'releases'
    ],

    /*
    |--------------------------------------------------------------------------
    | Hosts
    |--------------------------------------------------------------------------
    |
    | Here, you can define any domain or subdomain you want to deploy to.
    | You can provide them with roles and stages to filter them during
    | deployment. Read more about how to configure them in the docs.
    |
    */

    'hosts' => [
        'jollof.kortch.com' => [
            'deploy_path' => '/home/kortjwxc/jollof.kortch.com',
            'user' => 'kortjwxc',
            // 'identityFile' => '~/.ssh/id_rsa',
            // 'forwardAgent' => true,
            'port' => 21098
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Localhost
    |--------------------------------------------------------------------------
    |
    | This localhost option give you the ability to deploy directly on your
    | local machine, without needing any SSH connection. You can use the
    | same configurations used by hosts to configure your localhost.
    |
    */

    'localhost' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Include additional Deployer recipes
    |--------------------------------------------------------------------------
    |
    | Here, you can add any third party recipes to provide additional tasks,
    | options and strategies. Therefore, it also allows you to create and
    | include your own recipes to define more complex deployment flows.
    |
    */

    'include' => [
      base_path('/../deployer_task.php')
    ],

    /*
    |--------------------------------------------------------------------------
    | Use a custom Deployer file
    |--------------------------------------------------------------------------
    |
    | If you know what you are doing and want to take complete control over
    | Deployer's file, you can provide its path here. Note that, without
    | this configuration file, the root's deployer file will be used.
    |
    */

    'custom_deployer_file' => false,

];
