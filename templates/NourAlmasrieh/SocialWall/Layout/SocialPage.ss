<% require javascript('nour-almasrieh/socialwall: client/javascript/masonry.min.js') %>
<% require css('nour-almasrieh/socialwall: client/scss/_socialselement.scss') %>
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
        <div class="row mt-5 socialcards" data-masonry='{"percentPosition": true }'>
            <% loop $getPosts.Sort('Date', 'DESC') %>
                <div class="col-md-6 col-lg-4 col-xl-3 socialcard mb-3 mb-md-4">
                    <% include NourAlmasrieh\SocialWall\Includes\Cards %>
                </div>
            <% end_loop %>
        </div>
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