<style>$TemplateInlineFile('/socialwall/client', '/css/_socialselement.css')</style>
<style>$TemplateInlineFile('/socialwall/client', '/css/swiper.min.css')</style>
<script>$TemplateInlineFile('/socialwall/client', '/javascript/swiper.min.js')</script>
<script>$TemplateInlineFile('/socialwall/client', '/javascript/masonry.min.js')</script>
<% include PageHeader %>
<div class="socialpage position-relative">
    <div class="container typography">
        <div class="row ">
            <% if $SubTitle %>
                <div class="col-12">
                    <span class="subtitle mb-3 d-block">
                        $SubTitle
                    </span>
                </div>
            <% end_if %>
            <div class="col-12 col-md-6">
                <span class="h2 mb-4 d-block">
                    $ContentTitle.RAW
                </span>
            </div>
            <% if $Content %>
                <div class="col-12 mt-md-3 mt-2">
                    $Content
                </div>
            <% end_if %>
        </div>
        <% if $SecondContent %>
            $SecondContent
        <% end_if %>
    </div>
    <div class="container typography">
        <div class="row mt-5" data-masonry='{"percentPosition": true }'>
            <% loop $getPosts %>
                <div class="col-md-6 col-lg-4 col-xl-3 socialcard mb-3 mb-md-4">
                    <div class="card">
                        <% if $Bild %>
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
                        <% end_if %>
                        <div class="card-footer">
                            <div class="p-3 p-md-4">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="$PlatformLink" target="_blank" class="font-black">
                                            $Message.LimitWordCount(30, ' ...').RAW
                                        </a>
                                    </div>
                                </div>
                                <div class="row align-items-center mt-3">
                                    <div class="col-12">
                                        <span>
                                            $CreatedDate
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <% end_loop %>
        </div>
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