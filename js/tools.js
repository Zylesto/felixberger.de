function calculateDateDifference()
{
  var startDate = new Date(document.getElementsByName("from")[0].value);
  var endDate = new Date(document.getElementsByName("to")[0].value);
  var result = document.getElementById("result");
  result.innerHTML = "";
  if(startDate == "Invalid Date" || endDate == "Invalid Date"){
      result.innerHTML = "Bitte beide Felder ausfüllen."
  }
  else
  {
  var diff = Math.abs(endDate - startDate);
  var years = Math.abs(Math.floor((diff / (31536000000))));
  diff = diff - (years * 31536000000);
  var days = Math.abs(Math.floor((diff / (86400000))));
  diff = diff - (days * 86400000);
  var hours = Math.abs(Math.floor((diff / (3600000))));
  diff = diff - (hours * 3600000);
  var minutes = Math.abs(Math.floor((diff / (60000))));
  result.innerHTML += "Der Unterschied  liegt bei: <br>";
  result.innerHTML += years + " Jahren,<br>";
  result.innerHTML += days + " Tagen,<br>";
  result.innerHTML += hours + " Stunden,<br>";
  result.innerHTML += minutes + " Minuten.<br>.";
  }
}
