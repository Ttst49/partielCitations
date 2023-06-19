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

        $quote = $repository->findOneByCitation($value);


        if (!$quote) {
            $quote = new Quote();
            $quote->setContent($value);
            $quote->setCharacter($character);
        }else{
            $this->getUser()->addQuote($quote);
        }

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


    #[Route("/showBestQuotes",name: "app_citation_showbestquotes")]
    public function showBestQuotes(QuoteRepository $repository):Response{

        $quotes = $repository->findAll();
        $higher = 0;
        $second = 0;
        $third = 0;
        foreach ($quotes as $quote){
            foreach ($quote->getFavoriteOf() as $user){
                $actualNumber = $quote->getFavoriteOf()->count();
                $actualQuote = $quote->getContent();
                switch ($actualNumber){
                    case ($actualNumber > $higher): $higher = $actualNumber; $higherQuote = $actualQuote; break;
                    case ($actualNumber <= $higher && $actualNumber >= $second): $second = $actualNumber; $secondQuote = $actualQuote; break;
                    case ($actualNumber <= $second && $actualNumber >= $third): $third = $actualNumber; $thirdQuote = $actualQuote;
                }
            }
        }

        $bestQuotes = [$higher=>$higherQuote,$second=>$secondQuote,$third=>$thirdQuote];
        return $this->render("citation/bestQuotes.html.twig",[
            "bestQuotes"=>$bestQuotes
        ]);
    }

}
