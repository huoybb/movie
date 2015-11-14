<div class="row">
    {% for movie in page.items %}
        <div class="col-md-5 movie-list">
            <div class="col-md-4">
                <a href="{{ url.get(['for':'movies.show','movie':movie.id]) }}"><img class="src" src="{{ url.getBaseUri() }}{{ movie.poster }}" alt="{{ movie.title }}"/></a>
            </div>
            <div class="col-md-8">
                <div class="row"><h4>{{ movie.title }}</h4></div>
                <div class="row">{{ movie.director }}</div>
                <div class="row">{{ movie.release_time }}</div>
            </div>

        </div>
    {% endfor %}
</div>