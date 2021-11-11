<?php


namespace App\Controller;


use App\Dto\EmployeeDto;
use App\Entity\User;
use App\Form\NewEmployeeType;
use App\Form\UpdateEmployeeType;
use App\Handler\User\EmployeeHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContractController extends AbstractController
{
    /**
     * @Route({
     *     "en": "/modifier-employe/{id<\d+>}",
     *     "fr": "/update-employee/{id<\d+>}"
     *      },
     *     name="contract_update_employee_admin",
     *     methods={"GET", "POST"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param User $user
     * @param Request $request
     * @param EmployeeHandler $employeeHandler
     * @return Response
     */
    public function updateEmployee(User $user, Request $request, EmployeeHandler $employeeHandler): Response
    {
        $employeeDto = new EmployeeDto($user);
        $userPic = null !== $user->getImage() ? '/picture/users/' . $this->getUser()->getImage() : '/picture/user.jpg';
        $options = [
            'picture_url' => $this->getParameter('web_url') . $userPic
        ];
        $form = $this->createForm(UpdateEmployeeType::class, $employeeDto, $options)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employeeHandler->handle($employeeDto, $user);
            return $this->redirectToRoute('user_employees_admin');
        }

        return $this->renderForm('admin/employee/update_employee.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

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
        $employeeDto = new EmployeeDto();

        $form = $this->createForm(NewEmployeeType::class, $employeeDto)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employeeHandler->handle($employeeDto);
            return $this->redirectToRoute('user_employees_admin');
        }

        return $this->renderForm('admin/employee/new_employee.html.twig', [
            'form' => $form
        ]);
    }
}