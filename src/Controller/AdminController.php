<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin")]
class AdminController extends AbstractController
{
    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $repository): Response
    {
        return $this->render('admin/index.html.twig', [
            "users"=>$repository->findAll()
        ]);
    }


    #[Route("/promote/{id}",name: "app_user_promote")]
    #[Route("/demote/{id}",name: "app_user_demote")]
    public function promoteDemote(Request $request, User $user,UserRepository $repository):Response{

        $promote = true;
        if ($request->get("_route")== "app_user_demote"){
            $promote= false;}

        if ($promote){
            $user->setRoles(["ROLE_ADMIN"]);
        }else{
            $user->setRoles([]);
        }

        $repository->save($user,true);

        return $this->redirectToRoute("app_admin_users");

    }



    #[Route("/showUserFavorites/{id}",name: "app_admin_showuserfavorites")]
    public function showUserFavorites(User $user):Response{

        return $this->render("admin/userFavorites.html.twig",[
           "citations"=>$user->getQuotes()
        ]);
    }


    #[Route("/remove/{id},",name: "app_admin_adminremove")]
    public function adminRemove(Quote $quote, EntityManagerInterface $manager):Response{

        $manager->remove($quote);
        $manager->flush();


        return $this->redirectToRoute("app_admin_showuserfavorites",[
            "id"=>$quote->getFavoriteOf()->getId()
        ]);
    }


}
