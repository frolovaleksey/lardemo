<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InstallDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install-demo-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->createRolesAndPermissions();
        $this->createUsers();
    }

    protected function createRolesAndPermissions(): void
    {
        echo "\n";

        $role = Role::create(['name' => 'admin']);

        $role->givePermissionTo( Permission::create(['name' => 'Http_Controller_AuthorController_create']) );
        $role->givePermissionTo( Permission::create(['name' => 'Http_Controller_AuthorController_store']) );

        echo "Roles And Permissions created\n";
    }

    protected function createUsers(): void
    {
        echo "\n";

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'test@test.ts';
        $user->password = Hash::make('AdminAdmin');
        $user->save();

        $user->assignRole('admin');

        echo "Users created\n";
    }
}
