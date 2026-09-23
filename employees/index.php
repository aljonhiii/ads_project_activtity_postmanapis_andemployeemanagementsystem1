<?php
include_once __DIR__ . '/../api/database.php';
include_once __DIR__ . '/../class/DbTest.php';

// Create database connection
$database = new Database();
$conn = $database->getConnection();

// Initialize DbTest class
$test = new DbTest($conn);
$connectionStatus = $test->checkConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Information System</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="navbar">
            <button id="menu-toggle" class="menu-toggle">&#9776;</button>
            <div class="logo">Management Information System</div>
            <ul class="menu">
                <li><a href="#">Departments</a></li>
                <li><a href="#" class="active">Employees</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="#">Orders</a></li>
            </ul>
        </div>
    </div>

    <!-- Status Container Below the Header -->
    <div class="status-container">
        Database Connection Status:
        <span class="status <?= ($connectionStatus['status'] === 'success') ? 'success' : 'error' ?>">
            <?= htmlspecialchars($connectionStatus['message']) ?>
        </span>
    </div>

    <!-- Employee Table -->
    <div class="container">
        <div class="page-header">
            <div class="page-title">Employee List</div>
            <button class="add-btn" onclick="openAddEmployeeModal()">Add Employee</button>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter">
            <section>
                <input type="text" id="searchBox" placeholder="Search by Name..." onkeyup="filterEmployees()">
                <select id="filterSex" onchange="filterEmployees()">
                    <option value="">Filter by Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <select id="filterJobTitle" onchange="filterEmployees()">
                    <option value="">Select Job Title</option>
                    <option value="Project Manager">Project Manager</option>
                    <option value="Business Analyst">Business Analyst</option>
                    <option value="Fullstack Software Engineer">Fullstack Software Engineer</option>
                    <option value="Front End Developer">Front End Developer</option>
                    <option value="Back End Developer">Back End Developer</option>
                    <option value="Quality Assurance Engineer">Quality Assurance Engineer</option>
                    <option value="Software Engineer">Software Engineer</option>
                    <option value="HR Manager">HR Manager</option>
                    <option value="Sales Representative">Sales Representative</option>
                    <option value="Marketing Specialist">Marketing Specialist</option>
                    <option value="Customer Support">Customer Support</option>
                </select>
            </section>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>M.I.</th>
                        <th>Last Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Sex</th>
                        <th>Job Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    <!-- Employees will be loaded dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div id="addEmployeeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddEmployeeModal()">&times;</span>
            <h3>Enter Employee Details</h3>
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" id="first_name" placeholder="First Name">
                </div>
                <div class="form-group">
                    <input type="email" id="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="text" id="middle_initial" placeholder="M.I.">
                </div>
                <div class="form-group">
                    <select id="sex">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" id="last_name" placeholder="Last Name">
                </div>
                <div class="form-group">
                    <select id="job_title">
                        <option value="">Select Job Title</option>
                        <option value="Project Manager">Project Manager</option>
                        <option value="Business Analyst">Business Analyst</option>
                        <option value="Fullstack Software Engineer">Fullstack Software Engineer</option>
                        <option value="Front End Developer">Front End Developer</option>
                        <option value="Back End Developer">Back End Developer</option>
                        <option value="Quality Assurance Engineer">Quality Assurance Engineer</option>
                        <option value="Software Engineer">Software Engineer</option>
                        <option value="HR Manager">HR Manager</option>
                        <option value="Sales Representative">Sales Representative</option>
                        <option value="Marketing Specialist">Marketing Specialist</option>
                        <option value="Customer Support">Customer Support</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <input type="text" id="mobile_number" placeholder="Mobile Number">
                </div>
            </div>
            <button class="submit-btn" onclick="addEmployee()">Save Employee Details</button>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Update Employee</h3>
            <input type="hidden" id="editId">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" id="editFirstName" placeholder="First Name">
                </div>
                <div class="form-group">
                    <input type="email" id="editEmail" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="text" id="editMiddleInitial" placeholder="M.I.">
                </div>
                <div class="form-group">
                    <select id="editSex">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" id="editLastName" placeholder="Last Name">
                </div>
                <div class="form-group">
                    <select id="editJobTitle">
                        <option value="">Select Job Title</option>
                        <option value="Project Manager">Project Manager</option>
                        <option value="Business Analyst">Business Analyst</option>
                        <option value="Fullstack Software Engineer">Fullstack Software Engineer</option>
                        <option value="Front End Developer">Front End Developer</option>
                        <option value="Back End Developer">Back End Developer</option>
                        <option value="Quality Assurance Engineer">Quality Assurance Engineer</option>
                        <option value="Software Engineer">Software Engineer</option>
                        <option value="HR Manager">HR Manager</option>
                        <option value="Sales Representative">Sales Representative</option>
                        <option value="Marketing Specialist">Marketing Specialist</option>
                        <option value="Customer Support">Customer Support</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <input type="text" id="editMobileNumber" placeholder="Mobile Number">
                </div>
            </div>
            <button class="submit-btn" onclick="updateEmployee()">Save Changes</button>
        </div>
    </div>

    <script src="../javascript/functions.js"></script>
    <script src="employee.js"></script>
</body>
</html>
