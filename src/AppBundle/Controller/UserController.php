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
     * @Route("/user/{id}", name="user_profile")
     * @param $id
     * @return Response
     */
    public function showUserProfileAction($id, EntityManagerInterface $em)
    {
        $form = '';
        $currentUser = $this->getUser();
        if ($currentUser->getId() == $id) {
            $form = $this->createForm(AddPostType::class, new Post())->createView();
            $user = $currentUser;
        } else {
            $user = $em
                ->getRepository(User::class)
                ->findOneBy(['id' => $id]);
        }
        return $this->render('@App/user/user_profile.html.twig', [
            'user' => $user,
            'form' => $form,
            'posts' => $user->getPosts()
        ]);
    }


    /**
     * @Route("/follow/{user_id}")
     * @param $user_id
     * @Method("POST")
     * @return JsonResponse
     */
    public function followUserAction($user_id, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $following_user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['id' => $user_id]);
        $user->follow($following_user);
        $em->flush();
        return new JsonResponse(['message'=> 'You have successfully follow to the user'], 200);
    }

    /**
     * @Route("/unfollow/{user_id}")
     * @Method("DELETE")
     * @param $user_id
     * @return JsonResponse
     */
    public function unfollowUserAction($user_id, EntityManagerInterface $em){
        $user = $this->getUser();
        $unfollowing_user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['id' => $user_id]);
        $user->unfollow($unfollowing_user);
        $em->flush();
        return new JsonResponse(['message' => 'You have successfully unfollow to the user'], 200);
    }



    /**
     * @Route("/search_user/{username}")
     * @Method("POST")
     * @param string $username
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function searchUserAction($username, EntityManagerInterface $em)
    {
        $user = $em
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);
        if ($user) {
            return new JsonResponse(['user_url' => $this->generateUrl(
                    "homepage", [], UrlGeneratorInterface::ABSOLUTE_URL) . "user/{$user->getId()}"
            ]);
        }
        return new JsonResponse(['message' => 'User not found'], 404);
    }

    /**
     * @Route("/users/", name="show_all_users")
     */
    public function showAllUserAction(EntityManagerInterface $em)
    {
        $users = $em
            ->getRepository(User::class)
            ->findAll();
        return $this->render('@App/user/all_users.html.twig', [
            'users' => $users
        ]);
    }
}