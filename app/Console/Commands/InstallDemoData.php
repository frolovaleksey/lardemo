<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Author\AuthorRepository;
use App\Services\Book\BookRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

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
        $this->truncateDB();

        $this->createRolesAndPermissions();
        echo "Roles And Permissions created\n";

        $this->createAdminUser();
        echo "Users created\n";

        $this->createAuthorsAndBooks();
        echo "Authors And Books created\n";
    }

    protected function truncateDB()
    {
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $tableName = reset($table);
            if ($tableName !== 'migrations') {
                DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                DB::table($tableName)->truncate();
                DB::statement("SET FOREIGN_KEY_CHECKS=1;");
            }
        }

        echo "All tables except migrations are cleared!";
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

    public function createAuthorsAndBooks()
    {
        $authors = [
            [
                'first_name' => 'Terry',
                'last_name' => 'Pratchett',
            ],
            [
                'first_name' => 'Neil',
                'last_name' => 'Gaiman',
            ],
            [
                'first_name' => 'Isaac',
                'last_name' => 'Asimov',
            ],
        ];

        $authorRepository = app(AuthorRepository::class);

        $authorModels = [];
        foreach ($authors as $authorData){
            $authorModel = $authorRepository->findByFirstLastName($authorData['first_name'], $authorData['last_name']);
            if($authorModel===null){
                $authorModel = $authorRepository->create($authorData);
            }
            $authorModels[] = $authorModel;
        }

        $books = [
            [
                'title' => 'The Colour of Magic',
                'price' => 297,
                'authors' => [$authorModels[0]->id]
            ],
            [
                'title' => 'The Light Fantastic',
                'price' => 320,
                'authors' => [$authorModels[0]->id]
            ],
            [
                'title' => 'Sourcery',
                'price' => 269,
                'authors' => [$authorModels[0]->id]
            ],
            [
                'title' => 'Good Omens',
                'price' => 229,
                'authors' => [$authorModels[0]->id, $authorModels[1]->id]
            ],
            [
                'title' => 'American Gods',
                'price' => 139,
                'authors' => [$authorModels[1]->id]
            ],
            [
                'title' => 'The Graveyard Book',
                'price' => 229,
                'authors' => [$authorModels[1]->id]
            ],
            [
                'title' => 'I, Robot',
                'price' => 204,
                'authors' => [$authorModels[2]->id]
            ],
            [
                'title' => 'Foundation',
                'price' => 269,
                'authors' => [$authorModels[2]->id]
            ],
        ];
        $bookRepository = app(BookRepository::class);
        foreach ($books as $bookData){
            $bookRepository->create($bookData);
        }
    }
}
