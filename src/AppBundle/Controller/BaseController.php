<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\AddCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;

class BaseController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction(EntityManagerInterface $em)
    {
        $posts = [];
        $user = $this->getUser();
        if($user){
            foreach ($user->getFollowing() as $following){
                foreach ($following->getPosts() as $post){
                    array_push($posts, $post);
                }
            }
            usort($posts, array(Post::class, "cmp_obj"));
        }
        return $this->render('@App/posts.html.twig', [
            'posts' => $posts
        ]);
    }
}
