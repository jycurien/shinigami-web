<?php


namespace App\Service\User;


use App\Entity\User;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserImageUploadHelper
{
    /**
     * @var SluggerInterface
     */
    private $slugger;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var string
     */
    private $userImageDir;

    public function __construct(SluggerInterface $slugger, FlashBagInterface $flashBag, Filesystem $filesystem, string $userImageDir)
    {
        $this->slugger = $slugger;
        $this->flashBag = $flashBag;
        $this->filesystem = $filesystem;
        $this->userImageDir = $userImageDir;
    }

    public function uploadImage(UploadedFile $image, User $user): string
    {
        $fileName = $this->slugger->slug($user->getUsername())
            . '_'.uniqid().'.' . $image->guessExtension();
        try {
            $image->move(
                $this->userImageDir,
                $fileName
            );
        } catch (FileException $e) {
            $error = $e->getMessage();
            $this->flashBag->add('error', $error);
        }
        if (null !== $user->getImage()) {
            $this->filesystem->remove($this->userImageDir.'/'.$user->getImage());
        }

        return $fileName;
    }
}