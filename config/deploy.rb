# config valid only for current version of Capistrano
lock '3.5.0'

set :application, 'nodes'
set :repo_url, 'git@gitlab.com:nodes.ir/nodes.git'

# Default branch is :master
ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, '/var/www/nodes'

# Default value for :scm is :git
set :scm, :git

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: 'log/capistrano.log', color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
set :linked_files, fetch(:linked_files, []).push('config/.env')

# Default value for linked_dirs is []
set :linked_dirs, fetch(:linked_dirs, []).push('runtime', 'vendor', 'web/userassets', 'log')

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
set :keep_releases, 15

namespace :deploy do

  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
      # Here we can do anything such as:
      # within release_path do
      #   execute :rake, 'cache:clear'
      # end
    end
  end

  task :composer do
    on roles(:web) do
      within release_path do
        execute "cd #{release_path} && composer global require \"fxp/composer-asset-plugin:1.1.4\""
        execute "cd #{release_path} && composer install"
      end
    end
  end

  task :migrate do
    on roles(:db) do
      within release_path do
        execute "cd #{release_path} && ./yii migrate --migrationPath=@app/modules/user/migrations --interactive=0"
        execute "cd #{release_path} && ./yii migrate --migrationPath=@app/modules/post/migrations --interactive=0"
        execute "cd #{release_path} && ./yii migrate --migrationPath=@app/modules/embed/migrations --interactive=0"
        execute "cd #{release_path} && ./yii migrate --migrationPath=@app/modules/social/migrations --interactive=0"
      end
    end
  end

  task :compile do
    on roles(:web) do
      within release_path do
        execute "cd #{release_path} && ./yii asset assets.php config/assets-prod.php --interactive=0"
      end
    end
  end

  task :chmod do
    on roles(:web) do
      within release_path do
        execute "cd #{release_path} && chmod -R 777 web/assets"
      end
    end
  end

  task :set_env do
    on roles(:web) do
      within release_path do
        execute "cd #{release_path} && sed -i \"s/defined('YII_DEBUG') or define('YII_DEBUG', true);/defined('YII_DEBUG') or define('YII_DEBUG', false);/\" web/index.php"
        execute "cd #{release_path} && sed -i \"s/defined('YII_ENV') or define('YII_ENV', 'dev');/defined('YII_ENV') or define('YII_ENV', 'prod');/\" web/index.php"
      end
    end
  end

  after :updated, "deploy:composer"
  after :updated, "deploy:migrate"
  after :updated, "deploy:compile"
  after :updated, "deploy:chmod"
  after :updated, "deploy:set_env"
end
