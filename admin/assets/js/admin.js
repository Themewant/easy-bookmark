(function($) {

    $(document).ready(function(){

        let EASYBOOKMARK = {
            init: function () { 
                this.pluginSettings();
            },
            pluginSettings: function () { 
                // Click function
                $('.easy-bookmark-settings-tabs #tabs-nav li').click(function(){

                    $('#tabs-nav li').removeClass('active');
                    $(this).addClass('active');
                    $('.tab-content').hide();
                    var activeTab = $(this).find('a').attr('href');
                    $(activeTab).fadeIn();
                    return false;

                });
             }
            
        }
        EASYBOOKMARK.init();
        

    });

})(jQuery);