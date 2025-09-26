<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document Repository System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .card { margin-top: 20px; }
  </style>
</head>
<body>

<div class="container mt-4">
  <h2 class="text-center mb-4">📂 Document Repository System</h2>

  <!-- Upload Form -->
  <div class="card">
    <div class="card-header bg-primary text-white">Upload Document</div>
    <div class="card-body">
      <form id="uploadForm" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="docId" class="form-label">Document ID</label>
          <input type="text" class="form-control" name="docId" required>
        </div>
        <div class="mb-3">
          <label for="docType" class="form-label">Document Type</label>
          <select name="docType" class="form-select" required>
            <option value="">Select Type</option>
            <option>Syllabus</option>
            <option>Attendance Sheets</option>
            <option>Instructional Materials</option>
            <option>TOS</option>
            <option>TQS</option>
            <option>Grade Sheets</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="docFile" class="form-label">Upload File</label>
          <input type="file" class="form-control" name="docFile" required>
        </div>
        <button type="submit" class="btn btn-success">Upload</button>
      </form>
    </div>
  </div>

  <!-- Sorting + Search -->
  <div class="mt-4 d-flex justify-content-between align-items-center">
    <h4>📑 Document List</h4>
    <div class="d-flex align-items-center">
      <label class="form-label me-2">Sort By:</label>
      <select id="sortOption" class="form-select me-3 w-auto">
        <option value="id">Database ID</option>
        <option value="doc_id">Document ID</option>
        <option value="doc_type">Document Type</option>
      </select>

      <input type="text" id="searchBox" class="form-control" placeholder="Search..." style="width: 200px;">
    </div>
  </div>


  <!-- Document Table -->
  <table class="table table-striped table-hover mt-3">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Document ID</th>
        <th>Type</th>
        <th>File</th>
        <th>Uploaded</th>
      </tr>
    </thead>
    <tbody id="docTableBody"></tbody>
  </table>
</div>

<!-- Bootstrap + jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function fetchDocuments(sort = "id", search = "") {
  $.get("fetch.php", { sort: sort, search: search }, function(data) {
    let tbody = $("#docTableBody");
    tbody.empty();
    data.forEach(doc => {
      tbody.append(`
        <tr>
          <td>${doc.id}</td>
          <td>${doc.doc_id}</td>
          <td>${doc.doc_type}</td>
          <td><a href="${doc.file_name}" target="_blank">View File</a></td>
          <td>${doc.uploaded_at}</td>
        </tr>
      `);
    });
  }, "json");
}

// Handle upload
$("#uploadForm").submit(function(e) {
  e.preventDefault();
  let formData = new FormData(this);
  $.ajax({
    url: "upload.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      if (response === "success") {
        fetchDocuments($("#sortOption").val(), $("#searchBox").val());
        $("#uploadForm")[0].reset();
      } else {
        alert(response);
      }
    }
  });
});

// Sorting
$("#sortOption").change(function() {
  fetchDocuments($(this).val(), $("#searchBox").val());
});

// Searching
$("#searchBox").on("keyup", function() {
  fetchDocuments($("#sortOption").val(), $(this).val());
});

// Initial load
fetchDocuments();

</script>
</body>
</html>
