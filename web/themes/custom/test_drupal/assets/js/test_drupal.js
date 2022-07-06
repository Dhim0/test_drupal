(function ($, Drupal) {
  Drupal.behaviors.testDrupal = {
    attach: function (context, settings) {
      testDrupal.onReady();
    }
  };
  const testDrupal = {
    main: function () {
      testDrupal.agenda();
      testDrupal.relatedBlock();
    },
    agenda: function () {
      if ($('.view-id-agenda .button').length) {
        testDrupal.selectContainer();
      }
    },
    relatedBlock: function () {
      if ($('.block-related-event-block .event-link').length) {
        testDrupal.manageOverlay();
      }
    },
    manageOverlay: function () {
      let content = $('.block-related-event-block .event-link');
      content.hide();
      $(".block-related-event-block h3").click(function(){
        content.show();
      });
      $(".block-related-event-block .event-link svg").click(function(){
        content.hide();
      });
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
