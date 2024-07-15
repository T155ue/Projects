const searchClick = () => {
  //   get the value of the input of searchBox
  const searchBox = document.getElementById("searchBox");
  const searchValue = searchBox.value;
  //   goto link ?search=value
  window.location.href = `?search=${searchValue}`;
};
