<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Repository\QuoteRepository;
use App\Repository\UserRepository;
use App\service\KaamelottCitations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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



    #[Route("/user/addFavorite/",name: "app_citation_addfavorite")]
    public function addFavorite(QuoteRepository $repository, EntityManagerInterface $manager, Request $request):Response{

        $quoteContent =$request->get("quote");
        $character = $request->get("character");


        $quote = $repository->findOneByCitation($quoteContent);


        if ($counter = null){
            $counter = 0;
        }

        if (!$quote) {
            $quote = new Quote();
            $quote->setContent($quoteContent);
            $quote->setCharacter($character);
        }

        $quote->setCounter($counter+1);
        $quote->addFavoriteOf($this->getUser());
        $manager->persist($quote);
        $manager->flush();


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
        $quote->setCounter($quote->getCounter()-1);
        $manager->flush();


        return $this->redirectToRoute("app_citation_indexfavorites");
    }


    #[Route("/showBestQuotes",name: "app_citation_showbestquotes")]
    public function showBestQuotes(QuoteRepository $repository):Response{

        $quotes = $repository->findBy(array(),["counter"=>"DESC"],3,null);
        return $this->render("citation/bestQuotes.html.twig",[
                "bestQuotes" => $quotes
        ]);
    }

    #[Route("/api/login/connected")]
    public function getFirstInfo(UserRepository $repository):Response{

        $user = $this->getUser();

        $infos = $repository->findByExampleField($user);


        return $this->json($infos,200,[],["groups"=>"api"]);
    }

}
