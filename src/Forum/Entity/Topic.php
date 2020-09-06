<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Entity;

use Ares\Framework\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Topic
 *
 * @package Ares\Topic\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="ares_forum_topics", uniqueConstraints={@ORM\UniqueConstraint(name="title", columns={"title"})}))
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\HasLifecycleCallbacks
 */
class Topic extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $slug;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $description;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private \DateTime $created_at;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private \DateTime $updated_at;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param   int  $id
     *
     * @return Topic
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param   string  $title
     *
     * @return Topic
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return Topic
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param   string  $description
     *
     * @return Topic
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @param   \DateTime  $created_at
     *
     * @return Topic
     */
    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param   \DateTime  $updated_at
     *
     * @return Topic
     */
    public function setUpdatedAt(\DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Gets triggered only on insert
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime("now");
    }

    /**
     * Returns a copy of the current Entity safely
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'slug' => $this->getId() . '-' . $this->getSlug(),
            'description' => $this->getDescription(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        ];
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize(get_object_vars($this));
    }

    /**
     * @param   string  $data
     */
    public function unserialize($data): void
    {
        $values = unserialize($data);

        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}