$ ->
  $("#search-form").submit ->
    keywords = $("#search").val().trim();
    location.href = "http://myphalcon2/movies/search/#{keywords}"
    false
  if $('.next a').length
    key 'right',->
      location.href = $('.next a').attr('href')
  if $('.previous a').length
    key 'left',->
      location.href = $('.previous a').attr('href')


  # 收藏功能的设置
  $('#favorite-button').click ->
    if $('#favorite').attr('class').trim() is 'glyphicon glyphicon-heart'
      url = window.location.href + '/deleteFavorite'
      $.getJSON url,(data)->
        $('#favorite').attr('class','glyphicon glyphicon-heart-empty') if data.status is 'success'
    else
      url = window.location.href + '/addFavorite'
      $.getJSON url,(data)->
        $('#favorite').attr('class','glyphicon glyphicon-heart') if data.status is 'success'
  # 季数跳转功能
  $('#season').on 'change',->
    url = 'http://myphalcon2/movies/'+$(this).val()
    location.href = url
