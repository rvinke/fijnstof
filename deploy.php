<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'fijnstof');

// Project repository
set('repository', 'https://github.com/rvinke/fijnstof.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', ['storage']);
add('writable_dirs', ['storage', 'vendor']);


// Hosts

host('emis3.test.experda.nl')
    ->stage('production')
    ->user('rvinke')
    ->set('branch', 'master')
    ->set('deploy_path', '/mnt/storage/{{application}}');

// Tasks



// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

//before('deploy:symlink', 'artisan:migrate');

task('reload:php-fpm', function () {
    run('sudo /usr/sbin/service php8.0-fpm restart');
});


after('deploy', 'reload:php-fpm');

