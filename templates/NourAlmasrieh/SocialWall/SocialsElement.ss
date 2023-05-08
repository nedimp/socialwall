<% require javascript('client/javascript/masonry.min.js') %>
<% require javascript('client/javascript/swiper.min.js') %>
<% require themedCSS('client/scss/_socialselement') %>
<% require css('client/css/swiper.min.css') %>
<div ID="SocialsElement{$ID}" class="SocialsElement py-5" style="background-color: $BackgroundColor;">
    <% if $Title || $Content || $ButtonCaption %>
        <div class="container typography socialcards">
            <div class="row align-items-center <% if $BackgroundColor %> font_white--all <% end_if %>">
                <div class="col-12 mb-3 mb-md-4">
                    <% if $ButtonCaption || $LinkedPage || $ExternalLink %>
                        <div class="row align-items-md-center mb-4">
                            <div class="col-lg-8 col-md-6">
                                <h2 class="mb-3 mb-lg-0">
                                    $Title.RAW
                                </h2>
                            </div>
                            <div class="col-lg-4 col-md-6 text-md-end">
                                <div class="d-none d-md-block">
                                    <a href="<% if $ExternalLink %>$ExternalLink <% else %>$LinkedPage.Link<% end_if %>" <% if $ExternalLink %> target="_blank" <% end_if %> class="btn btn-primary bg-green">
                                        <% if $ButtonCaption %>
                                            $ButtonCaption
                                        <% else %>
                                            <%t Page.READMORE 'Mehr erfahren' %>
                                        <% end_if %>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <% else %>
                        <h2>
                            $Title.RAW
                        </h2>
                    <% end_if %>
                    $Content
                    <% if $ButtonCaption || $LinkedPage || $ExternalLink %>
                        <div class="d-block d-md-none my-2 my-md-0">
                            <a href="<% if $ExternalLink %>$ExternalLink <% else %>$LinkedPage.Link<% end_if %>" <% if $ExternalLink %> target="_blank" <% end_if %> class="btn btn-primary bg-green">
                                <% if $ButtonCaption %>
                                    $ButtonDesc
                                <% else %>
                                    <%t Page.READMORE 'Mehr erfahren' %>
                                <% end_if %>
                            </a>
                        </div>
                    <% end_if %>
                </div>
            </div>
        </div>
    <% end_if %>
    <% if $ElementStyle == Slides %>
        <div class="container typography socialcards my-md-4 my-xl-5">
            <div class="row">
                <div class="col-12">
                    <div class="swiper-container mt-5" id="social__element--{$ID}">
                        <div class="swiper-wrapper">
                            <% loop $getPosts.Sort('Date', 'DESC') %>
                                <% if $Bild %>
                                    <div class="swiper-slide socialcard">
                                        <% include NourAlmasrieh\SocialWall\Includes\Slides %>
                                    </div>
                                <% end_if %>
                            <% end_loop %>
                        </div>                
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    <% else_if $ElementStyle == Cards %>
        <div class="container typography cards_style d-none d-lg-block">
            <div class="row mt-5 socialcards" data-masonry='{"percentPosition": true }'>
                <% loop $getPosts.Sort('Date', 'DESC') %>
                    <div class="col-md-6 col-lg-4 col-xl-3 socialcard mb-3 mb-md-4">
                        <% include NourAlmasrieh\SocialWall\Includes\Cards %>
                    </div>
                <% end_loop %>
            </div>
        </div>
        <script>
            var msnry = new Masonry( '.socialcards', {
                  // options...
            });
            setInterval(function () {
                var msnry = new Masonry( '.socialcards', {
                  // options...
                });
            }, 1500)
        </script>
        <div class="container typography socialcards d-block d-lg-none">
            <div class="row">
                <div class="col-12">
                    <div class="swiper-container mt-5" id="social__element--{$ID}">
                        <div class="swiper-wrapper">
                            <% loop $getPosts.Sort('Date', 'DESC') %>
                                <% if $Bild %>
                                    <div class="swiper-slide socialcard">
                                        <% include NourAlmasrieh\SocialWall\Includes\Slides %>
                                    </div>
                                <% end_if %>
                            <% end_loop %>
                        </div>                
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    <% end_if %>
</div>
<script>
    let swiper{$ID} = new Swiper('#social__element--{$ID}', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: false,
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