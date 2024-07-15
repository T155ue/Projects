// declare initial points for Mouse
var startPointX = 0;
var startPointY = 0;

// declare initial points for element Offset
var offsetPointX = 0;
var offsetPointY = 0;

// declare initial dragElement
var dragElement;

// declare mouse up and down event
document.onmousedown = OnMouseDown;
document.onmouseup = OnMouseUp;

// window load
window.onload = function () {
  startMap();
};

// initialize map
function startMap() {
  componentData.forEach(function (entry) {
    insertIframe(entry);
  });
}

// Insert house (Main Part)
function insertIframe(entry) {
  var spawnArea = document.getElementById("moveArea");
  var iframe = document.createElement("iframe");

  // disable iframe scroll
  iframe.scrolling = "no";
  // set mouse event to auto initially
  iframe.style.pointerEvents = "auto";

  // set positiona and coordinates
  iframe.style.position = "absolute";
  iframe.style.left = randomNumGen() + "px";
  iframe.style.right = randomNumGen() + "px";
  iframe.style.top = randomNumGen() + "px";
  iframe.style.bottom = randomNumGen() + "px";

  // add iframe (empty)
  spawnArea.appendChild(iframe);

  // iframe post message to index when clicked
  iframe.addEventListener("load", function () {
    this.contentWindow.document.addEventListener("click", function (event) {
      document.querySelectorAll("iframe").forEach((iframe) => {
        if (iframe.style.pointerEvents === "none") {
          iframe.style.pointerEvents = "auto";
        }
      });
      //console.log("innerMapPlayer " + entry.PlayerID);
      window.parent.postMessage(
        { type: "innerMapClicked", id: entry.PlayerID },
        "http://localhost/sdp-assignment/gamemode/mapExplore/index.php"
      );
      //console.log("innerMapID " + entry.ComponentID);
      window.parent.postMessage(
        { type: "innerMapID", id: entry.ComponentID },
        "http://localhost/sdp-assignment/gamemode/mapExplore/index.php"
      );
    });
  });

  var doc = iframe.contentDocument;

  var css = entry.component_css;
  var html = entry.component_html;

  // replace < > in HTML data
  html = html.replace(/&lt;/g, "<");
  html = html.replace(/&gt;/g, ">");

  // draw the house in iframe
  doc.open();
  doc.write("<style>" + css + "</style>" + html);
  doc.close();
}

// true randomizer
function randomNumGen() {
  // set gate random
  gate = Math.floor(Math.random() * 4);
  // random positive or negative
  if (gate >= 0 && gate < 2) {
    num = -5000;
    // more random
    return Math.floor(Math.random() * num);
  } else {
    num = 5000;
    return Math.floor(Math.random() * num);
  }
}

// function for when Mouse is Held Down
function OnMouseDown(event) {
  document.onmousemove = OnMouseMove;

  document.querySelectorAll("iframe").forEach((iframe) => {
    iframe.addEventListener("mouseover", function () {
      iframe.style.pointerEvents = "none";
    });
  });

  // set initial point to Current Point on screen
  startPointX = event.clientX;
  startPointY = event.clientY;

  // set offset point to moveArea offset
  offsetPointX = document.getElementById("moveArea").offsetLeft;
  offsetPointY = document.getElementById("moveArea").offsetTop;
  dragElement = document.getElementById("moveArea");
}

// function for when Mouse Move Around when Held Down
function OnMouseMove(event) {
  // updates the element style (left and top) according to Mouse Point
  dragElement.style.left =
    offsetPointX + (event.clientX - startPointX) * 5 + "px";
  dragElement.style.top =
    offsetPointY + (event.clientY - startPointY) * 5 + "px";
}

// function for when Mouse is Released
function OnMouseUp(event) {
  document.onmousemove = null;
  dragElement = null;
  iframeCheck();
}

// iframe manage so no mouse cursor problem
function iframeCheck() {
  document.querySelectorAll("iframe").forEach((iframe) => {
    if (iframe.style.pointerEvents === "none") {
      iframe.style.pointerEvents = "auto";
    }
  });
}

// fix mouse hover over iframe bug, set 1 sec timer between click
// enable double click
if (!onmousedown) {
  setInterval(iframeCheck, 1000);
}
