// meow-exit-intent.js
(function ($) {
  $(document).ready(function () {
    // Check if MeowExitIntentData is available
    if (typeof MeowExitIntentData === 'undefined') {
      return;
    }

    const popupId = MeowExitIntentData.popup_id;
    const nonce = MeowExitIntentData.nonce;
    const ajaxUrl = MeowExitIntentData.ajax_url;
    const aggressive = MeowExitIntentData.aggressive;
    const delay = MeowExitIntentData.delay;

    const popupElement = document.getElementById('meow-exit-intent-modal-' + popupId);

    const nyaobounceInstance = nyaobounce(popupElement, {
      aggressive: aggressive,
      delay: delay,
      cookieExpire: 7,
      callback: function () {
        // Send AJAX request to track view
        $.post(ajaxUrl, {
          action: 'meow_exit_intent_track_view',
          popup_id: popupId,
          security: nonce
        });
      },
    });

    // Hide the modal when clicking outside of it
    document.body.addEventListener('click', function () {
      popupElement.style.display = 'none';
    });

    // Prevent closing when clicking inside the modal
    popupElement.querySelector('.meow-modal').addEventListener('click', function (e) {
      e.stopPropagation();
    });

    // Track clicks inside the modal only once per display
    let clickCounted = false; // Flag to check if click has been counted
    popupElement.querySelector('.meow-modal-body').addEventListener('click', function () {
      if (!clickCounted) {
        // Send AJAX request to track click
        $.post(ajaxUrl, {
          action: 'meow_exit_intent_track_click',
          popup_id: popupId,
          security: nonce
        });
        clickCounted = true; // Set flag to true after counting the click
      }
    });

    // Reset clickCounted flag when the modal is displayed again
    nyaobounceInstance.fire = (function (originalFire) {
      return function () {
        clickCounted = false; // Reset the flag
        originalFire();
      };
    })(nyaobounceInstance.fire);
  });
})(jQuery);