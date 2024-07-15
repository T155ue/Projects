class Component {
  constructor() {
    this.htmlInput = document.getElementById("htmlinput"); // Reference to the HTML input field
    this.cssInput = document.getElementById("cssinput"); // Reference to the CSS input field
    this.iframe = document.getElementById("iframe"); // Reference to the iframe

    // Call updateIframe when the component is initialized
    this.updateIframe();

    // Listen for input events on the HTML input field
    this.htmlInput.addEventListener("input", () => this.updateIframe());

    // Listen for input events on the CSS input field
    this.cssInput.addEventListener("input", () => this.updateIframe());
  }

  handleInput() {
    // Check if input is detected in either HTML input field or CSS input field
    if (this.htmlInput.value || this.cssInput.value) {
      // Call updateIframe to update the iframe
      this.updateIframe();
      console.log("i am going to update");
    }
  }

  updateIframe() {
    var htmlContent = this.htmlInput.value; // Get the value of the HTML input field
    var cssContent = this.cssInput.value; // Get the value of the CSS input field
    var content = `
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Child HTML</title>
        <style id="iframe-css">
          ${cssContent}
        </style>
      </head>
      <body>
        ${htmlContent}
      </body>
    </html>
    `;
    this.iframe.contentWindow.document.open();
    this.iframe.contentWindow.document.write(content); // Write the combined content into the iframe
    this.iframe.contentWindow.document.close();
    console.log("Update successful");
  }
}

// Initialize the Component
const myComponent = new Component();
