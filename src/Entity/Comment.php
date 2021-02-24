<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ApiResource (
 *     itemOperations={"get"},
 *     collectionOperations={"get"}
 * )
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\User", inversedBy="comments" )
     * @ORM\JoinColumn()
     */
    private $author;


    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\BlogPost", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $blogpost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor():User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }


    public function getBlogpost():BlogPost
    {
        return $this->blogpost;
    }

    public function setBlogpost(BlogPost $blogpost): self
    {
        $this->blogpost = $blogpost;
        return $this;
    }


}
