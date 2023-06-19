<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Repository\QuoteRepository;
use App\service\KaamelottCitations;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/")]
class CitationController extends AbstractController
{
    #[Route('', name: 'app_citation')]
    public function index(KaamelottCitations $kaamelottCitations): Response
    {

        $citation = $kaamelottCitations->fetchKaamelottInfo();


        return $this->render('citation/index.html.twig', [
            "citation"=>$citation
        ]);
    }



    #[Route("/user/addFavorite/{value}/{character}",name: "app_citation_addfavorite")]
    public function addFavorite(QuoteRepository $repository, EntityManagerInterface $manager, $value = null, $character = null):Response{

        $quote = new Quote();
        $quote->setContent($value);
        $quote->setCharacter($character);
        $quote->addFavoriteOf($this->getUser());

        $manager->persist($quote);
        $manager->flush();

        $this->addFlash(
            'notice',
            'La citation est vôtre!'
        );

        return $this->redirectToRoute("app_citation");
    }


    #[Route("/user/favoriteIndex",name: "app_citation_indexfavorites")]
    public function indexFavorite():Response{

        return $this->render("citation/favorites.html.twig",[
            "citations"=>$this->getUser()->getQuotes()
        ]);
    }


    #[Route("/user/remove/{id},",name: "app_citation_removefavorite")]
    public function removeFavorite(Quote $quote, EntityManagerInterface $manager):Response{

        $this->getUser()->removeQuote($quote);
        $manager->flush();


        return $this->redirectToRoute("app_citation_indexfavorites");
    }

}
