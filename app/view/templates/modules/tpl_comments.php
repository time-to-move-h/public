<!--Template Comments-->
<div id="tpl_comments" style="display: none"> 
<li>
    <div class="post__author author vcard inline-items">
<!--            <img src="/img/authorpage.jpg" alt="author">-->
            <div class="author-date">
                    <a class="h6 post__author-name fn" href="#">{{=it.USR}}</a>
                    <div class="post__date">
                            <time class="published" datetime="{{=it.DATCRE}}">
                                   {{=it.DATCRE}}
                            </time>
                    </div>
            </div>
            <!-- <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="/img/i/icons.svg#olymp-three-dots-icon"></use></svg></a> -->
    </div>
    <p>{{=it.DESC}}</p>
    <!-- <a href="#" class="post-add-icon inline-items">
            <svg class="olymp-heart-icon"><use xlink:href="/img/i/icons.svg#olymp-heart-icon"></use></svg>
            <span>3</span>
    </a>    
    <a href="#" class="reply">Reply</a>-->
</li>
</div> 