{% if movie.getEpisodes() %}
<div class="row">
    <h2>剧集
        {% if movie.isSerialable() AND movie.isLastSeason() AND movie.isAiring() %}
            <span><a href="{{ url(['for':'movies.updateEpisodesInfo','movie':movie.id]) }}">更新</a></span>
            {% if movie.getTVSerial().getFirstKATLink() %}
                <span><a href="{{ movie.getTVSerial().getFirstKATLink().url }}" target="_blank">KAT</a></span>
            {% endif %}
        {% endif %}
    </h2>
    {% for myEpisode in movie.getEpisodes() %}
        {% if episode is defined AND myEpisode.id == episode.id  %}
            {% set buttonStyle = 'btn btn-primary disabled' %}
        {% else %}
            {% if myEpisode.isPublished() %}
                {% set buttonStyle = 'btn btn-success' %}
            {% else %}
                {% set buttonStyle = 'btn btn-default' %}
            {% endif %}
        {% endif %}
        <a  class="{{ buttonStyle }}" style="margin-top: 4px" href="{{ url(['for':'movies.showEpisode','movie':movie.id,'episode':myEpisode.id]) }}" role="button" title="{{ myEpisode.title }}: {{ myEpisode.date.format('l M d, Y') }}">{{ "%'.02d" |format(myEpisode.num)}}</a>
    {% endfor %}
</div>
{% endif %}