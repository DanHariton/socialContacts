<?php

namespace App\Controller;

use App\Entity\Meeting;
use App\Form\MeetingAddType;
use App\Repository\MeetingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeetingController extends AbstractController
{
    /**
     * @Route("/meetings", name="meetings_list")
     * @param MeetingRepository $meetingRepository
     * @return Response
     */
    public function meetingList(MeetingRepository $meetingRepository)
    {
        $meetings = $meetingRepository->findAll();

        return $this->render('meetings/meeting_list.html.twig', [
            'meetings' => $meetings
        ]);
    }

    /**
     * @Route("/add-meeting", name="meetings_create_meeting")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function createMeeting(Request $request, EntityManagerInterface $em)
    {
        $meeting = new Meeting();

        $form = $this->createForm(MeetingAddType::class, $meeting)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Meeting $meeting */
            $meeting = $form->getData();

            $location = $meeting->getLocation();
            $em->persist($location);

            $em->persist($meeting);
            $em->flush();

            $this->addFlash('success', 'Setkani bylo úspěšně přidáno');
            return $this->redirectToRoute('meetings_list');
        }

        return $this->render('meetings/add_meeting.html.twig', [
            'form' => $form->createView()
        ]);
    }
}