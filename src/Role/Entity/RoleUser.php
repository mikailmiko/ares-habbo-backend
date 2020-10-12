<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Entity;

use Ares\Framework\Entity\Entity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class RoleUser
 *
 * @package Ares\Role\Entity
 *
 * @ORM\Table(name="ares_role_user",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idx_user_role_unique", columns={"user_id", "role_id"})},
 *      indexes={@ORM\Index(name="fk_user_role_role", columns={"role_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class RoleUser extends Entity
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private int $userId;

    /**
     * @ORM\Column(name="role_id", type="integer", nullable=false)
     */
    private int $roleId;

    /**
     * @ORM\ManyToOne(targetEntity="Ares\Role\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private Role $role;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private DateTime $createdAt;

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
     * @return RoleUser
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param   int  $userId
     *
     * @return RoleUser
     */
    public function setUserId($userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->roleId;
    }

    /**
     * @param   int  $roleId
     *
     * @return RoleUser
     */
    public function setRoleId($roleId): self
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @param   Role  $role
     *
     * @return RoleUser
     */
    public function setRole($role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param   DateTime  $createdAt
     *
     * @return RoleUser
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     *
     * @throws \Exception
     */
    public function prePersist(): void
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [];
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
