(function($){

 // $("#plagScanButton").click(function() {
  $("n-Evaluar-Página").click(function() {
    //This is the current page's URL, it needs to be sent as a callback refernce
    var $location = $(location).attr('href');
    //This is the div where MediaWiki stores all the body context that a user writes
   //var $content = $("mw-content-text").text();
    var $content = $("bodyContent").text();	
    $.ajax({
      //The URL needs to be the relative path I.E. "~/Extensions/SubmitPlagScan.php"
      url: "/plagscan/SubmitPlagScan.php",
      type: "POST",
      dataType: "json",
      contentType: "application/json",
      data: { PageTitle: $location, Text: $content },
      async: true,
      success: function(json) {
        //I'm just doing a simple JS alert for this, but you could do a modal or
        //whatever notification you'd like. This is only going to say that it submitted
        //successfully or not, the actual scan takes time so a callback is needed
        if (json.Error) {
          alert(json.Error);
        }
        else {
          alert(json.Content);
        }
      },
      error: function(json) {
        alert(json);
      }
    });
  });

})(jQuery);
