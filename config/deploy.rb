# config valid for current version and patch releases of Capistrano
# lock "~> 3.12.1"

set :application, "ImOk_API"
set :repo_url, "https://github.com/CDA-Promo2/imok_api.git"

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, "/var/www/clients/client51/web49/web/imok_api"

namespace :deploy do

    desc "Build"
    after :updated, :build do
        on roles(:app) do
            within release_path  do
                execute :composer, "install --no-dev --quiet" # install dependencies
                execute :chmod, "u+x artisan" # make artisan executable
                execute :chmod, "-R 775 storage"
                execute :php, "artisan swagger-lume:generate" #generate api documentation
            end
        end
    end
end


# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: "log/capistrano.log", color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
append :linked_files, ".env"

# Default value for linked_dirs is []
# append :linked_dirs, "log", "tmp/pids", "tmp/cache", "tmp/sockets", "public/system"

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for local_user is ENV['USER']
# set :local_user, -> { `git config user.name`.chomp }

# Default value for keep_releases is 5
set :keep_releases, 3

# Uncomment the following to require manually verifying the host key before first deploy.
# set :ssh_options, verify_host_key: :secure
