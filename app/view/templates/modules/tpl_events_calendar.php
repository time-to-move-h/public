<div id="tpl_evt" style="display: none">
<div class="divTableRow event-item">
    <div class="divTableCell upcoming">
        <div class="date-event">
            <svg class="olymp-small-calendar-icon"><use xlink:href="/img/i/icons.svg#olymp-small-calendar-icon"></use></svg>
            <span class="day">{{=it.DAY}}</span>
            <span class="month">{{=it.MONTH}}</span>
        </div>
    </div>
    <div class="divTableCell author">
        <div class="event-author inline-items">
            <div class="author-date break-word">
                <a href="/event/{{=it.URLLINK}}" class="author-name h6">{{=it.TITLE}}</a>
                <time class="published" datetime="">{{=it.DATBEG}}</time>
            </div>
        </div>
    </div>
</div>
</div>