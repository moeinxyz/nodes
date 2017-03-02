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
# Dont share vendor directory, Composer has problem with down grading
# set :linked_dirs, fetch(:linked_dirs, []).push('runtime', 'vendor', 'web/userassets', 'log')
set :linked_dirs, fetch(:linked_dirs, []).push('runtime', 'web/userassets', 'log')

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
        execute "cd #{release_path} && ./yii auto-migrate"
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

  task :dir_config do
    on roles(:web) do
      within release_path do
        execute "cd #{release_path} && chmod -R 777 web/assets"
        execute "cd #{release_path}/web/userassets && mkdir -p post user && chmod 777 *"
        execute "cd #{release_path}/web/userassets/user && mkdir -p covers pictures && chmod 777 *"
        execute "cd #{release_path}/web/userassets/post && mkdir -p covers && chmod 777 *"

        execute "cd #{release_path} && chmod 777 runtime"
        execute "cd #{release_path} && rm -rf Twig/cache"
        execute "cd #{release_path}/runtime && mkdir -p temp/covers && chmod 777 temp/covers"
        execute "cd #{release_path}/runtime && mkdir -p temp/images && chmod 777 temp/images"
        execute "cd #{release_path}/runtime && mkdir -p temp/pictures && chmod 777 temp/pictures"
        execute "cd #{release_path}/runtime && mkdir -p temp/postcovers && chmod 777 temp/postcovers"
        execute "cd #{release_path}/runtime && mkdir -p Twig/cache && chmod 777 Twig/cache"
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

  task :flush_cache do
    on roles(:web) do
      within release_path do
        execute "cd #{release_path}  && ./yii cache/flush-all"
        execute "cd #{release_path}  && ./yii cache/flush-schema --interactive=0"
        execute "cd #{release_path}  && ./yii cache/flush-schema --interactive=0"
        execute "cd #{release_path}  && curl -sO http://gordalina.github.io/cachetool/downloads/cachetool.phar"
        execute "cd #{release_path}  && chmod +x cachetool.phar"
        execute "cd #{release_path}  && php cachetool.phar opcache:reset --fcgi=/var/run/php5-fpm.sock"
        execute "cd #{release_path}  && php cachetool.phar stat:clear --fcgi=/var/run/php5-fpm.sock"
        execute "cd #{release_path}  && rm -f cachetool.phar"
      end
    end
  end

  after :updated, "deploy:composer"
  after :updated, "deploy:migrate"
  after :updated, "deploy:compile"
  after :updated, "deploy:dir_config"
  after :updated, "deploy:set_env"
  after :updated, "deploy:flush_cache"
end
