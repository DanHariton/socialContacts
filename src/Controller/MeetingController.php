<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Meeting;
use App\Entity\Person;
use App\Form\MeetingAddType;
use App\Form\ParticipantMeetingAddType;
use App\Form\ParticipantMeetingRemoveType;
use App\Repository\MeetingRepository;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    /**
     * @Route("/meeting/delete/{id}", name="meetings_delete_meeting")
     * @param Meeting $meeting
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteMeeting(Meeting $meeting, EntityManagerInterface $em, Request $request)
    {
        /** @var Location $location */
        $location = $meeting->getLocation();
        $em->remove($location);

        $persons = $meeting->getPersons();
        foreach ($persons as $person) {
            $meeting->removePerson($person);
        }

        $em->remove($meeting);
        $em->flush();

        $this->addFlash('success', 'Setkani bylo úspěšně odstraněno');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/meeting/detail/{id}", name="meetings_detail_meeting")
     * @param Meeting $meeting
     * @return Response
     */
    public function detailMeeting(Meeting $meeting)
    {
        return $this->render('meetings/about_meeting.html.twig', [
            'meeting' => $meeting
        ]);
    }

    /**
     * @Route("/meeting/edit/{id}", name="meetings_edit_meeting")
     * @param Meeting $meeting
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param PersonRepository $personRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function editMeeting(Meeting $meeting, EntityManagerInterface $em, Request $request, PersonRepository $personRepository)
    {
        /** @var Person[]|null $persons */
        $persons = $personRepository->findAll();

        /** @var Person[]|null $participants */
        $participants = $meeting->getPersons();

        foreach ($persons as $key => $person) {
            $flag = false;
            foreach ($participants as $participant) {
                if ($person->getId() == $participant->getId()) {
                    $flag = true;
                    break;
                }
            }
            if ($flag) {
                unset($persons[$key]);
            }
        }

        $form = $this->createForm(MeetingAddType::class, $meeting)
            ->handleRequest($request);

        $formParticipation = $this->createForm(ParticipantMeetingAddType::class, null, array('persons' => $persons))
            ->handleRequest($request);

        $formParticipationRemove = $this->createForm(ParticipantMeetingRemoveType::class, null, array('participants' => $participants))
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Meeting $meeting */
            $meeting = $form->getData();

            $location = $meeting->getLocation();
            $em->persist($location);

            $em->persist($meeting);
            $em->flush();

            $this->addFlash('success', 'Setkani bylo úspěšně editovane');
            return $this->redirect($request->headers->get('referer'));
        }

        if ($formParticipation->isSubmitted() && $formParticipation->isValid()) {
            $participant = $formParticipation->get('person')->getData();

            /** @var Person $participant */
            $participant = $personRepository->findOneById($participant);
            $meeting->addPerson($participant);
            $participant->addMeeting($meeting);

            $em->persist($meeting);
            $em->persist($participant);
            $em->flush();

            $this->addFlash('success', 'Ucastnik byl uspesne pridan');
            return $this->redirect($request->headers->get('referer'));
        }

        if ($formParticipationRemove->isSubmitted() && $formParticipationRemove->isValid()) {
            $participantRemove = $formParticipationRemove->get('person')->getData();

            /** @var Person $participant */
            $participant = $personRepository->findOneById($participantRemove);
            $meeting->removePerson($participant);
            $participant->removeMeeting($meeting);

            $em->persist($meeting);
            $em->persist($participant);
            $em->flush();

            $this->addFlash('success', 'Ucastnik byl uspesne odstranen');
            return $this->redirect($request->headers->get('referer'));
        }


        return $this->render('meetings/edit_meeting.html.twig', [
            'meeting' => $meeting,
            'form' => $form->createView(),
            'formParticipation' => $formParticipation->createView(),
            'formParticipationRemove' => $formParticipationRemove->createView()
        ]);
    }
}