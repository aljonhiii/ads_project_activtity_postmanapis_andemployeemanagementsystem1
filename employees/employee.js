document.addEventListener("DOMContentLoaded", () => {
    loadEmployees();

    // Ensure modals are hidden on load
    const addModal = document.getElementById("addEmployeeModal");
    const editModal = document.getElementById("editModal");
    if (addModal) addModal.style.display = "none";
    if (editModal) editModal.style.display = "none";
});

// Fetch all employees and display them in the table
function loadEmployees() {
    fetch("../api/employee_api.php")
        .then(response => response.json())
        .then(data => {
            const employeeTableBody = document.getElementById("employeeTableBody");
            employeeTableBody.innerHTML = "";

            if (data.status === "success") {
                const employees = data.employee || data.employees || [];
                employees.forEach(emp => {
                    const tr = document.createElement("tr");

                    const id = emp.id || '';
                    const firstName = emp.first_name || '';
                    const middleInitial = emp.middle_initial || '';
                    const lastName = emp.last_name || '';
                    const mobile = emp.mobile_number || '';
                    const email = emp.email || '';
                    const sex = emp.sex || '';
                    const jobTitle = emp.job_title || '';

                    tr.innerHTML = `
                        <td>${id}</td>
                        <td>${sanitizeHTML(firstName)}</td>
                        <td>${sanitizeHTML(middleInitial)}</td>
                        <td>${sanitizeHTML(lastName)}</td>
                        <td>${sanitizeHTML(mobile)}</td>
                        <td>${sanitizeHTML(email)}</td>
                        <td>${sanitizeHTML(sex)}</td>
                        <td>${sanitizeHTML(jobTitle)}</td>
                        <td class="actions">
                            <button class="edit-btn" onclick="openEditModal(${id}, '${escapeJS(firstName)}', '${escapeJS(middleInitial)}', '${escapeJS(lastName)}', '${escapeJS(email)}', '${escapeJS(mobile)}', '${escapeJS(sex)}', '${escapeJS(jobTitle)}')">Edit</button>
                            <button class="delete-btn" onclick="deleteEmployee(${id})">Delete</button>
                        </td>
                    `;
                    employeeTableBody.appendChild(tr);
                });
            } else {
                employeeTableBody.innerHTML = `<tr><td colspan="9" style="text-align:center;">No employees found.</td></tr>`;
            }
        })
        .catch(error => {
            console.error("Error fetching employees:", error);
        });
}

// Add a new employee
function addEmployee() {
    const firstName = document.getElementById("first_name").value.trim();
    const mi = document.getElementById("middle_initial").value.trim();
    const lastName = document.getElementById("last_name").value.trim();
    const email = document.getElementById("email").value.trim();
    const mobile = document.getElementById("mobile_number").value.trim();
    const sex = document.getElementById("sex").value.trim();
    const jobTitle = document.getElementById("job_title").value.trim();

    if (!firstName || !lastName || !email || !mobile || !sex || !jobTitle) {
        alert("All fields except Middle Initial are required!");
        return;
    }

    fetch("../api/employee_api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            first_name: firstName,
            middle_initial: mi,
            last_name: lastName,
            email: email,
            mobile_number: mobile,
            sex: sex,
            job_title: jobTitle
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            document.getElementById("first_name").value = "";
            document.getElementById("middle_initial").value = "";
            document.getElementById("last_name").value = "";
            document.getElementById("email").value = "";
            document.getElementById("mobile_number").value = "";
            document.getElementById("sex").value = "";
            document.getElementById("job_title").value = "";
            closeAddEmployeeModal();
            loadEmployees();
        }
    })
    .catch(error => {
        console.error("Error adding employee:", error);
    });
}

// Open Edit Employee Modal
function openEditModal(id, firstName, middleInitial, lastName, email, mobile, sex, jobTitle) {
    document.getElementById("editId").value = id;
    document.getElementById("editFirstName").value = firstName;
    document.getElementById("editMiddleInitial").value = middleInitial === "null" ? "" : middleInitial;
    document.getElementById("editLastName").value = lastName;
    document.getElementById("editEmail").value = email;
    document.getElementById("editMobileNumber").value = mobile;
    document.getElementById("editSex").value = sex;
    document.getElementById("editJobTitle").value = jobTitle;

    const modal = document.getElementById("editModal");
    if (modal) modal.style.display = "flex";
}

// Close Edit Modal
function closeModal() {
    const modal = document.getElementById("editModal");
    if (modal) modal.style.display = "none";
}

// Open Add Employee Modal
function openAddEmployeeModal() {
    const modal = document.getElementById("addEmployeeModal");
    if (modal) modal.style.display = "flex";
}

// Close Add Employee Modal
function closeAddEmployeeModal() {
    const modal = document.getElementById("addEmployeeModal");
    if (modal) modal.style.display = "none";
}

// Update Employee details
function updateEmployee() {
    const id = document.getElementById("editId").value;
    const firstName = document.getElementById("editFirstName").value.trim();
    const mi = document.getElementById("editMiddleInitial").value.trim();
    const lastName = document.getElementById("editLastName").value.trim();
    const email = document.getElementById("editEmail").value.trim();
    const mobile = document.getElementById("editMobileNumber").value.trim();
    const sex = document.getElementById("editSex").value.trim();
    const jobTitle = document.getElementById("editJobTitle").value.trim();

    if (!id || !firstName || !lastName || !email || !mobile || !sex || !jobTitle) {
        alert("All fields except Middle Initial are required!");
        return;
    }

    fetch("../api/employee_api.php", {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            id: id,
            first_name: firstName,
            middle_initial: mi,
            last_name: lastName,
            email: email,
            mobile_number: mobile,
            sex: sex,
            job_title: jobTitle
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            closeModal();
            loadEmployees();
        }
    })
    .catch(error => {
        console.error("Error updating employee:", error);
    });
}

// Delete Employee
function deleteEmployee(id) {
    if (confirm("Are you sure you want to delete this employee?")) {
        fetch(`../api/employee_api.php?id=${id}`, {
            method: "DELETE"
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                loadEmployees();
            }
        })
        .catch(error => {
            console.error("Error deleting employee:", error);
        });
    }
}

// Filter employees table
function filterEmployees() {
    const searchText = document.getElementById("searchBox").value.toLowerCase();
    const filterSex = document.getElementById("filterSex").value.toLowerCase();
    const filterJobTitle = document.getElementById("filterJobTitle").value.toLowerCase();

    const rows = document.querySelectorAll("#employeeTableBody tr");

    rows.forEach(row => {
        if (row.cells.length < 8) return;

        const firstName = row.cells[1].textContent.toLowerCase();
        const lastName = row.cells[3].textContent.toLowerCase();
        const sex = row.cells[6].textContent.toLowerCase();
        const jobTitle = row.cells[7].textContent.toLowerCase();

        const matchesSearch = firstName.includes(searchText) || lastName.includes(searchText) || jobTitle.includes(searchText);
        const matchesSex = filterSex === "" || sex === filterSex;
        const matchesJobTitle = filterJobTitle === "" || jobTitle === filterJobTitle;

        if (matchesSearch && matchesSex && matchesJobTitle) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

// Helper to escape strings for JS inline calls
function escapeJS(str) {
    if (!str) return '';
    return str.toString().replace(/'/g, "\\'").replace(/"/g, '\\"');
}
