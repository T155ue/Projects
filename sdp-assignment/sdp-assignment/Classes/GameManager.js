export default class GameManager {
  constructor() {
    this.styleInput = document.getElementById("style-input");
    this.htmlInput = document.getElementById("html-input");
    this.iframeDoc = document.getElementById("iframe").contentDocument;
    this.iframeUpdateStyle = this.iframeUpdateStyle.bind(this);
  }
  iframeUpdateStyle(iframeHTML) {
    let iframe = this.iframeDoc;

    iframe.open();
    iframe.write(iframeHTML);
    iframe.close();
  }

  generateHtml(style, html) {
    return `
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Child HTML</title>
        <style id="iframe-css">
          ${style}
        </style>
      </head>
      <body>
        ${html}
      </body>
    </html>
  `;
  }

  init() {
    this.styleInput.addEventListener("input", () => {
      this.iframeUpdateStyle(
        this.generateHtml(this.styleInput.value, this.htmlInput.value)
      );
    });
    this.htmlInput.addEventListener("input", () => {
      this.iframeUpdateStyle(
        this.generateHtml(this.styleInput.value, this.htmlInput.value)
      );
    });
  }
}
