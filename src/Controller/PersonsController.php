<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\ContactType;
use App\Entity\Location;
use App\Entity\Person;
use App\Entity\Relation;
use App\Form\PersonCreateType;
use App\Form\RelationAddType;
use App\Repository\ContactTypeRepository;
use App\Repository\PersonRepository;
use App\Repository\RelationRepository;
use App\Repository\RelationTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * Class PersonsController
 * @package App\Controller
 */
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
        $relations = $relationRepository->findById($person->getId());

        return $this->render('persons/about_person.html.twig', [
            'person' => $person,
            'relations' => $relations
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
     * @param Request $request
     * @param ContactTypeRepository $contactTypeRepository
     * @param RelationTypeRepository $relationTypeRepository
     * @param PersonRepository $personRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function editPersonInfo(Person $person, EntityManagerInterface $em, Request $request,
                                   ContactTypeRepository $contactTypeRepository, RelationTypeRepository $relationTypeRepository,
                                   PersonRepository $personRepository)
    {
        /** @var ContactType[]|null $contactTypes */
        $contactTypes = $contactTypeRepository->findAll();

        /** @var Person[]|null $persons */
        $persons = $personRepository->findAll();

        /** @var Relation[]|null $relationTypes */
        $relationTypes = $relationTypeRepository->findAll();

        $relation = new Relation();

        $personContacts = $person->getContacts();

        foreach ($contactTypes as $contactType) {
            $contact = new Contact();
            $contact->setContactType($contactType);
            $flag = false;
            foreach ($personContacts as $personContact) {
                if ($contactType->getId() == $personContact->getContactType()->getId()) {
                    $flag = true;
                    break;
                }
            }
            if (!$flag) {
                $person->addContact($contact);
            }
        }

        foreach ($persons as $key => $value) {
            if ($value->getId() == $person->getId()) {
                unset($persons[$key]);
            }
        }

        $form = $this->createForm(PersonCreateType::class, $person)
            ->handleRequest($request);

        $formRelation = $this->createForm(RelationAddType::class, $relation, ['relationTypes' => $relationTypes, 'persons' => $persons])
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

            $this->addFlash('success', 'Změny osobních údajů byly úspěšně uloženy');
            return $this->redirect($request->headers->get('referer'));
        }

        if ($formRelation->isSubmitted() && $formRelation->isValid()) {

            $contacts = $person->getContacts();
            foreach ($contacts as $contact) {
                if (!is_null($contact->getContact())) {
                    $em->persist($contact);
                } else {
                    $person->removeContact($contact);
                }
            }

            $relation->setPerson1($person);
            $relation->setPerson2($personRepository->findOneById($formRelation->get('person2')->getData()));
            $relation->setRelationType($relationTypeRepository->findOneById($formRelation->get('relation')->getData()));

            $em->persist($relation);
            $em->flush();

            $this->addFlash('success', 'Změny byly úspěšně uloženy');
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('persons/edit_person.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
            'formRelation' => $formRelation->createView()
        ]);
    }
}