<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use AppBundle\Form\AddPostType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends Controller
{
    /**
     * @Method("GET")
     * @Route("/user/{id}")
     * @param $id
     * @return Response
     */
    public function showUserProfileAction($id)
    {
        $form = '';
        if($this->getUser()->getId() == $id){
            $form = $this->createForm(AddPostType::class, new Post())->createView();
        }
        return $this->render('@App/user/user_profile.html.twig', [
            'user' => $this->getUser(),
            'form' => $form,
            'posts' => $this->getUser()->getPosts()
        ]);
    }


    /**
     * @Route("/follow/{user_id}")
     * @param $user_id
     * @Method("POST")
     * @return JsonResponse
     */
    public function followUser($user_id)
    {
        $user = $this->getUser();
        $following_user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['id' => $user_id]);
        $user->follow($following_user);
        return new JsonResponse([], 200);
    }

    /**
     * @Route("/search_user/{username}")
     * @Method("POST")
     * @param string $username
     * @return Response
     */
    public function searchUserAction($username, EntityManagerInterface $em)
    {
        $user = $em
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);
        if ($user) {
          return new JsonResponse(['user_url' => $this->generateUrl(
              "homepage", [],UrlGeneratorInterface::ABSOLUTE_URL) . "user/{$user->getId()}"
          ]);
        }
        return new JsonResponse(['message' => 'User not found'], 404);
    }
}