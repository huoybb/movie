<div><span class="label label-primary">共计{{ page.total_items }}部电影</span>--<span class="label label-primary">第{{ page.current }}页/总{{ page.total_pages }}页</span></div>
{% if page.total_pages %}
<nav>
    <ul class="pager">
        <li class="previous"><a href="{{ url.get(['for':'index.page','page':page.before]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
        <li class="next"><a href="{{ url.get(['for':'index.page','page':page.next]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
    </ul>
</nav>
{% endif %}


{#<table class="table table-hover">#}
    {#<tr>#}
        {#<td>#</td>#}
        {#<td>电影片名</td>#}
        {#<td>上传时间</td>#}
    {#</tr>#}
    {#{% for movie in page.items %}#}
        {#<tr>#}
            {#<td>{{movie.id}}</td>#}
            {#<td><span>{{ link_to(['for':'movies.show','movie':movie.id],movie.title) }}</span></td>#}
            {#&#123;&#35;<td> <span><?=Carbon\Carbon::parse($movie->created_at)->diffForHumans()?></span></td>&#35;&#125;#}
            {#<td> <span>{{ movie.created_at.diffForHumans() }}</span></td>#}
            {#&#123;&#35;<td> <span>{{ movie.created_at }}</span></td>&#35;&#125;#}
        {#</tr>#}
    {#{% endfor %}#}
{#</table>#}


   {% include 'movies/partials/movielist3.volt' %}



{% if page.total_pages %}
<nav>
    <ul class="pager">
        <li class="previous"><a href="{{ url.get(['for':'index.page','page':page.before]) }}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
        <li class="next"><a href="{{ url.get(['for':'index.page','page':page.next]) }}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
    </ul>
</nav>
{% endif %}