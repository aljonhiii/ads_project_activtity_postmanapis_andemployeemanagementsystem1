<?php
class Employee {
    private $conn;
    private $table = "employees";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all employees
    public function getAllEmployees() {
        $query = "SELECT id, first_name, last_name, middle_initial, mobile_number, email, sex, job_title FROM " . $this->table . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch employee by ID
    public function getEmployeeById($id) {
        $query = "SELECT id, first_name, last_name, middle_initial, mobile_number, email, sex, job_title FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new employee
    public function addEmployee($first_name, $last_name, $middle_initial, $mobile_number, $email, $sex, $job_title) {
        $query = "INSERT INTO " . $this->table . " (first_name, last_name, middle_initial, mobile_number, email, sex, job_title) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$first_name, $last_name, $middle_initial, $mobile_number, $email, $sex, $job_title]);
    }

    // Update employee details
    public function updateEmployee($id, $first_name, $last_name, $middle_initial, $mobile_number, $email, $sex, $job_title) {
        $query = "UPDATE " . $this->table . " SET first_name = ?, last_name = ?, middle_initial = ?, mobile_number = ?, email = ?, sex = ?, job_title = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$first_name, $last_name, $middle_initial, $mobile_number, $email, $sex, $job_title, $id]);
    }

    // Delete employee
    public function deleteEmployee($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
