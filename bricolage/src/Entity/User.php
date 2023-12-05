<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private $roleType;

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_HANDYMAN = 'HANDYMAN';
    const ROLE_CUSTOMER = 'CUSTOMER';

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $numStreet = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $postalCode = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Dispute::class)]
    private Collection $disputes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\ManyToMany(targetEntity: Message::class, mappedBy: 'user')]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Project::class)]
    private Collection $projects;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Blog::class)]
    private Collection $blogs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class)]
    private Collection $comments;

    public function __construct()
    {
        $this->disputes = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->roleType = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        $roleType = $this->roleType;

        // Ajouter des rôles supplémentaires
        $roleType[] = self::ROLE_ADMIN;
        $roleType[] = self::ROLE_HANDYMAN;
        $roleType[] = self::ROLE_CUSTOMER;

        return array_unique($roleType);
    }

    public function setRoleType(string $roleType): static
    {
        $this->roleType = $roleType;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getNumStreet(): ?string
    {
        return $this->numStreet;
    }

    public function setNumStreet(string $numStreet): static
    {
        $this->numStreet = $numStreet;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getFullName() {
        return $this->firstName . " " . $this->lastName;
    }

    public function getFullAddress() {
        return $this->numStreet . " " . $this->city . " " . $this->postalCode;
    }

    public static function getSender($username) {
        return $username;
    }

    public static function getRecipient($username) {
        return $username;
    }

    public function equals($obj) {
        if ($obj == null || get_class($obj) != get_class($this)) {
            return false;
        }
        if ($obj === $this) {
            return true;
        }

        $other = $obj;
        return $this->id === $other->id;
    }

    public function hashCode() {
        return hash('sha256', $this->id);
    }

    public function __toString() {
        return "User[id=" . $this->id . "]";
    }

    /**
     * @return Collection<int, Dispute>
     */
    public function getDisputes(): Collection
    {
        return $this->disputes;
    }

    public function addDispute(Dispute $dispute): static
    {
        if (!$this->disputes->contains($dispute)) {
            $this->disputes->add($dispute);
            $dispute->setUser($this);
        }

        return $this;
    }

    public function removeDispute(Dispute $dispute): static
    {
        if ($this->disputes->removeElement($dispute)) {
            // set the owning side to null (unless already changed)
            if ($dispute->getUser() === $this) {
                $dispute->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->addUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            $message->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setUser($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getUser() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): static
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs->add($blog);
            $blog->setUser($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): static
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getUser() === $this) {
                $blog->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function eraseCredentials(): void
    {
        // Supprimez les données sensibles de l'utilisateur. 
        // Cette méthode est appelée après que les informations de l'utilisateur ont été utilisées pour s'authentifier.
        // Dans la plupart des cas, vous n'avez pas besoin de faire quelque chose ici.
    }

    public function getUserIdentifier(): string
    {
        // Retourne l'identifiant unique de l'utilisateur.
        // À partir de Symfony 5.3, cette méthode remplace getUsername().
        return $this->username;
    }
}
