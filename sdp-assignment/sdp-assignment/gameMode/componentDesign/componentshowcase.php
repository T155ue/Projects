<?php
require_once '../../Includes/dbConnection.php';
require_once '../../Includes/generalFunc.php';

session_start();
if (!is_login()) {
  echo "<script>
      alert('Please login first before viewing components...');
      window.location.href = '../../index.php';
  </script>";
  exit();
}
$username = $_SESSION['username'];


// Base query without any filters
$query = 'SELECT c.*, u.username, u.isCompany FROM component c JOIN user u ON c.PlayerID = u.userid';

// Handling search parameter
if (isset($_GET['search'])) {
  $searchresult = $_GET['search'];
  $query .= " WHERE c.component_name LIKE '%$searchresult%'";
}

// Handling sort parameter
if (isset($_GET['sort'])) {
  $sort = $_GET['sort'];

  // Adjusting query based on sort option
  switch ($sort) {
    case 'quality':
      $query .= " AND u.isCompany = '1'";
      break;
    case 'my_components':
      // Adjust query to select components created by the current user
      $query .= " AND u.username = '$username'";
      break;
    case 'default':
    default:
      // No additional filters needed for default sort
      break;
  }
}

if (isset($_POST['deletecomp'])) {
  $componentdelete = $_POST['deletecomp'];

  $deletequery = execSql("DELETE FROM component WHERE ComponentID = '$componentdelete'");

  if ($deletequery) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
}

