<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>jQuery Table Pagination</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="style.css" /> -->

  <style>
    body {
  font-family: Arial, sans-serif;
  padding: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

th, td {
  border: 1px solid #ccc;
  padding: 8px;
}

th {
  cursor: pointer;
  background: #f4f4f4;
}

#pagination button {
  margin: 5px;
  padding: 5px 10px;
  cursor: pointer;
}

#pagination button.active {
  background: black;
  color: white;
}

/* /// ----- */

button {
  padding: 6px 10px;
  cursor: pointer;
}

.modal {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.4);
}

.modal-content {
  background: #fff;
  padding: 20px;
  width: 300px;
  margin: 10% auto;
  border-radius: 4px;
}

.modal-content input {
  width: 100%;
  margin-bottom: 10px;
  padding: 6px;
}
  </style>
</head>
<body>

  <!-- <input class="mb-3" type="text" id="search" placeholder="Search..." /> -->

  <div class="mb-3">
    <input type="search" class="mb-3 form-control" id="search" aria-describedby="emailHelp" placeholder="Search...">
  </div>


  <button id="addNew" class="btn btn-primary mb-3">
    + Add New
  </button>

  <!-- Modal -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <h3 id="modalTitle"></h3>
      <input type="hidden" id="recordId" />
      <input type="text" id="nameInput" placeholder="Name" />
      <input type="email" id="emailInput" placeholder="Email" />

      <button id="saveRecord">Save</button>
      <button id="closeModal">Cancel</button>
    </div>
  </div>


  <table id="dataTable">
    <thead>
      <tr>
        <th data-key="id">ID</th>
        <th data-key="name">audio</th>
        <th data-key="email">tag name</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <div id="pagination"></div>




  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- <script src="script.js"></script> -->
   <script>
    let allData = [];
let filteredData = [];
let currentPage = 1;
let rowsPerPage = 10;
let sortKey = "";
let sortOrder = "asc";

/* ------------------ FETCH DATA ------------------ */
function fetchData() {
  // Simulated backend response
  allData = Array.from({ length: 12000 }, (_, i) => ({
    id: i + 1,
    // name: "User " + (i + 1),
    // email: "user" + (i + 1) + "@example.com"
    name: "" + (i + 1) + "_audio",
    email: "" + (i + 1) + "_tag_name"
  }));

  filteredData = [...allData];
  renderTable();
  renderPagination();
}

/* ------------------ RENDER TABLE ------------------ */
/*function renderTable() {
  const start = (currentPage - 1) * rowsPerPage;
  const end = start + rowsPerPage;
  const pageData = filteredData.slice(start, end);

  const tbody = $("#dataTable tbody");
  tbody.empty();

  pageData.forEach(row => {
    tbody.append(`
      <tr>
        <td>${row.id}</td>
        <td>${row.name}</td>
        <td>${row.email}</td>
      </tr>
    `);
  });
}*/

function renderTable() {
  const start = (currentPage - 1) * rowsPerPage;
  const pageData = filteredData.slice(start, start + rowsPerPage);

  const tbody = $("#dataTable tbody");
  tbody.empty();

  pageData.forEach(row => {
    tbody.append(`
      <tr>
        <td>${row.id}</td>
        <td>${row.name}</td>
        <td>${row.email}</td>
        <td>
          <button class="btn btn-sm btn-warning edit" data-id="${row.id}">
            Edit
          </button>
          <button class="btn btn-sm btn-danger delete" data-id="${row.id}">
            Delete
          </button>
        </td>
      </tr>
    `);
  });
}


// --- Add New ---

$("#addNew").click(function () {
  $("#modalTitle").text("Add New Record");
  $("#recordId").val("");
  $("#nameInput").val("");
  $("#emailInput").val("");
  $("#modal").show();
});

// --- Edit---

$(document).on("click", ".edit", function () {
  const id = Number($(this).data("id"));
  const record = allData.find(r => r.id === id);

  $("#modalTitle").text("Edit Record");
  $("#recordId").val(record.id);
  $("#nameInput").val(record.name);
  $("#emailInput").val(record.email);
  $("#modal").show();
});


