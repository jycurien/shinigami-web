<?php


namespace App\Handler\User;


use App\Dto\UserEditDto;
use App\Entity\Address;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Service\User\UserImageUploadHelper;
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
     * @var UserImageUploadHelper
     */
    private $imageUploadHelper;

    public function __construct(EntityManagerInterface $entityManager, UserImageUploadHelper $imageUploadHelper)
    {
        $this->entityManager = $entityManager;
        $this->imageUploadHelper = $imageUploadHelper;
    }

    public function handle(UserEditDto $userEditDto, User $user)
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
        if (null !== $userEditDto->image) {
            $fileName = $this->imageUploadHelper->uploadImage($userEditDto->image, $user);
            $user->setImage($fileName);
        }

        $this->entityManager->flush();
    }
}