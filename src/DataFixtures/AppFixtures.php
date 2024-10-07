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
use App\Entity\PlaylistSubscription;
use App\Entity\Season;
use App\Entity\Serie;
use App\Entity\Subscription;
use App\Entity\SubscriptionHistory;
use App\Entity\User;
use App\Entity\WatchHistory;
use App\Enum\MediaTypeEnum;
use App\Enum\StatusCommentEnum;
use App\Enum\UserAccountStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Categories
        $categoryEntities = [];
        $categories = [
            ['Action', 'action'],
            ['Aventure', 'aventure'],
            ['Comédie', 'comédie'],
            ['Drame', 'drame'],
            ['Science-fiction', 'sci-Fi']
        ];

        foreach ($categories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData[0]);
            $category->setLabel($categoryData[1]);
            $manager->persist($category);
            $categoryEntities[] = $category;
        }

        // Languages
        $languageEntities = [];
        $languages = [
            ['French', 'FR'],
            ['English', 'EN'],
            ['Spanish', 'ES'],
            ['Russian', 'RU'],
            ['Chinese', 'CN']
        ];

        foreach ($languages as $languageData) {
            $language = new Language();
            $language->setName($languageData[0]);
            $language->setCode($languageData[1]);
            $manager->persist($language);
            $languageEntities[] = $language;
        }

        // Movies
        $movieTab = [];
        for ($i = 0; $i < 5; $i++) {
            $movie = new Movie();
            $movie->setTitle("Film " . $i);
            $movie->setShortDescription("Description du film " . $i);
            $movie->setLongDescription("Longue description du film " . $i);
            $movie->setCoverImage("film" . $i . ".png");
            $movie->setRealeaseDateAt(new \DateTime());
            $movie->setMediaType(MediaTypeEnum::MOVIE);
            $movie->addCategoryMedium($categoryEntities[$i % count($categoryEntities)]);
            $movie->addMediaLanguage($languageEntities[$i % count($languageEntities)]);
            $manager->persist($movie);
            $movieTab[] = $movie;
        }

        // Series, Seasons, and Episodes
        for ($i = 0; $i < 5; $i++) {
            $serie = new Serie();
            $serie->setTitle("Serie " . $i);
            $serie->setShortDescription("Description de la Serie " . $i);
            $serie->setLongDescription("Longue description de la Serie " . $i);
            $serie->setCoverImage("Serie" . $i . ".png");
            $serie->setRealeaseDateAt(new \DateTime());
            $serie->setMediaType(MediaTypeEnum::SERIE);
            $serie->addCategoryMedium($categoryEntities[$i % count($categoryEntities)]);
            $serie->addMediaLanguage($languageEntities[$i % count($languageEntities)]);
            $manager->persist($serie);

            for ($s = 0; $s < 3; $s++) {
                $season = new Season();
                $season->setSeasonNumber($s);
                $season->setSerie($serie);
                $manager->persist($season);

                for ($e = 0; $e < 10; $e++) {
                    $episode = new Episode();
                    $episode->setTitle("Episode " . $e);
                    $episode->setDuration(new \DateTimeImmutable('00:00:00'));
                    $episode->setReleaseDateAt(new \DateTimeImmutable());
                    $episode->setSeason($season);
                    $manager->persist($episode);
                }
            }
        }

        // Subscriptions
        $subscriptionsEntities = [];
        $subscriptions = [
            ['Simple subscription', 5, 1],
            ['Premium subscription', 10, 2],
            ['INSANE PREMIUM', 25, 3]
        ];

        foreach ($subscriptions as $subscriptionData) {
            $subscription = new Subscription();
            $subscription->setName($subscriptionData[0]);
            $subscription->setPrice($subscriptionData[1]);
            $subscription->setDurationInMonths($subscriptionData[2]);
            $manager->persist($subscription);
            $subscriptionsEntities[] = $subscription;
        }

        // Users and Subscriptions
        $userTab = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setUsername("User" . $i);
            $user->setEmail("user" . $i . "@example.com");

            $hashedPassword = $this->passwordHasher->hashPassword($user, "password" . $i);
            $user->setPassword($hashedPassword);

            $user->addCurrentSubscription($subscriptionsEntities[rand(0, 2)]);
            $user->setAccountStatus(UserAccountStatusEnum::ACTIVE);
            $manager->persist($user);
            $userTab[] = $user;

            $subHistory = new SubscriptionHistory();
            $subHistory->setSubscriber($user);
            $subHistory->setSubscription($subscriptionsEntities[rand(0, 2)]);
            $subHistory->setStartDateAt(new \DateTimeImmutable());
            $subHistory->setEndDateAt(new \DateTimeImmutable('+1 month'));
            $manager->persist($subHistory);
        }

        // Comments
        foreach ($userTab as $key => $user) {
            $comment = new Comment();
            $comment->setContributor($user);
            $comment->setMedia($movieTab[rand(0, count($movieTab) - 1)]);
            $comment->setContent("Commentaire du user " . $key);
            $comment->setStatus(StatusCommentEnum::POSTED);
            $manager->persist($comment);
        }

        // Watch History
        foreach ($userTab as $user) {
            $watchHistory = new WatchHistory();
            $watchHistory->setWatcher($user);
            $watchHistory->setMedia($movieTab[rand(0, count($movieTab) - 1)]);
            $watchHistory->setLastWatchedAt(new \DateTime());
            $watchHistory->setNumberOfViews(rand(1, 50));
            $manager->persist($watchHistory);
        }

        // Playlists
        $playlistTab = [];
        foreach ($userTab as $user) {
            $playlist = new Playlist();
            $playlist->setCreator($user);
            $playlist->setName("Playlist of " . $user->getUsername());
            $playlist->setCreatedAt(new \DateTimeImmutable());
            $playlist->setUpdatedAt(new \DateTime());
            $manager->persist($playlist);
            $playlistTab[] = $playlist;
        }

        // Playlist Subscriptions
        foreach ($userTab as $user) {
            $playlistSub = new PlaylistSubscription();
            $playlistSub->setCreator($userTab[rand(0, count($userTab) - 1)]);
            $playlistSub->setSubscriber($user);
            $playlistSub->setPlaylist($playlistTab[rand(0, count($playlistTab) - 1)]);
            $playlistSub->setSubscribedAt(new \DateTime());
            $manager->persist($playlistSub);
        }

        // Playlist Media
        foreach ($playlistTab as $playlist) {
            $playlistMedia = new PlaylistMedia();
            $playlistMedia->setMedia($movieTab[rand(0, count($movieTab) - 1)]);
            $playlistMedia->setPlaylist($playlist);
            $playlistMedia->setAddedAt(new \DateTime());
            $manager->persist($playlistMedia);
        }

        $manager->flush();
    }
}
