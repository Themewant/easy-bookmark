(function($) {

    $(document).ready(function(){

        let EASYBOOKMARK = {
            init: function () { 
                $( document )
                .on( 'click.EASYBOOKMARK', '.esbmrk-counter-box .esbmrk-counter', this.openBookmarkedPopup )
                .on( 'click.EASYBOOKMARK', '#esbmrk_popup_modal .esbmrk-modal-close', this.closeBookmarkedPopup )
                .on( 'click.EASYBOOKMARK', '.esbmrk-add-to-bookmark-btn', this.addToBookmark )
                .on( 'click.EASYBOOKMARK', '.esbmrk_delete_all_trigger', this.deleteAllPost )
                .on( 'change.EASYBOOKMARK', '.esbmrk-posts .esbmrk-remove-btn input[type="checkbox"]', this.deleteSinglePost )
                ;
            },
            openBookmarkedPopup: function (that) { 

                let perPage = $(this).data('per-page');


                 // Send AJAX request
                 $.ajax({
                    type: "POST",
                    url: ebm_ajax.ajaxurl,
                    data: {
                        action: 'esbmrk_get_easy_bookmarked_posts_list_html', 
                        perPage: perPage,
                        nonce: ebm_ajax.nonce,
                    },
                    success: function (response) {
                        if (response) {
                            $('#esbmrk_popup_modal .esbmrk-modal-body').html(response);
                        } 

                        $('#esbmrk_popup_modal').css('display', 'block');
                    }
                });
                
            },
            closeBookmarkedPopup: function () { 
                $('#esbmrk_popup_modal').css('display', 'none');
            },
            addToBookmark: function (e, that) { 

                // Prevent default action
               e.preventDefault();

               // Get post ID from the clicked button
               let bookmarkBtn = $(this);
               let postId = $(bookmarkBtn).data('post-id');
               let currentPostCount = $('.esbmrk-counter .esbmrk-total-marked').html();
               let bookmarkedTooltip = $(bookmarkBtn).data('bookmarked-tooltip');
               let bookmarkedLabel = $(bookmarkBtn).data('bookmarked-label');

               if(!$(this).hasClass('esbmrk-marked')){
                   $('.esbmrk-counter .esbmrk-total-marked').html(parseFloat(currentPostCount) + parseFloat(1));
                   $.ajax({

                       type: "POST",
                       url: ebm_ajax.ajaxurl,
                       data: {
                           action: 'esbmrk_add_to_easy_bookmark',
                           postId: postId,
                           nonce: ebm_ajax.nonce,
                       },
                       success: function (response) {
                           $(bookmarkBtn).addClass('esbmrk-marked');
                           $(bookmarkBtn).find('.esbmrk-tooltip').html(bookmarkedTooltip);
                           $(bookmarkBtn).find('.esbmrk-bookmark-label').html(bookmarkedLabel);
                       }
                   });
               }else{
                   EASYBOOKMARK.deletePost(postId);
                   $(bookmarkBtn).removeClass('esbmrk-marked');
                   
               }
              
            },
            deletePost: function (postId) { 

                let bookmarkBtn = $('a[data-post-id="'+postId+'"].esbmrk-add-to-bookmark-btn');
                let bookmarkTooltip = $(bookmarkBtn).data('bookmark-tooltip');
                let bookmarklabel = $(bookmarkBtn).data('bookmark-label');
               
                let emptyBookmarkLabel = ebm_ajax.empty_label;
                


                // Send AJAX request
                $.ajax({
                    type: "POST",
                    url: ebm_ajax.ajaxurl,
                    data: {
                        action: 'esbmrk_delete_from_easy_bookmarked', 
                        postId: postId,  // Send array of post IDs
                        nonce: ebm_ajax.nonce,
                    },
                    success: function (response) {
                        
                      
                        if (response.success) {

                            if(response.data.empty_bookmark_label != ''){
                                emptyBookmarkLabel = response.data.empty_bookmark_label;
                            }
                            
                            // Optionally, remove the deleted posts from the UI
                            $('input[data-post-id="'+postId+'"]').closest('.esbmrk-checkbox-label').parent().remove();

                            $(bookmarkBtn).removeClass('esbmrk-marked');
                            $(bookmarkBtn).find('.esbmrk-tooltip').html(bookmarkTooltip);
                            $(bookmarkBtn).find('.esbmrk-bookmark-label').html(bookmarklabel);
                            

                            let currentPostCount = $('.esbmrk-counter .esbmrk-total-marked').html();
                            let updatedPostCount = parseFloat(currentPostCount) - parseFloat(1);

                            
                            

                            $('.esbmrk-counter .esbmrk-total-marked').html(updatedPostCount);

                            if(updatedPostCount < 1) {
                                $('#esbmrk_popup_modal .esbmrk-action-bar').remove();
                                $('.esbmrk-posts').html('<div class="esbmrk-empty-posts">' + emptyBookmarkLabel + '</div>');
                            }
                        } else {
                        }
                    }
                });
            },
            deleteSinglePost: function (that) { 
                let postId = $(this).data('post-id');
                EASYBOOKMARK.deletePost(postId);
               
                
            },
            deleteAllPost: function (that) { 

                $('.esbmrk-deleteing-loader').css('display', 'flex');
            
                // Send AJAX request
                $.ajax({
                    type: "POST",
                    url: ebm_ajax.ajaxurl,
                    data: {
                        action: 'esbmrk_delete_all_from_easy_bookmarked', 
                        nonce: ebm_ajax.nonce,
                    },
                    success: function (response) {
                        if (response.success) {
                            // Optionally, remove the deleted posts from the UI
                            $('#esbmrk_popup_modal .esbmrk-action-bar').remove();
                            $('.esbmrk-posts').html($('<div class="esbmrk-empty-posts"></div>').text(ebm_ajax.empty_short));
                            $('.esbmrk-counter .esbmrk-total-marked').html(0);
                            
                            setTimeout(() => {
                                $('#esbmrk_popup_modal .esbmrk-deleteing-loader').css('display', 'none');
                            }, 2000);
                        } else {
                        }
                    }
                });
            }
            
        }
        EASYBOOKMARK.init();
        

    });

})(jQuery);