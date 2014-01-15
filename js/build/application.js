// Testing AJAX calls

$('#test').on('click', function(){
  loader($(this));
  getData.api('get_post/?post_id=14/', function(data){
    $('#ajax-here').html(
      'this post title = ' + data.title +
      '<br/>and more content = ' + data.custom_fields.test +
      '<br/>and even more stuff like an image = <img src="' + data.acf.image.url + '"/>');
  });
});

var getData = function(){
  var apiUrl = '/5andbox-test-install/api/',
  api = function(method, callback){
    $.getJSON(apiUrl + method, function(data) {
      callback(data.post);
      $('.loading').remove();
    });
  };
  return {
    api: api
  };
} ();

var loader = function(el){
  var animation = '<div class="loading">loading...</div>';
  el.append(animation);
};