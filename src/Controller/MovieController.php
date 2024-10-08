<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Serie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    // LIST ALL MOVIES
    #[Route('/movies', name: 'movie_list')]
    public function listMovies(EntityManagerInterface $em): Response
    {
        $movies = $em->getRepository(Movie::class)->findAll();

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    // SHOW A SINGLE MOVIE
    #[Route('/movies/{id}', name: 'movie_show')]
    public function showMovie(Movie $movie): Response
    {
        return $this->render('detail.html.twig', [
            'movie' => $movie,
        ]);
    }

    // LIST ALL SERIES
    #[Route('/series', name: 'serie_list')]
    public function listSeries(EntityManagerInterface $em): Response
    {
        $series = $em->getRepository(Serie::class)->findAll();

        return $this->render('detail_serie.html.twig', [
            'series' => $series,
        ]);
    }

    // SHOW A SINGLE SERIE
    #[Route('/series/{id}', name: 'serie_show')]
    public function showSerie(Serie $serie): Response
    {
        return $this->render('serie/detail.html.twig', [
            'serie' => $serie,
        ]);
    }
}
