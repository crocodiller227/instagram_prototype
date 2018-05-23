<?php


namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity
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
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $avatar;

    /**
     * @Vich\UploadableField(mapping="avatar_file", fileNameProperty="avatar")
     *
     * @var File
     */
    private $avatarFile;


    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
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
        $this->posts[] = $post;
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
        $this->following->remove($user);
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


}
