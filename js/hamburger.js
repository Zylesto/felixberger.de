function animateHamburger(elem){
  elem.classList.toggle("change");
  var m = document.getElementsByClassName("mobile_links")[0];
  if (m.style.display === "block") {
      m.style.display = "none";
    }
    else {
      m.style.display = "block";
    }
}
