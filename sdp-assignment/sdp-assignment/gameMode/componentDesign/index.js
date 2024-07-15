document.addEventListener("DOMContentLoaded", function () {
  const htmlInput = document.getElementById("htmlInput");
  const cssInput = document.getElementById("cssInput");
  const nameInput = document.getElementById("nameInput");
  const componentType = document.getElementById("component_type");
  const saveButton = document.getElementById("saveButton");
  const confirmationScreen = document.getElementById("confirmationScreen");
  const confirmationData = document.getElementById("confirmationData");
  const confirmButton = document.getElementById("confirmButton");
  const cancelButton = document.getElementById("cancelButton");
  const iframe = document.getElementById("iframe");
  const search = document.getElementById("searchInput");

  function updateIframe() {
    const htmlContent = htmlInput.value;
    const cssContent = cssInput.value;
    const content = `
      <html>
        <head>
          <style>${cssContent}</style>
        </head>
        <body>
          ${htmlContent}
        </body>
      </html>
    `;
    iframe.contentWindow.document.open();
    iframe.contentWindow.document.write(content);
    iframe.contentWindow.document.close();
  }

  htmlInput.addEventListener("input", updateIframe);
  cssInput.addEventListener("input", updateIframe);

  updateIframe(); // Call initially to populate iframe

  saveButton.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent form submission to show confirmation screen

    const componentName = nameInput.value;
    const htmlContent = htmlInput.value;
    const cssContent = cssInput.value;
    const isHouse = componentType.checked ? 1 : 0;

    // Escape HTML characters in the HTML content
    const escapedHtmlContent = escapeHtml(htmlContent);

    if (componentName === "" || htmlContent === "" || cssContent === "") {
      alert("Please fill out all fields: Name, HTML content, and CSS content.");
      return; // Exit the function early if fields are empty
    }
    console.log("passed");

    // Display the component details in the confirmation screen
    confirmationData.innerHTML = `
      <p>Name: ${componentName}</p>
      <p>HTML Content:</p>
      <pre>${escapedHtmlContent}</pre> <!-- Display HTML content -->
      <p>CSS Content:</p>
      <pre>${cssContent}</pre>
      <p>House Component?: ${isHouse ? "Yes" : "No"}</p>
    `;

    confirmationScreen.classList.remove("hidden");
  });

  confirmButton.addEventListener("click", function () {
    // Set the data to hidden form fields
    document.getElementById("myForm").submit();
    console.log("success");
  });

  cancelButton.addEventListener("click", function (Event) {
    Event.preventDefault();
    confirmationScreen.classList.add("hidden");
  });
});

// Function to escape HTML characters
function escapeHtml(html) {
  return html.replace(/</g, "&lt;").replace(/>/g, "&gt;");
}
