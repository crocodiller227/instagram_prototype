<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @Vich\Uploadable
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publish_date", type="datetime")
     */
    private $publish_date;

    /**
     *
     * @Vich\UploadableField(mapping="user_photos", fileNameProperty="avatar")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", cascade={"persist", "remove"})
     */
    private $comments;

    /**
     *
     * @param File|UploadedFile $image
     *
     * @return Post
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function __toString()
    {
        return $this->title ?: '';
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set publishDate
     *
     * @param \DateTime $publishDate
     *
     * @return Post
     */
    public function setPublishDate($publishDate)
    {
        $this->publish_date = $publishDate;

        return $this;
    }

    /**
     * Get publishDate
     *
     * @return \DateTime
     */
    public function getPublishDate()
    {
        return $this->publish_date;
    }


    /**
     * @param mixed $user
     * @return Post
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Comment $comment
     * @return Post
     */
    public function setComments($comment)
    {
        $this->comments[] = $comment;
        return $this;
    }

    static function cmp_obj($a, $b)
    {
        if ($a->getPublishDate() == $b->getPublishDate()) {
            return 0;
        }
        return ($a->getPublishDate() > $b->getPublishDate()) ? +1 : -1;
    }
}

