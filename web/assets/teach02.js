/* Function: alertClicked
  When the "Click Me" button is clicked, alert the user that it has been clicked.
    Input: 
      none
    Output:
      none
*/
function alertClicked() {
  alert("Clicked!");
}

/* Function: changeColor
  When the "Change Color" button is clicked, change the background color of the first div based on user input
    Input: 
      none
    Output:
      none
*/
function changeColor() {
  var userColor = document.getElementById("div1newcolortext").value;
  document.getElementById("div1").style.backgroundColor = userColor;
}
