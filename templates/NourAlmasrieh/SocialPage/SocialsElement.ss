<style>$TemplateInlineFile('/socialwall/client', '/css/_socialselement.css')</style>
<style>$TemplateInlineFile('/socialwall/client', '/css/swiper.min.css')</style>
<script>$TemplateInlineFile('/socialwall/client', '/javascript/swiper.min.js')</script>
<script>$TemplateInlineFile('/socialwall/client', '/javascript/masonry.min.js')</script>
<div ID="SocialsElement{$ID}" class="SocialsElement bg-blue py-lg-5">
    <div class="container-fluid typography socialcards">
        <div class="row align-items-center">
            <div class="col-12 col-xl-8 font_white--all mt-5 my-lg-5">
                <h2 class="h2">$Title.RAW</h2>
                $Content
                <% if $ButtonDesc %>
                    <a href="<% if $LinkedPage %>$LinkedPage.Link<% else %>$ExternalLink<% end_if %>" <% if $ExternalLink %>target="_blank" <% end_if %> 
                        class="btn btn-primary bg-green mt-3">
                        $ButtonDesc
                    </a>
                <% end_if %>
            </div>
        </div>
    </div>
    <div class="swiper-container mt-5" id="social__element--{$ID}">
        <div class="swiper-wrapper align-items-center">
            <% with $SocialPage %>
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
            <% end_with %>
        </div>
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