(function ($, Drupal) {
  Drupal.behaviors.testDrupal = {
    attach: function (context, settings) {
      testDrupal.onReady();
    }
  };
  const testDrupal = {
    main: function () {
      testDrupal.agenda();
    },
    agenda: function () {
      if ($('.view-id-agenda .button').length) {
        testDrupal.selectContainer();
      }
    },
    selectContainer: function () {
      $( ".views-col .button button" ).on( "click", function() {
        let container = $( this ).closest('.views-col');
        let newColor = "rgb(255, 255, 0)";

        if (container.css("background-color") == newColor) {
          container.css("background-color", "");
        }
        else {
          container.css("background-color", newColor);
        }
      });
    },
    onReady: function () {
      testDrupal.main();
    }
  };
})(jQuery, Drupal);
