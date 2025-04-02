<?php
set_include_path($_SERVER['DOCUMENT_ROOT'].'/util');
include_once("auth.php");
include_once("conn.php");
?>
<style>

.table td, .table th {
    word-wrap: break-word;
    word-break: break-word;
    white-space: normal; 
}

.table td {
    max-width: 300px; 
}
</style>
<div class="content-wrapper">
  <div class="page-header">
    <h3 class="page-title"> Reviews </h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Reviews</a></li>
      </ol>
    </nav>
  </div>
  <div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Reviews table</h4>
          <p class="card-description">The user's feedback or suggestions regarding the product.</p>
          <table class="table table-bordered">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Username</th>
                      <th>Message</th>
                      <th>Created At</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  try {
                      $stmt = $conn->prepare("SELECT id, username, message, created_at FROM messages");
                      $stmt->execute();
                      $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

                      foreach ($messages as $row) {
                          echo "<tr>";
                          echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                          echo "</tr>";
                      }
                  } catch (PDOException $e) {
                      echo "<tr><td colspan='4'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                  }
                  ?>
              </tbody>
          </table>
        </div>
      </div>
    </div>
    
  </div>
</div>
