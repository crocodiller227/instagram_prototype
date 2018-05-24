<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Post;
use AppBundle\Form\AddPostType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PostController extends Controller
{
    /**
     * @Route("/add_post")
     * @Method("POST")
     * @param $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function addPostAction(Request $request, EntityManagerInterface $em){
        $post = new Post();
        $form = $this->createForm(AddPostType::class, $post);
        $form->handleRequest($request);
        $post
            ->setUser($this->getUser())
            ->setPublishDate(new \DateTime());
        $em->persist($post);
        $em->flush();
        return $this->redirectToRoute("/user/{$this->getUser()->getId()}");

    }
}