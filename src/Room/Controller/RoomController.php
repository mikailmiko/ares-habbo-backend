<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Controller;

use Ares\Framework\Controller\BaseController;

use Ares\Room\Entity\Room;
use Ares\Room\Exception\RoomException;
use Ares\Room\Repository\RoomRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RoomController
 *
 * @package Ares\Room\Controller
 */
class RoomController extends BaseController
{
    /**
     * @var RoomRepository
     */
    private RoomRepository $roomRepository;

    /**
     * RoomController constructor.
     *
     * @param RoomRepository $roomRepository
     */
    public function __construct(
        RoomRepository $roomRepository
    ) {
        $this->roomRepository = $roomRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param          $id
     *
     * @return Response
     * @throws RoomException
     */
    public function room(Request $request, Response $response, $id): Response
    {
        /** @var Room $room */
        $room = $this->roomRepository->get((int)$id);

        if(is_null($room)) {
            throw new RoomException(__('No specific Room found'));
        }

        return $this->respond(
            $response,
            response()->setData($room->getArrayCopy())
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws RoomException
     */
    public function list(Request $request, Response $response): Response
    {
        /** @var Room $rooms */
        $rooms = $this->roomRepository->getList([]);

        if(is_null($rooms)) {
            throw new RoomException(__('No Rooms were found'), 404);
        }

        $list = [];
        foreach ($rooms as $room) {
            $list[] = $room->getArrayCopy();
        }

        return $this->respond(
            $response,
            response()->setData($list)
        );
    }
}
