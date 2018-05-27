<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\AddPostType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PostController extends Controller
{
    /**
     * @Route("/user/add_post")
     * @Method("POST")
     * @param $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function addPostAction(Request $request, EntityManagerInterface $em)
    {
        $post = new Post();
        $form = $this->createForm(AddPostType::class, $post);
        $form->handleRequest($request);
        $post
            ->setUser($this->getUser())
            ->setPublishDate(new \DateTime());
        $em->persist($post);
        $em->flush();
        return $this->redirectToRoute("user_profile", ['id' => $this->getUser()->getId()]);
    }

    /**
     * @Route("/like/{post_id}")
     * @Method("POST")
     * @param int $post_id
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function likePostAction($post_id, EntityManagerInterface $em)
    {
        $current_user = $this->getUser();
        $post = $em
            ->getRepository(Post::class)
            ->findOneBy(['id' => $post_id]);
        $post->addLiker($current_user);
        dump($post);
        $em->flush();
        return new JsonResponse(['message' => 'Liked!']);
    }

    /**
     * @Route("/unlike/{post_id}")
     * @Method("DELETE")
     * @param int $post_id
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function unlikePostAction($post_id, EntityManagerInterface $em)
    {
        $current_user = $this->getUser();
        $post = $em
            ->getRepository(Post::class)
            ->findOneBy(['id' => $post_id]);
        $post->removeLiker($current_user);
        dump($post);
        $em->flush();
        return new JsonResponse(['message' => 'Unliked!']);
    }

    /**
     * @Route("/post/add_comment/{post_id}")
     * @Method("POST")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param $post_id
     * @return JsonResponse
     */
    public function addCommentAction($post_id, Request $request, EntityManagerInterface $em)
    {
        $comment = new Comment();
        $post = $em->getRepository(Post::class)->findOneBy(['id' => $post_id]);
        $comment
            ->setPublishDate(new \DateTime())
            ->setUser($this->getUser())
            ->setComment($request->request->get('comment'))
            ->setPost($post);
        $em->persist($comment);
        $em->flush();
        return new JsonResponse([], 200);
    }
}