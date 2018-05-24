<?php


namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Method("GET")
     * @Route("/my_profile")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myProfileAction(){
        $posts = $this->getUser()->getPosts();
        return $this->render('@App/user/my_profile.html.twig', [
            'posts' => $posts
        ]);
    }
}