// declare iframe
var iframe = document.getElementById("mapIframe");
var scale = 1;

// zoom in button
document.querySelector("[zoomIn]").addEventListener("click", function () {
  scale *= 1.2;
  //limit zoom in
  if (scale > 2) {
    scale = 2;
  }
  iframe.style.transform = "scale(" + scale + ")";
});

// zoom out button
document.querySelector("[zoomOut]").addEventListener("click", function () {
  scale /= 1.2;
  // limit zoom out
  if (scale < 1) {
    scale = 1;
  }
  iframe.style.transform = "scale(" + scale + ")";
});

// refresh button
document.querySelector("[refresh]").addEventListener("click", function () {
  var dragMapHint = document.getElementsByClassName("dragMapHint")[0];
  var infoSections = document.getElementsByClassName("infoSection");

  // reset side bar
  dragMapHint.classList.add("show");
  Array.from(infoSections).forEach(function (section) {
    section.classList.remove("show");
  });
  // refresh iframe
  iframe.contentWindow.location.reload(true);
});

// back button
document.querySelector("[back]").addEventListener("click", function () {
  // go back to previous page
  window.history.back();
});

// get player id message from innerMap
window.addEventListener("message", function (event) {
  if (event.data.type === "innerMapClicked") {
    var ClickedPlayerId = event.data.id;
    //console.log(ClickedPlayerId + " clicked");

    var userId = findUser(ClickedPlayerId);
    displayUser(userId);
  }
});

// get build id message from innerMap
window.addEventListener("message", function (event) {
  if (event.data.type === "innerMapID") {
    var InnerMapID = event.data.id;
    //console.log(InnerMapID + " clicked");

    var buildID = findBuild(InnerMapID);
    displayBuild(buildID);
  }
});

// player id = user id
function findUser(ClickedPlayerId) {
  return data.find(function (entry) {
    return entry.PlayerID === ClickedPlayerId;
  });
}

// build id = component id
function findBuild(InnerMapID) {
  return data.find(function (entry) {
    return entry.ComponentID === InnerMapID;
  });
}

// side bar: learn more button & component name
function displayBuild(BuildID) {
  if (BuildID) {
    var componentName = document.querySelector("[componentNameSection]");
    var learnMoreButton = document.querySelector("[learnMoreButton]");

    componentName.textContent = "Component Name: " + BuildID.component_name;

    learnMoreButton.addEventListener("click", function () {
      showLearnMorePopUp(BuildID);
    });
    learnMoreButton.textContent = BuildID.component_name + " build";
  }
}

// side bar: username & image user profile button
function displayUser(userId) {
  if (userId) {
    var dragMapHint = document.getElementsByClassName("dragMapHint")[0];
    var userImage = document.querySelector("[imageSection]");
    var username = document.querySelector("[usernameSection]");
    var userButton = document.querySelector("[userButton]");
    var infoSections = document.getElementsByClassName("infoSection");

    dragMapHint.classList.remove("show");
    Array.from(infoSections).forEach(function (section) {
      section.classList.add("show");
    });

    userImage.innerHTML = "";
    var userImg = document.createElement("img");
    userImg.src = userId.image;
    userImage.appendChild(userImg);

    username.textContent = "Designer: " + userId.username;

    // construct link for user profile button
    userButton.addEventListener("click", function () {
      window.location.href =
        "http://localhost/sdp-assignment/user/?id=" + userId.userid;
    });
    userButton.textContent = userId.username + "'s " + "Profile";
  }
}

// declare pop up window
var learnMorePopUp = document.getElementsByClassName("learnMorePopUp")[0];
// declare pop up window back button
var popUpBackButton = document.getElementById("popUpBackButton");

popUpBackButton.addEventListener("click", function () {
  learnMorePopUp.classList.remove("show");
});

// call load left & right box in popup window
function showLearnMorePopUp(BuildID) {
  learnMorePopUp.classList.add("show");
  loadLeftSideBox(BuildID);
  loadRightSideBox(BuildID);
}

// load left: house build
function loadLeftSideBox(BuildID) {
  var houseBox = document.getElementById("houseLeftSide");

  houseBox.innerHTML = "";

  houseIframe = document.createElement("iframe");

  houseBox.appendChild(houseIframe);

  var houseDoc = houseIframe.contentDocument;

  var css = BuildID.component_css;
  var html = BuildID.component_html;

  html = html.replace(/&lt;/g, "<");
  html = html.replace(/&gt;/g, ">");

  houseDoc.open();
  houseDoc.write("<style>" + css + "</style>" + html);
  houseDoc.close();
}

// load right: HTML & CSS info
function loadRightSideBox(BuildID) {
  var htmlBox = document.getElementById("htmlRightSide");
  var cssBox = document.getElementById("cssRightSide");

  htmlBox.innerHTML = "";
  cssBox.innerHTML = "";

  htmlIframe = document.createElement("iframe");
  cssIframe = document.createElement("iframe");

  htmlBox.appendChild(htmlIframe);
  cssBox.appendChild(cssIframe);

  var htmlDoc = htmlIframe.contentDocument;
  var cssDoc = cssIframe.contentDocument;

  var css = BuildID.component_css;
  var html = BuildID.component_html;

  htmlDoc.open();
  htmlDoc.write(html);
  htmlDoc.close();

  cssDoc.open();
  cssDoc.write(css);
  cssDoc.close();
}