// --- Save ---

$("#saveRecord").click(function () {
  const id = $("#recordId").val();
  const name = $("#nameInput").val();
  const email = $("#emailInput").val();

  if (!name || !email) return alert("All fields required");

  if (id) {
    // UPDATE
    const record = allData.find(r => r.id == id);
    record.name = name;
    record.email = email;
  } else {
    // CREATE
    const newId = allData.length
      ? Math.max(...allData.map(r => r.id)) + 1
      : 1;

    allData.push({ id: newId, name, email });
  }

  filteredData = [...allData];
  $("#modal").hide();
  renderTable();
  renderPagination();
});


// --- Delete 

$(document).on("click", ".delete", function () {
  if (!confirm("Delete this record?")) return;

  const id = Number($(this).data("id"));
  allData = allData.filter(r => r.id !== id);
  filteredData = filteredData.filter(r => r.id !== id);

  renderTable();
  renderPagination();
});



// Close modal 
$("#closeModal").click(() => $("#modal").hide());





/* ------------------ PAGINATION ------------------ */
/*
function renderPagination() {
  const pageCount = Math.ceil(filteredData.length / rowsPerPage);
  const pagination = $("#pagination");
  pagination.empty();

  for (let i = 1; i <= pageCount; i++) {
    pagination.append(`
      <button class="${i === currentPage ? "active" : ""}" data-page="${i}">
        ${i}
      </button>
    `);
  }
}
*/
function renderPagination() {
  const totalPages = Math.ceil(filteredData.length / rowsPerPage);
  const pagination = $("#pagination");
  pagination.empty();

  const visiblePages = 5;
  let startPage = Math.max(1, currentPage - Math.floor(visiblePages / 2));
  let endPage = startPage + visiblePages - 1;

  if (endPage > totalPages) {
    endPage = totalPages;
    startPage = Math.max(1, endPage - visiblePages + 1);
  }

  // Prev
  if (currentPage > 1) {
    pagination.append(`<button data-page="${currentPage - 1}">Prev</button>`);
  }

  // First page shortcut
  if (startPage > 1) {
    pagination.append(`<button data-page="1">1</button>`);
    if (startPage > 2) pagination.append(`<span>...</span>`);
  }

  // Page window
  for (let i = startPage; i <= endPage; i++) {
    pagination.append(`
      <button class="${i === currentPage ? "active" : ""}" data-page="${i}">
        ${i}
      </button>
    `);
  }

  // Last page shortcut
  if (endPage < totalPages) {
    if (endPage < totalPages - 1) pagination.append(`<span>...</span>`);
    pagination.append(`<button data-page="${totalPages}">${totalPages}</button>`);
  }

  // Next
  if (currentPage < totalPages) {
    pagination.append(`<button data-page="${currentPage + 1}">Next</button>`);
  }
}


/* ------------------ EVENTS ------------------ */

// Pagination click
$(document).on("click", "#pagination button", function () {
  currentPage = Number($(this).data("page"));
  renderTable();
  renderPagination();
});

// Sorting
$("th").on("click", function () {
  const key = $(this).data("key");

  sortOrder = sortKey === key && sortOrder === "asc" ? "desc" : "asc";
  sortKey = key;

  filteredData.sort((a, b) => {
    if (a[key] < b[key]) return sortOrder === "asc" ? -1 : 1;
    if (a[key] > b[key]) return sortOrder === "asc" ? 1 : -1;
    return 0;
  });

  renderTable();
});

// Filtering
$("#search").on("keyup", function () {
  const value = $(this).val().toLowerCase();

  filteredData = allData.filter(item =>
    item.name.toLowerCase().includes(value) ||
    item.email.toLowerCase().includes(value)
  );

  currentPage = 1;
  renderTable();
  renderPagination();
});

/* ------------------ INIT ------------------ */
$(document).ready(fetchData);
   </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>