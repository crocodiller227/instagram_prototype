<?php


namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="fos_user")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="following")
     **/
    protected $followers;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="followers")
     * @ORM\JoinTable(name="followers",
     * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="following_user_id", referencedColumnName="id")}
     * )
     */
    protected $following;

    /**
     * @var Post[]
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $posts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    protected $avatar;

    /**
     * @Vich\UploadableField(mapping="avatar_file", fileNameProperty="avatar")
     *
     * @var File
     */
    protected $avatarFile;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="likers")
     */
    private $likedPosts;


    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->likedPosts = new ArrayCollection();
    }

    /**
     *
     * @param File|UploadedFile $image
     *
     * @return User
     */
    public function setAvatarFile(File $image = null)
    {
        $this->avatarFile = $image;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    public function __toString()
    {
        return $this->username ?: '';
    }


    /**
     * @param Post $post
     * @return User
     */
    public function addPost($post)
    {
        $this->posts->add($post);
        return $this;
    }

    /**
     * @param Post $post
     * @return User
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);
        return $this;
    }

    /**
     * @return Post[]
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param User $user
     * @return User
     */
    public function follow(User $user)
    {
        $this->following[] = $user;
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function unfollow(User $user)
    {
        $this->following->removeElement($user);
        return $this;
    }

    /**
     * @return User[]|ArrayCollection
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @return User[]|ArrayCollection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @return int
     */
    public function getFollowersAmount()
    {
        return $this->followers->count();
    }

    /**
     * @return int
     */
    public function getFollowingAmount()
    {
        return $this->following->count();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function checkFollowing(User $user)
    {
        return $this->following->contains($user);
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikedPosts()
    {
        return $this->likedPosts;
    }

    /**
     * @param Post $post
     * @return User
     */
    public function addLikedPost(Post $post)
    {
        $this->likedPosts->add($post);
        return $this;
    }

    /**
     * @param Post $post
     * @return User
     */
    public function removeLikedPost(Post $post)
    {
        $this->likedPosts->removeElement($post);
        return $this;
    }


}
