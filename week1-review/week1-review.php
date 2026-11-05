<?php

declare(strict_types=1);

class EmployeeNotFoundException extends RuntimeException
{
}

$employeesJson = '[
    {
        "id": 101,
        "name": "John",
        "department": "IT",
        "salary": 50000,
        "active": true
    },
    {
        "id": 102,
        "name": "David",
        "department": "HR",
        "salary": 35000,
        "active": false
    },
    {
        "id": 103,
        "name": "Alex",
        "department": "IT",
        "salary": 60000,
        "active": true
    },
    {
        "id": 104,
        "name": "Robert",
        "department": "Finance",
        "salary": 45000,
        "active": true
    }
]';


$employees = json_decode(
    $employeesJson,
    true,
    512,
    JSON_THROW_ON_ERROR
);

print_r($employees);

function findEmployee(array $employees, int $id): array
{
    foreach ($employees as $employee) {

        if ($employee["id"] === $id) {
            return $employee;
        }
    }

    throw new EmployeeNotFoundException(
        "Employee not found"
    );
}

function getActiveEmployees(array $employees): array
{
    $activeEmployees = array_filter(
        $employees,
        function ($employee) {
            return $employee["active"] === true;
        }
    );

    return array_values($activeEmployees);
}

function calculateAnnualSalary(int $monthlySalary): int
{
    return $monthlySalary * 12;
}

function calculateTotalPayroll(array $employees): int
{
    $total = 0;

    foreach ($employees as $employee) {
        $total += $employee["salary"];
    }

    return $total;
}


try {

    // Search employee by ID
    $employee = findEmployee($employees, 101);

    // Get active employees
    $activeEmployees = getActiveEmployees($employees);

    // Calculate annual salary
    $annualSalary = calculateAnnualSalary(
        $employee["salary"]
    );

    // Calculate active employees' payroll
    $activePayroll = calculateTotalPayroll(
        $activeEmployees
    );

    // Prepare response
    $response = [
        "success" => true,
        "message" => "Employee information retrieved",
        "data" => [
            "employee" => $employee,
            "annual_salary" => $annualSalary,
            "active_employee_count" => count($activeEmployees),
            "active_monthly_payroll" => $activePayroll
        ]
    ];

    echo json_encode(
        $response,
        JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
    );

} catch (EmployeeNotFoundException | JsonException $e) {

    $response = [
        "success" => false,
        "message" => $e->getMessage()
    ];

    echo json_encode($response, JSON_PRETTY_PRINT);

}

?>