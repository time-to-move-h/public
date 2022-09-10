<div id="tpl_mytickets" style="display: none">
<div class="divTableRow event-item">
<div class="divTableCell author">
    <div class="event-author inline-items">
            <div class="author-date break-word" style="padding: 5px;">
                <a href="/order/details/{{=it.TICORDER}}" class="author-name h6"><strong>{{=it.TITLE}}</strong></a><br>
                {{~it.DETAILS :value:index}}
                <span class="published">{{=value.ITEM_QTE}} x {{=value.NAME}}</span><br>
                {{~}}
            </div>
            </div>
    </div>
</div>
</div>
</div>
<!--({{=value.ITEM_PRICE}} &euro;)-->