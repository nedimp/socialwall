<div class="container typography socialcards my-md-4 my-xl-5">
    <div class="swiper-container mt-5" id="social__element--{$ID}">
        <div class="swiper-wrapper mb-5">
            <% if $PostsStyle == custom %>
                <% loop $SpeziellePosts.Sort('SortOrder', 'ASC') %>
                    <% with $AllPosts %>
                        <div class="swiper-slide socialcard">
                            <% include NourAlmasrieh\SocialWall\Includes\Slides %>                             
                        </div>
                    <% end_with %>
                <% end_loop %>
            <% else %>
                <% loop $getOnPosts.Sort('CreatedDate', 'DESC') %>
                    <div class="swiper-slide socialcard">
                        <% include NourAlmasrieh\SocialWall\Includes\Slides %>                             
                    </div>
                <% end_loop %>
            <% end_if %>
        </div>                
        <div class="swiper-pagination"></div>
    </div>
</div>
<script>
    let swiper{$ID} = new Swiper('#social__element--{$ID}', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        preloadImages: false,
        lazy: {
            loadPrevNext: true,
        },
        grabCursor: true,
        autoplay: {
            delay: 3000,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            430: {
                slidesPerView: 1.2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 2.2,
                paceBetween: 20,
            },
            992: {
                slidesPerView: 3,
                paceBetween: 30,
            },
            1200: {
                slidesPerView: 4
            }
        }
    })
</script>
<script>
    window.addEventListener("load", function(){
        equalHeight('.equalHeight')
    })
    window.addEventListener("resize", function(){
        setTimeout(function(){
            equalHeight('.equalHeight')
        })
    })
</script>