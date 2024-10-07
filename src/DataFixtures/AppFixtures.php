<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Language;
use App\Entity\Media;
use App\Entity\Movie;
use App\Entity\Playlist;
use App\Entity\PlaylistMedia;
use App\Entity\Season;
use App\Entity\Serie;
use App\Entity\Subscription;
use App\Entity\User;
use App\Entity\WatchHistory;
use App\Enum\UserAccountStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\Collections\ArrayCollection;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create sample languages
        $english = new Language();
        $english->setName('English');

        $french = new Language();
        $french->setName('French');

        $manager->persist($english);
        $manager->persist($french);

        // Create a few categories
        $categories = [];
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Create a few sample users
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setUsername('user' . $i);
            $user->setEmail('user' . $i . '@example.com');

            // Hash the password (assuming password hashing is enabled)
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password' . $i);
            $user->setPassword($hashedPassword);

            // Set a default account status (change this as needed)
            $user->setAccountStatus(UserAccountStatusEnum::ACTIVE);

            $manager->persist($user);
            $users[] = $user;

            // Create watch history for each user
            $watchHistory = new WatchHistory();
            $watchHistory->setWatcher($user);
            // Add relevant details to WatchHistory entity
            $manager->persist($watchHistory);
        }

        // Create a few movies and associate them with categories and languages
        for ($i = 1; $i <= 5; $i++) {
            $movie = new Movie();
            $movie->setTitle('Movie ' . $i);
            $movie->setLanguage($i % 2 == 0 ? $english : $french);
            $movie->setCategory($categories[$i % 3]);

            $manager->persist($movie);
        }

        // Create a series with seasons and episodes
        for ($i = 1; $i <= 2; $i++) {
            $serie = new Serie();
            $serie->setTitle('Serie ' . $i);
            $serie->setLanguage($i % 2 == 0 ? $english : $french);
            $manager->persist($serie);

            // Add seasons to series
            for ($j = 1; $j <= 2; $j++) {
                $season = new Season();
                $season->setTitle('Season ' . $j . ' of ' . $serie->getTitle());
                $season->setSerie($serie);
                $manager->persist($season);

                // Add episodes to each season
                for ($k = 1; $k <= 3; $k++) {
                    $episode = new Episode();
                    $episode->setTitle('Episode ' . $k . ' of ' . $season->getTitle());
                    $episode->setSeason($season);
                    $manager->persist($episode);
                }
            }
        }

        // Create playlists for users
        foreach ($users as $user) {
            for ($j = 1; $j <= 2; $j++) {
                $playlist = new Playlist();
                $playlist->setName('Playlist ' . $j . ' for ' . $user->getUsername());
                $playlist->setCreator($user);
                $manager->persist($playlist);

                // Add media to playlist
                $playlistMedia = new PlaylistMedia();
                // Assume you have some logic to get media to add
                // $playlistMedia->setMedia($someMedia);
                $playlistMedia->setPlaylist($playlist);
                $manager->persist($playlistMedia);
            }
        }

        // Create comments for some media
        foreach ($users as $user) {
            $comment = new Comment();
            $comment->setContributor($user);
            $comment->setContent('This is a comment by ' . $user->getUsername());
            // Assume some logic to associate comment with a media
            $manager->persist($comment);
        }

        // Create subscriptions for users
        foreach ($users as $user) {
            $subscription = new Subscription();
            $subscription->setSubscriber($user);
            // Assume some logic to set subscription details
            $manager->persist($subscription);
        }

        // Persist all entities to the database
        $manager->flush();
    }
}
