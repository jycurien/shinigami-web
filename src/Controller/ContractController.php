<?php


namespace App\Controller;


use App\Dto\EmployeeDto;
use App\Form\NewEmployeeType;
use App\Handler\User\EmployeeHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContractController extends AbstractController
{
    // TODO
//    /**
//     * @Route({
//     *     "en": "/modifier-employe/{id<\d+>}",
//     *     "fr": "/update-employee/{id<\d+>}"
//     *      },
//     *     name="contract_update_employee_admin",
//     *     methods={"GET", "POST"})
//     * @Security("user.isValidateContract() and has_role('ROLE_ADMIN')")
//     * @param User $user
//     * @param Request $request
//     * @param EmployeeHandler $employeeHandler
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function updateEmployee(User $user, Request $request, EmployeeHandler $employeeHandler)
//    {
//        $event = new GetResponseUserEvent($user, $request);
//        $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);
//
//        $form = $this->createForm(UpdateEmployeeType::class, $user)->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            if(null !== $employeeHandler->handle($user, false)) {
//                $user = $employeeHandler->handle($user, false);
//                $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, new Response('OK')));
//                return $this->redirectToRoute('user_employees_admin');
//            }
//        }
//
//        return $this->render('admin/employee/update_employee.html.twig', [
//            'user' => $user,
//            'form' => $form->createView()
//        ]);
//    }

    /**
     * @Route({
     *     "en": "/new-employee",
     *     "fr": "/ajouter-un-employe"
     *      },
     *     name="contract_new_employee_admin",
     *     methods={"GET", "POST"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param EmployeeHandler $employeeHandler
     * @return Response
     */
    public function newEmployee(Request $request, EmployeeHandler $employeeHandler): Response
    {
        $employee = new EmployeeDto();

        $form = $this->createForm(NewEmployeeType::class, $employee)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $employeeHandler->handle($employee);
            return $this->redirectToRoute('user_employees_admin');
        }

        return $this->renderForm('admin/employee/new_employee.html.twig', [
            'form' => $form
        ]);
    }
}