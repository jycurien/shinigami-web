<?php


namespace App\Handler\User;


use App\Dto\UserEditDto;
use App\Entity\Address;
use App\Entity\User;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileEditHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SluggerInterface
     */
    private $slugger;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger, Filesystem $filesystem, FlashBagInterface $flashBag)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        $this->filesystem = $filesystem;
        $this->flashBag = $flashBag;
    }

    public function handle(UserEditDto $userEditDto, User $user, string $userImageDir)
    {
        $user->setFirstName($userEditDto->firstName);
        $user->setLastName($userEditDto->lastName);
        $user->setPhoneNumber($userEditDto->phoneNumber);
        $user->setBirthDate($userEditDto->birthDate);
        $userAddress = $user->getAddress() ?? new Address();
        $userAddress->setAddress($userEditDto->address->address);
        $userAddress->setZipCode($userEditDto->address->zipCode);
        $userAddress->setCity($userEditDto->address->city);
        $user->setAddress($userAddress);

        // IMAGE UPLOAD
        /** @var UploadedFile $image */
        $image = $userEditDto->image;
        if (null !== $image) {
            $fileName = $this->slugger->slug($user->getUsername())
                . '_'.uniqid().'.' . $image->guessExtension();
            try {
                $image->move(
                    $userImageDir,
                    $fileName
                );
                if (null !== $user->getImage()) {
                    $this->filesystem->remove($userImageDir.'/'.$user->getImage());
                }
                $user->setImage($fileName);
            } catch (FileException $e) {
                $error = $e->getMessage();
                $this->flashBag->add('error', $error);
            }
        }

        $this->entityManager->flush();
    }
}