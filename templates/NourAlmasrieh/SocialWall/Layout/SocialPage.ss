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
                <% include NourAlmasrieh\SocialWall\Includes\Cards %>
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