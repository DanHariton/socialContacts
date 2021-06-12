<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\ContactType;
use App\Entity\Location;
use App\Entity\Person;
use App\Form\PersonCreateType;
use App\Repository\ContactTypeRepository;
use App\Repository\PersonRepository;
use App\Repository\RelationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonsController extends AbstractController
{
    /**
     * @Route("/persons", name="persons_list_index")
     * @param PersonRepository $personRepository
     * @return Response
     */
    public function personsList(PersonRepository $personRepository)
    {
        $persons = $personRepository->findAll();

        return $this->render('persons/all_persons.html.twig', [
           'persons' => $persons
        ]);
    }

    /**
     * @Route("/add-person", name="persons_create_person")
     * @param Request $request
     * @param ContactTypeRepository $contactTypeRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function createPerson(Request $request, ContactTypeRepository $contactTypeRepository, EntityManagerInterface $em)
    {
        $person = new Person();

        /** @var ContactType[]|null $contactTypes */
        $contactTypes = $contactTypeRepository->findAll();

        foreach ($contactTypes as $contactType) {
            $contact = new Contact();
            $contact->setContactType($contactType);
            $person->addContact($contact);
        }

        $form = $this->createForm(PersonCreateType::class, $person)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Person $person */
            $person = $form->getData();

            $location = $person->getLocation();
            $em->persist($location);

            $contacts = $person->getContacts();
            foreach ($contacts as $contact) {
                if (!is_null($contact->getContact())) {
                    $em->persist($contact);
                } else {
                    $person->removeContact($contact);
                }
            }

            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'Osoba byla úspěšně přidána');
            return $this->redirectToRoute('persons_list_index');
        }

        return $this->render('persons/add_person.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/person/detail/{id}", name="persons_detail_person")
     * @param Person $person
     * @param RelationRepository $relationRepository
     * @return Response
     */
    public function detailPerson(Person $person, RelationRepository $relationRepository)
    {

        return $this->render('persons/about_person.html.twig', [
            'person' => $person
        ]);
    }

    /**
     * @Route("/person/delete/{id}", name="persons_delete_person")
     * @param Person $person
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return RedirectResponse
     */
    public function deletePerson(Person $person, EntityManagerInterface $em, Request $request)
    {
        /** @var Location $location */
        $location = $person->getLocation();
        $em->remove($location);

        /** @var Contact[]|null $contacts */
        $contacts = $person->getContacts();
        foreach ($contacts as $contact) {
            $em->remove($contact);
        }

        $em->remove($person);
        $em->flush();

        $this->addFlash('success', 'Osoba byla úspěšně odstraněna');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/person/edit/{id}", name="persons_edit_person")
     * @param Person $person
     * @param EntityManagerInterface $em
     * @param ContactTypeRepository $contactTypeRepository
     * @param Request $request
     */
    public function editPersonInfo(Person $person, EntityManagerInterface $em, Request $request,
                                   ContactTypeRepository $contactTypeRepository)
    {
        /** @var ContactType[]|null $contactTypes */
        $contactTypes = $contactTypeRepository->findAll();

        foreach ($contactTypes as $contactType) {
            $contact = new Contact();
            $contact->setContactType($contactType);
            $person->addContact($contact);
        }

        $form = $this->createForm(PersonCreateType::class, $person)
            ->handleRequest($request);


    }
}