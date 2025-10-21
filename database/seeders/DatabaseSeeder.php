<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        foreach (['Food', 'Lifestyle', 'Travel', 'Technology', 'Health'] as $name) {
            Tag::create(['name' => $name]);
        }

        User::all()->each(function (User $user) {
            Article::factory(10)
                ->for($user)
                ->create()
                ->each(function (Article $article) {
                    foreach (range(1,2) as $i) {
                        ArticleTag::firstOrCreate(['article_id' => $article->id, 'tag_id' => rand(1, 5)]);
                    }
                    Comment::factory(5)
                        ->for($article)
                        ->for(tap(new User())->fill(['id' => rand(1, 11)]))
                        ->create();
                });
        });
    }
}