// Execute the SQL query
$result = execSql($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tailwind CSS Page</title>
  <link rel="stylesheet" href="componentshowcase.css" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100">
  <!-- Header -->
  <header class="bg-blue-500 text-white py-4 flex justify-between items-center px-4">
    <h1 class="text-2xl font-bold">Open Source Components</h1>

    <!-- Right-aligned links -->
    <div class="flex items-center">
      <?php if (is_login()) { ?>
        <a href="index.php" class="bg-white text-blue-500 px-3 py-1 rounded-md text-sm">Make your own component</a>
      <?php } else { ?>
        <a href="../../login_signup/login.php" class="bg-white text-blue-500 px-3 py-1 rounded-md text-sm">Login</a>
      <?php } ?>

      <!-- Spacer -->
      <div class="ml-4"></div>

      <!-- Back Button -->
      <a href="../../index.php" class="bg-white text-blue-500 px-3 py-1 rounded-md text-sm">Back</a>
    </div>
  </header>
  <div class="flex items-center justify-between">
    <form action="" method="get" id="searchform" class="w-2/3 lg:w-3/4 xl:w-4/5 mt-3 mr-3 ml-3">
      <input type="text" id="searchInput" name="search" placeholder="Search component..." class="border p-2 mb-4 w-full">
    </form>
    <div class="flex items-center w-30">
      <form action="" method="get" id="sortform">
        <div class="flex items-center w-70 p-4">
          <label class="mr-4">Sort By:</label>
          <select name="sort" id="sortSelect" class="border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent flex-1 mr-2" onchange="submitForm()">
            <option value="default" <?php if (!isset($_GET['sort']) || $_GET['sort'] === 'default') echo 'selected'; ?>>Default</option>
            <option value="quality" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'quality') echo 'selected'; ?>>Sort by Quality</option>
            <option value="my_components" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'my_components') echo 'selected'; ?>>My Components</option>
          </select>
          <input type="hidden" name="sort" value="" id="sortinput">
        </div>
      </form>
    </div>
  </div>
  <div class="container mx-auto py-8 flex flex-wrap" id="itemsContainer">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
      // Check if component_type is not equal to 2
      if ($row['component_type'] != 2) {

        $isOwner = ($row['username'] === $username);
    ?>
        <div class="component-container p-4 rounded-md shadow-md bg-white m-2 h-80">
          <div class="component-card" onclick="toggledetails(<?php echo $row['ComponentID'] ?>)" id="<?php echo $row['ComponentID'] ?>">
            <div class="mb-4">
              <p class="text-gray-700 font-bold" id="showid_<?php echo $row['ComponentID']; ?>">Component ID: <?php echo $row['ComponentID']; ?></p>
              <p class="text-gray-700 font-bold" id="showname_<?php echo $row['ComponentID']; ?>">Name: <?php echo $row['component_name']; ?></p>
              <p class="text-gray-700 font-bold" id="showcompany_<?php echo $row['ComponentID']; ?>">Is Company: <?php echo ($row['isCompany'] == 1) ? 'Yes' : 'No'; ?></p>
              <p class="text-gray-700 font-bold" id="showuser_<?php echo $row['ComponentID']; ?>">Created by: <?php echo $row['username']; ?></p>
            </div>
            <form action="index.php" method="post" id="form_<?php echo $row['ComponentID'] ?>">
              <input type="hidden" name="cid" value="<?php echo $row['ComponentID'] ?>">
              <input type="hidden" name="uid" value="<?php echo $row['PlayerID'] ?>">
            </form>
            <form action="" method="post" id="delete_<?php echo $row['ComponentID'] ?>">
              <input type="hidden" name="deletecomp" value=" <?php echo $row['ComponentID'] ?>">
            </form>
            <form action="reportcomponent.php" method="post" id="report_<?php echo $row['ComponentID'] ?>">
              <input type="hidden" name="reportcomp" value=" <?php echo $row['ComponentID'] ?>">
            </form>
            <input type="hidden" id="isowner_<?php echo $row['ComponentID']; ?>" value="<?php echo $isOwner ?>">
            <div class="code-container hidden">
              <div class="code-title text-gray-700 font-bold mb-2">HTML:</div>
              <pre class="code-content bg-gray-200 p-2 rounded-md overflow-auto"><code class="language-html" id="showhtml_<?php echo $row['ComponentID']; ?>"><?php echo htmlspecialchars($row['component_html'], ENT_QUOTES); ?></code></pre>
            </div>
            <div class="code-container hidden">
              <div class="code-title text-gray-700 font-bold mb-2">CSS:</div>
              <pre class="code-content bg-gray-200 p-2 rounded-md overflow-auto"><code class="language-css" id="showcss_<?php echo $row['ComponentID']; ?>"><?php echo htmlspecialchars($row['component_css'], ENT_QUOTES); ?></code></pre>
            </div>

            <div class="result-frame-container">
              <iframe id="resultframe_<?php echo $row['ComponentID']; ?>" class="result-frame" frameborder="0" srcdoc='
              <html>
                <head>
                  <style><?php echo htmlspecialchars($row['component_css'], ENT_QUOTES); ?></style> 
                </head>
                <body>
                  <?php echo htmlspecialchars($row['component_html'], ENT_QUOTES); ?> 
                </body>
              </html>
            '></iframe>
            </div>
          </div>
        </div>

    <?php

      }
    }
    ?>
    <div id="componentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
      <div class="modal-overlay fixed inset-0 bg-gray-900 opacity-50"></div>

      <div class="modal-container bg-white w-full max-w-3xl mx-auto rounded-lg shadow-lg z-50 overflow-hidden flex relative">
        <!-- Close button -->
        <button id="modalCloseBtn" class="text-gray-800 hover:text-gray-600 focus:outline-none absolute top-4 right-4">
          <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M6.293 6.293a.999.999 0 0 1 1.414 0L12 10.586l4.293-4.293a.999.999 0 1 1 1.414 1.414L13.414 12l4.293 4.293a.999.999 0 1 1-1.414 1.414L12 13.414l-4.293 4.293a.999.999 0 1 1-1.414-1.414L10.586 12 6.293 7.707a.999.999 0 0 1 0-1.414z" />
          </svg>
        </button>

        <!-- Left side: Component details -->
        <div class="w-1/2 p-6">
          <div class="pb-3">
            <p class="text-2xl font-bold text-gray-800">Component Details</p>
          </div>

          <div id="modalContent" class="text-gray-700">
            <!-- Content will be dynamically added here -->
          </div>
        </div>

        <!-- Right side: Result frame -->
        <div class="w-1/2 bg-gray-100 p-6">
          <div class="pb-3">
            <p class="text-2xl font-bold text-gray-800">Component Preview</p>
          </div>
          <div id="modalResultFrame" class="result-frame-container">
            <!-- Result frame content will be dynamically added here -->
          </div>
        </div>
      </div>
    </div>



    <script src="index.js"></script>

    <script>
      function submitForm() {
        var sortSelect = document.getElementById("sortSelect");
        var sortform = document.getElementById("sortform");
        var selectedValue = sortSelect.options[sortSelect.selectedIndex].value;
        console.log(selectedValue);
        var sortinput = document.getElementById("sortinput");
        sortinput.value = selectedValue;
        sortform.submit();

      }

      function toggledetails(id) {
        var showId = document.getElementById('showid_' + id).textContent;
        var showName = document.getElementById('showname_' + id).textContent;
        var showCompany = document.getElementById('showcompany_' + id).textContent;
        var showUser = document.getElementById('showuser_' + id).textContent;
        var showHtml = document.getElementById('showhtml_' + id).textContent;
        var showCss = document.getElementById('showcss_' + id).textContent;
        var resultFrame = document.getElementById('resultframe_' + id);
        var isOwner = document.getElementById('isowner_' + id).value;
        var submitform = document.getElementById('form_' + id);
        var deleteform = document.getElementById('delete_' + id)
        var reportform = document.getElementById('report_' + id)
        console.log(isOwner);
        console.log(showHtml);



        // Construct modal content
        var modalContent = `
        <p class="mb-4"><strong class="font-bold">${showId}</p>
        <p class="mb-4"><strong class="font-bold">${showName}</p>
        <p class="mb-4"><strong class="font-bold">${showCompany}</p>
        <p class="mb-4"><strong class="font-bold"></strong> ${showUser}</p>
        <div class="code-container mb-4 max-h-50">
            <div class="code-title text-gray-700 font-bold mb-2">HTML:</div>
            <pre class="code-content bg-gray-200 p-2 rounded-md overflow-auto"><code class="language-html">${showHtml}</code></pre>
        </div>
        <div class="code-container max-h-72 overflow-scroll">
            <div class="code-title text-gray-700 font-bold mb-2">CSS:</div>
            <pre class="code-content bg-gray-200 p-2 rounded-md overflow-auto"><code class="language-css">${showCss}</code></pre>
        </div>        
    `;

        // Set modal content
        document.getElementById('modalContent').innerHTML = modalContent;

        var srcdocContent = `
    <html>
      <head>
        <style>${showCss}</style>
      </head>
      <body>
        ${showHtml}
      </body>
    </html>
  `;

        // Set srcdoc for modal result frame
        var modalResultFrame = document.getElementById('modalResultFrame');
        modalResultFrame.innerHTML = ''; // Clear previous content if any
        var iframe = document.createElement('iframe');
        iframe.classList.add('result-frame');
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('srcdoc', srcdocContent);
        modalResultFrame.appendChild(iframe);

        var modalButtons = document.createElement('div');
        modalButtons.classList.add('mt-4');

        if (isOwner) {
          var editButton = document.createElement('button');
          editButton.textContent = 'Edit Component';
          editButton.classList.add('bg-blue-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'mr-2', 'focus:outline-none', 'hover:bg-blue-600');
          editButton.addEventListener('click', function(event) {
            // Redirect to edit page
            event.preventDefault();
            submitform.submit();

          });
          modalButtons.appendChild(editButton);

          var deleteButton = document.createElement('button');
          deleteButton.textContent = 'Delete Component';
          deleteButton.classList.add('bg-red-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'focus:outline-none', 'hover:bg-red-600');
          deleteButton.addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this component?')) {
              deleteform.submit();
              alert("successfully deleted component.")

            }

          });
          modalButtons.appendChild(deleteButton);
        } else {
          var reportButton = document.createElement('button');
          reportButton.textContent = 'Report Component';
          reportButton.classList.add('bg-red-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'mr-2', 'focus:outline-none', 'hover:bg-red-600');
          reportButton.addEventListener('click', function(event) {
            // Redirect to edit page
            event.preventDefault();
            reportform.submit();
          });
          modalButtons.appendChild(reportButton);
        }

        // Append buttons to modal container
        document.getElementById('modalContent').appendChild(modalButtons);


        // Show modal
        var modal = document.getElementById('componentModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Close modal event handler
        var closeModalBtn = document.getElementById('modalCloseBtn');
        closeModalBtn.addEventListener('click', function() {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
        });
      }
    </script>
</body>

</html>