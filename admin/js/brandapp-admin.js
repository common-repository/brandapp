(function($) {
    'use strict';
    var BrandappWordPressAdmin = (function() {

        return {
            init: function() {
                $(document).ready(function($) {
                    var url;
                    
                    if (window.location.host == 'localhost') {
                        url = 'https://localhost:8081/wordpress/admin/js/WordPressUtils.js';
                    }
                    else {
                        url = 'https://brandapp.io/wordpress/admin/js/WordPressUtils.js';
                    }
                    
                    $.ajax({
                        cache: true,
                        dataType: 'script',
                        error:  function() {
                            console.log('error!');
                            alert('Could not intitialize BrandApp plugin');
                        },
                        success: function() {
                            // console.log('success!');
                            window.BrandappWordPressUtils.init($);
                        },
                        // timeout: __timeout,
                        url: url
                    });
                });
            }
        };
    })();

    // Launch init
    BrandappWordPressAdmin.init();
})(jQuery);
