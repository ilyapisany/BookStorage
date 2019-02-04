<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *      name="ISBN_unique",
 *      columns={"ISBN"}),
 *     @ORM\UniqueConstraint(
 *      name="NameYearUnique",
 *      columns={"name", "pulicatedYear"})
 * })
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name = '';

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $publicated_year = 0;

    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $ISBN = '';

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $page_num = 0;

    /**
     * @var PersistentCollection
     * @ORM\ManyToMany(targetEntity="Author", inversedBy="writed_books")
     */
    private $authors;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPublicatedYear(): \DateTime
    {
        return $this->publicated_year;
    }

    /**
     * @param int $publicated_year
     */
    public function setPublicatedYear($publicated_year): void
    {
        /** @var \DateTime $publicated_year */
        $this->publicated_year = $publicated_year;
    }

    /**
     * @return string
     */
    public function getISBN(): string
    {
        return $this->ISBN;
    }

    /**
     * @param string $ISBN
     */
    public function setISBN(string $ISBN): void
    {
        $this->ISBN = $ISBN;
    }

    /**
     * @return int
     */
    public function getPageNum(): int
    {
        return $this->page_num;
    }

    /**
     * @param int $page_num
     */
    public function setPageNum(int $page_num): void
    {
        $this->page_num = $page_num;
    }

    /**
     * @return array
     */
    public function getAuthors(): array
    {
        return $this->authors->toArray();
    }

    /**
     * @param array $authors
     */
    public function setAuthors(array $authors): void
    {
        foreach ($this->authors->toArray() as $author) {
            if(!array_search($author, $authors)) {
                $this->writed_books->removeElement($author);
                $author->removeBook($this);
            }
        }
        foreach ($authors as $author) {
            if(!$this->writed_books->contains($author)) {
                $this->addAuthor($author);
                $author->addBook($this);
            }
        }
    }

    /**
     * @param Author $author
     */
    public function addAuthor(Author $author) {
        $this->authors[] = $author;
    }

    /**
     * @param Author $author
     */
    public function removeAuthor(Author $author) {
        $this->authors->removeElement($author);
    }

    public function year() {
        return $this->publicated_year->format('Y');
    }

    public function __construct()
    {
        $this->publicated_year = new \DateTime();
    }
}
