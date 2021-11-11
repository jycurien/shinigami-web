window.addEventListener('DOMContentLoaded', () => {
    const pseudoInput = document.querySelector('#new_employee_username');
    const firstNameInput = document.querySelector('#new_employee_firstName');
    const lastNameInput = document.querySelector('#new_employee_lastName');
    const concatInputs = () => {
        pseudoInput.value = firstNameInput.value + lastNameInput.value;
    }
    firstNameInput.addEventListener('keyup', concatInputs);
    lastNameInput.addEventListener('keyup', concatInputs);
});