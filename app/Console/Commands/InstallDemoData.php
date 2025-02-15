<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Author\AuthorRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InstallDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan app:install-demo-data
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
        echo "\n";
        $this->createRolesAndPermissions();
        //echo "Roles And Permissions created\n";

        //$this->createAdminUser();
        //echo "Users created\n";

        //$this->createAuthors();
        echo "Authors created\n";
    }

    public function createRolesAndPermissions(): void
    {
        $role = Role::findOrCreate('admin');

        $permissions = [
            'Http_Controller_AuthorController_create',
            'Http_Controller_AuthorController_store',
            'Http_Controller_AuthorController_edit',
            'Http_Controller_AuthorController_update',
            'Http_Controller_AuthorController_destroy',

            'Http_Controller_BookController_create',
            'Http_Controller_BookController_store',
            'Http_Controller_BookController_edit',
            'Http_Controller_BookController_update',
            'Http_Controller_BookController_destroy',
        ];

        foreach ($permissions as $permissionName){
            $permission = Permission::findOrCreate($permissionName);
            $role->givePermissionTo( $permission );
        }
    }

    public function createAdminUser(): void
    {
        $user = User::where('email', 'test@test.ts')->first();
        if( $user === null ){
            $user = new User();
            $user->name = 'Admin';
            $user->email = 'test@test.ts';
            $user->password = Hash::make('AdminAdmin');
            $user->save();

            $user->assignRole('admin');
        }
    }

    public function createAuthors()
    {
        $authors = [
            [
                'first_name' => 'Neil',
                'last_name' => 'Gaiman',
            ],
            [
                'first_name' => 'Terry',
                'last_name' => 'Pratchett',
            ],
            [
                'first_name' => 'Isaac',
                'last_name' => 'Asimov',
            ],
        ];

        $authorRepository = app(AuthorRepository::class);

        foreach ($authors as $authorData){
            if(!$authorRepository->findByFirstLastName($authorData['first_name'], $authorData['last_name'])){
                $authorRepository->create($authorData);
            }
        }
    }
}
