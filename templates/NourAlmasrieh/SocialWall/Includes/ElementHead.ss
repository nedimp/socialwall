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