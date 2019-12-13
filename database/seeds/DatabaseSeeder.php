<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $numOfUsers = 10;

        // Create test user.
        App\User::create([
            'first_name' => "Bob",
            'last_name' => "Bobbinton",
            'username' => "testuser",
            'email' => "test@example.com",
            'password' => Hash::make("password"),
        ]);
        
        // Create a bunch of users.
        factory(App\User::class, ($numOfUsers - 1))->create();

        // Create a bunch of discussions.
        for ($i = 0; $i < 30; $i++) {
            factory(App\Discussion::class)->create(['user_id' => rand(1, $numOfUsers)]);
        }

        // Get all of the discussions that were just created. 
        $discussions = App\Discussion::all();

        // Create a bunch of posts for each discussion. 
        foreach ($discussions as $d) {
            $d->posts()->save(
                factory(App\Post::class)->make(['discussion_id' => NULL, 'user_id' => $d->user->id, 'is_reply' => false])
            );

            for ($i = 0; $i < rand(0, 15); $i++) {
                $d->posts()->save(
                    factory(App\Post::class)->make(['discussion_id' => NULL, 'user_id' => rand(1, 10)])
                );
            }
        }
        // $this->call(UsersTableSeeder::class);
    }
}
