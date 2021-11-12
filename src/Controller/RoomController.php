<?php


namespace App\Controller;


use App\Entity\Center;
use App\Repository\RoomRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends  AbstractController
{
    /**
     * @Route("/admin/ajax/get-rooms-by-center/{centerId<\d+>}", name="room_get_rooms_by_center_admin", methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @param int $centerId
     * @param RoomRepository $roomRepository
     * @return JsonResponse
     */
    public function getRoomsByCenter(int $centerId, RoomRepository $roomRepository): JsonResponse
    {
        $rooms = $roomRepository->findBy(['center'=> $centerId]);

        $roomList = array_map(function($room) {
            return [
                'id' => $room->getId(),
                'name' => $room->getName(),
                'capacity' => $room->getCapacity(),
            ];
        }, $rooms);

        return $this->json($roomList);
    }
}