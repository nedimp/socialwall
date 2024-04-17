<% require javascript('nedimp/socialwall: client/javascript/masonry.min.js') %>
<% require javascript('nedimp/socialwall: client/javascript/swiper.min.js') %>
<% require javascript('nedimp/socialwall: client/javascript/customScripts.js') %>
<% require css('nedimp/socialwall: client/css/swiper.min.css') %>
<% require css('nedimp/socialwall: client/scss/_socialselement.scss') %>
<div ID="SocialsElement{$ID}" class="SocialsElement py-5" style="background-color: $BackgroundColor;">
    <% include NourAlmasrieh\SocialWall\Includes\ElementHead %>
    <% if $ElementStyle == Slides %>
        <% include NourAlmasrieh\SocialWall\Includes\ElementStyleA %>  
    <% else_if $ElementStyle == Cards %>
        <div class="d-none d-lg-block">
            <% include NourAlmasrieh\SocialWall\Includes\ElementStyleB %> 
        </div>
        <div class="d-block d-lg-none">
            <% include NourAlmasrieh\SocialWall\Includes\ElementStyleA %>
        </div>
    <% end_if %>
</div>