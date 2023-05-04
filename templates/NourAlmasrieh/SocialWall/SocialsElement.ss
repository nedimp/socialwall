<style>$TemplateInlineFile('/socialwall/client', '/css/_socialselement.css')</style>
<style>$TemplateInlineFile('/socialwall/client', '/css/swiper.min.css')</style>
<script>$TemplateInlineFile('/socialwall/client', '/javascript/swiper.min.js')</script>
<script>$TemplateInlineFile('/socialwall/client', '/javascript/masonry.min.js')</script>
<div ID="SocialsElement{$ID}" class="SocialsElement py-5" style="background-color: $BackgroundColor;">
    <div class="container-fluid typography socialcards my-md-4 my-xl-5">
        <div class="row align-items-center <% if $BackgroundColor %> font_white--all <% end_if %>">
            <div class="col-12 mb-3 mb-md-4">
                <% if $ButtonCaption || $ExternalLink %>
                    <div class="row align-items-md-center mb-4">
                        <div class="col-lg-8 col-md-6">
                            <h2 class="mb-3 mb-lg-0">
                                $Title.RAW
                            </h2>
                        </div>
                        <div class="col-lg-4 col-md-6 text-md-end">
                            <div class="d-none d-md-block">
                                <a href="<% if $ExternalLink %>$ExternalLink <% else %>$SocialPage.Link<% end_if %>" class="btn btn-primary bg-green">
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
                <% if $ButtonCaption || $ExternalLink %>
                    <div class="d-block d-md-none my-2 my-md-0">
                        <a href="<% if $ExternalLink %>$ExternalLink <% else %>$SocialPage.Link<% end_if %>" class="btn btn-primary bg-green">
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
    <% if $ElementStyle == Slides %>
        <div class="swiper-container mt-5" id="social__element--{$ID}">
            <div class="swiper-wrapper align-items-center">
                <% loop $getPosts %>
                    <% if $Bild %>
                        <div class="swiper-slide socialcard">
                            <div class="card">
                                <div class="card-body py-2">
                                    <% if $PlatformLinkConf %>
                                        <a href="$PlatformLinkConf" target="_blank">
                                            <% end_if %>
                                                <% if $ProfileImageConf %>
                                                    <img src="$ProfileImageConf.Fit(35,35).Link" loading="lazy" class="img-fluid profileimage d-inline-block me-3">
                                                <% end_if %>
                                                <% if $UsernameConf %>
                                                    <h6 class="mb-0 d-inline-block">$UsernameConf</h6>
                                                <% else %>
                                                    <% if $Username %>
                                                        <h6 class="mb-0 d-inline-block">$Username</h6>
                                                        <% end_if %>
                                                <% end_if %>
                                            <% if $PlatformLinkConf %>
                                        </a>
                                    <% end_if %>
                                </div>
                                <div class="p-0 position-relative">
                                    <div class="card-img-top">
                                        <a href="$PlatformLink" target="_blank">
                                            <img src="$Bild.FocusFill(700,700).URL" class="img-fluid w-100" alt="$Bild.AltText">
                                        </a>
                                    </div>
                                </div>
                                <div class="rounded-social-buttons platform__icon text-center">
                                    <a class="social-button facebook" href="$PlatformLink" target="_blank">
                                        <i class="fab $PlatformIconClass"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <% end_if %>
                <% end_loop %>
            </div>
        </div>
        <script>
            let swiper{$ID} = new Swiper('#social__element--{$ID}', {
                slidesPerView: 1,
                spaceBetween: 40,
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
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 2,
                        pagination: false,
                    },
                    992: {
                        slidesPerView: 3,
                    },
                    1200: {
                        slidesPerView: 5
                    }
                }
            })
        </script>
    <% else_if $ElementStyle == Cards %>
        <div class="container-fluid typography cards_style">
            <div class="row mt-5" data-masonry='{"percentPosition": true }'>
                <% loop $getPosts %>
                    <% include NourAlmasrieh\SocialWall\Includes\Cards %>
                <% end_loop %>
            </div>
        </div>
        <script>
            var msnry = new Masonry( '.socialcard', {
                container: '.container',
            });
            setInterval(function () {
                var msnry = new Masonry( '.socialcard', {
                // options...
                container: '.container',
                });
            }, 1500)
        </script>
    <% end_if %>
</div>