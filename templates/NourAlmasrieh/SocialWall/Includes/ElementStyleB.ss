<div class="container typography cards_style">
    <div class="row mt-5 socialcards" <% if $ShowOnMasonry %> data-masonry='{"percentPosition": true }'<% end_if %>>
        <% if $PostsStyle == custom %>
            <% if $ShowOnMasonry %>
                <% loop $SpeziellePosts.Sort('SortOrder', 'ASC') %>
                    <% with $AllPosts %>
                        <div class="col-md-6 col-lg-4 col-xl-3 socialcard mb-3 mb-md-4">
                            <% include NourAlmasrieh\SocialWall\Includes\Cards %>
                        </div>
                    <% end_with %>
                <% end_loop %>
            <% else %>
                <% loop $SpeziellePosts.Sort('SortOrder', 'ASC') %>
                    <% with $AllPosts %>
                        <div class="col-md-6 col-lg-4 col-xl-3 socialcard mb-3 mb-md-4">
                            <% include NourAlmasrieh\SocialWall\Includes\Slides %>
                        </div>
                    <% end_with %>
                <% end_loop %>
            <% end_if %>
        <% else %>
            <% if $ShowOnMasonry %>
                <% loop $getOnPosts.Sort('CreatedDate', 'DESC') %>
                    <div class="col-md-6 col-lg-4 col-xl-3 socialcard mb-3 mb-md-4">
                        <% include NourAlmasrieh\SocialWall\Includes\Cards %>
                    </div>                      
                <% end_loop %>
            <% else %>
                <% loop $getOnPosts.Sort('CreatedDate', 'DESC') %>
                    <div class="col-md-6 col-lg-4 col-xl-3 socialcard mb-3 mb-md-4">
                        <% include NourAlmasrieh\SocialWall\Includes\Slides %>
                    </div>                      
                <% end_loop %>
            <% end_if %>
        <% end_if %>
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