$(document).ready(function(){
    $("#inputNamePlugin").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#plugins ").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });