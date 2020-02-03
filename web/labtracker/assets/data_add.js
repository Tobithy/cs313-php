/* Function: adjustTestFormat
  This function adjusts the value input to be either data_float or data_text based on the chosen Type of test
    Input: 
      none
    Output:
      rewrites the HTML
*/
function testFormatAdjust() {
  // most straightforward way to find our row is to use a loop
  for (var r = 0; r < clinicalTests.length; r++){
    if (this.value == clinicalTests[r][0]) {
      // we have a match! now find the corresponding data format, and then write the html
      if (clinicalTests[r][1] == 'FLOAT'){
        // build the HTML to put into .html
        var floatHTML = '<label for="data_float" class="col-lg-2 col-form-label">Lab result</label>';
        floatHTML += '<div class="col-lg-10">';
        floatHTML += '<input type="number" class="form-control" name="data_float" step="any" id="data_float" required>';
        floatHTML += '</div>';
        $('#lab_result_div').html(floatHTML);
      }
      else if (clinicalTests[r][1] == 'TEXT'){
        // build the HTML to put in into .html
        var textHTML = '<label for="data_text" class="col-lg-2 col-form-label">Lab result</label>';
        textHTML += '<div class="col-lg-10">';
        textHTML += '<input type="text" class="form-control" name="data_text" step="any" id="data_text" required>';
        textHTML += '</div>';
        $('#lab_result_div').html(textHTML);
      }
      else {
        alert('This shouldn\'t happen!!')
      }
    }
  }
}


/*
function changeColor() {
  var userColor = document.getElementById("div1newcolortext").value;
  document.getElementById("div1").style.backgroundColor = userColor;
}
*/
// Only load functions once the DOM is ready
$(document).ready(function(){
  $('#clinical_test_label').on('change', testFormatAdjust);
});